<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once('config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;

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

// @todo The rest will be here...

die;

// vvvv DANGER! Techila Code Below vvvv


$arrGcalUser = Helpers::fnGetProcessAccountsForOppties();

foreach ($arrGcalUser as $arrUser) {
    $arrUpdatedIds = array();
    $arrProcessIds = array();
    $intAccCnts = 0;
    $strARecId = $arrUser['id'];
    $arrAccountDetail = $arrUser['fields']['acoount_id'];

    if( !(is_array($arrAccountDetail) && (count($arrAccountDetail) > 0)) ) {
        continue;
    }

    foreach ($arrAccountDetail as $arrAccount) {
        if (!$arrAccount) {
            continue;
        }

        $intAccCnts++;
        $arrAccDetail = Helpers::fnGetAccountDetail($arrAccount);
        $arrOpportunityDetail = Helpers::fnGetOpportunityDetail($arrAccDetail['fields']['Account']);
        if (is_array($arrOpportunityDetail) && (count($arrOpportunityDetail) > 0)) {
            $arrAccountDetailSF = Helpers::fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
            if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                $arrOppHIds = $arrOpportunityDetail[0]['fields']['Opportunity History'];
                $IsToBeInserted = Helpers::fnCheckIfOppHistoryToBeInserted($arrAccountDetailSF['records']);


                if ($IsToBeInserted && $IsToBeInserted == "1") {
                    $isUpdatedAccountHistory = Helpers::fnInsertOppHistory($arrAccountDetailSF['records'], $$arrOpportunityDetail[0]['id']);
                    if ($isUpdatedAccountHistory['id']) {
                        $arrOppHIds[] = $isUpdatedAccountHistory['id'];
                        $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                    }
                } else if($IsToBeInserted) {
                    $arrOppHIds[] = $IsToBeInserted;
                    $arrUpdatedIds[] = $IsToBeInserted;
                } else {
                    $arrOppHIds[] = $IsToBeInserted;
                    $arrUpdatedIds[] = $IsToBeInserted;
                }

                $boolUpdateAccount = Helpers::fnUpdateAccountRecordForOppties($arrOpportunityDetail[0]['id'], $arrOppHIds);
            }

            continue;
        }

        $arrAccountDetailSF = Helpers::fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);

        if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) ) {
            $arrProcessIds[] = $strARecId;
            continue;
        }

        $arrUpdatedAccountHistory = Helpers::fnInsertOpportunity($arrAccountDetailSF['records'], $arrAccDetail['id']);
        $isUpdatedAccountHistory = Helpers::fnInsertOppHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
        $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
        $boolUpdateAccount = Helpers::fnUpdateAccountRecordForOppties($arrUpdatedAccountHistory['id'], array($isUpdatedAccountHistory['id']));
    }

    if(!is_array($arrProcessIds)) {
        continue;
    }

    if (count($arrProcessIds) == $intAccCnts) {
        $boolUpdateAccount = Helpers::fnUpdateOppProcessedRecord($strARecId);
    } elseif(count($arrUpdatedIds) > 0) {
        $arrUpdatedIds = array_unique($arrUpdatedIds);
        $boolUpdateAccount = Helpers::fnUpdateMeetingRecord($strARecId, $arrUpdatedIds);
    }
}
