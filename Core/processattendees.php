<?
/*
* This file is responsible for processing meetings from DB and fetching attendees respective contact from
* salesforce and than mapping it within DB for easy lookup and reference.
*/

error_reporting(E_ALL);

require_once('config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;

Helpers::setDebugParam($isDebugActive);

$access_token = "";
$instance_url = "";

/**
 * @var $client Client
 */
list($client, $calendarUsers) = Helpers::loadClientData($strClientDomainName);

$sfdcAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($sfdcAuths)>0) {
    // if we get salesforce OAuth access data we iterate and use the access data
    // and assign it out global variables declared.
    $integrationDetails = json_decode($sfdcAuths[0]->getData());
    $access_token = $integrationDetails->sfdc_access_token->access_token;
    $instance_url = $integrationDetails->sfdc_access_token->instance_url;
}

/*
* Below you will see system fetching unprocessed attendees from customeer's meeting table
* System will connect to meeting history table and fetch unprocessed attendees from there
*/
$arrGcalUser = Helpers::fnGetProcessAccounts();

if( !(is_array($arrGcalUser) && (count($arrGcalUser)>0)) ) {
    exit;
}

/*
* If there are unprocessed attendees, script will iterate through it and connect to salesforece and fetch the respective
* contact that matches the attendee and than put pulled contact in attendee table and also add an
* entry in attendee history table.
*
* Iteration will only be conducted if there are more than 0 unprocessed attendees fetched from customers airtable base.
*/
foreach($arrGcalUser as $arrUser) {
    $arrUpdatedIds = array();
    $strARecId = $arrUser['id'];


    // Each meeting record will have attendees detail for that meeting, we will access every attendees email
    // for that meeting. There can be multiple attendees for a meeting, we explode and prepare array of attendees
    // so that we can iterate and process each single attendee from the meet and try to fetch its respective
    // contact details from sfdc.
    $arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);

    foreach($arrEmails as $strEm) {
        $domain = substr(strrchr($strEm, "@"), 1);

        // comaparison we only process attendees those are external to client domains
        if(strtolower($domain) == strtolower($strClientDomainName)) {
            continue;
        }

        $strEmailDomain = $domain;
        $strEmail = $strEm;
        $arrAccountDetail = Helpers::fnGetContactDetail($strEm);

        if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0)) {
            // if contact found in airtable than we connect to sf and fetch the latest modified contact from sf
            // check to see if the latest fetched record does have any updated values
            // if they are updated we add a record in the attendee history table
            // and than map it with meeting history table for look up

            // connecting and getting latest modified contact detail from sf
            $arrAccountDetailSF = Helpers::fnGetContactDetailFromSf($instance_url, $access_token, $strEm);

            if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0)) ) {
                continue;
            }

            // we now check if contact details fetched from sfdc, has some updated values or not
            // if yes than we add it into attendee history table otherwise we proceed to next attendee
            // system makes note of the attendee history record if it was created for mapping

            $IsToBeInserted = Helpers::fnCheckIfContactHistoryToBeInserted($arrAccountDetailSF['records']);

            if( !$IsToBeInserted ) {
                $arrUpdatedIds[] = $IsToBeInserted;
            }

            if($IsToBeInserted == "1") {
                if($arrAccountDetail[0]['id']) {
                    $isUpdatedAccountHistory = Helpers::fnInsertContactHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
                    $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                }
            } else {
                $arrUpdatedIds[] = $IsToBeInserted;
            }
        } else {
            // if attendee not present in DB, we connect to sf and get the contact details from sf
            // create a attendee record in the attendee table
            // create a attendee history record in attendee history table and
            // map attendee history record to meeting record
            $arrAccountDetailSF = Helpers::fnGetContactDetailFromSf($instance_url, $access_token,$strEm);

            if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0)) ) {
                continue;
            }

            $arrAccDetail = Helpers::fnGetAccountDetailForAttendees($arrAccountDetailSF['records'][0]['AccountId']);
            $arrUpdatedAccountHistory = Helpers::fnInsertContact($arrAccountDetailSF['records'],$arrAccDetail[0]['id']);

            if( !(is_array($arrUpdatedAccountHistory) && (count($arrUpdatedAccountHistory)>0)) ) {
                continue;
            }

            if($arrUpdatedAccountHistory['id']) {
                $isUpdatedAccountHistory = Helpers::fnInsertContactHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
                $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
            }
        }
    }

    // All the noted attendee record are here mapped with meeting history table
    // if there are none we flag it as "NO SFDC Contact"
    if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0)) {
        $boolUpdateAccount = Helpers::fnUpdateAccountRecord($strARecId,$arrUpdatedIds);
    } else {
        $boolUpdateNoContact = Helpers::fnUpdateNoContact($strARecId);
    }
}

