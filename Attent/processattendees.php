<?
error_reporting(~E_WARNING && ~E_NOTICE);

require_once('config.php');
require_once('../libraries/Helpers.php');

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;

Helpers::setDebugParam($isDebugActive);

$access_token = "";
$instance_url = "";

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

$sfdcIntegration = Helpers::getIntegrations($customer, CustomerContactIntegration::SFDC);

if(is_array($sfdcIntegration) && (count($sfdcIntegration)>0)) {
    $integrationDetails = json_decode($sfdcIntegration[0]->getData());
    $access_token = $integrationDetails->sfdc_access_token->access_token;
    $instance_url = $integrationDetails->sfdc_access_token->instance_url;
}

$arrGcalUser = Helpers::fnGetProcessAccounts();

if( !(is_array($arrGcalUser) && (count($arrGcalUser)>0)) ) {
    exit;
}

foreach($arrGcalUser as $arrUser) {
    $arrUpdatedIds = array();
    $strARecId = $arrUser['id'];
    $arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);

    foreach($arrEmails as $strEm) {
        $domain = substr(strrchr($strEm, "@"), 1);

        if(strtolower($domain) == strtolower($strClientDomainName)) {
            continue;
        }

        $strEmailDomain = $domain;
        $strEmail = $strEm;
        $arrAccountDetail = Helpers::fnGetContactDetail($strEm);

        if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0)) {
            $arrAccountDetailSF = Helpers::fnGetContactDetailFromSf($instance_url, $access_token, $strEm);

            if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0)) ) {
                continue;
            }

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
            $arrAccountDetailSF = Helpers::fnGetContactDetailFromSf($instance_url, $access_token,$strEm);

            if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0)) ) {
                continue;
            }

            $arrAccDetail = Helpers::fnGetAccountDetail($arrAccountDetailSF['records'][0]['AccountId']);
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

    if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0)) {
        $boolUpdateAccount = Helpers::fnUpdateAccountRecord($strARecId,$arrUpdatedIds);
    } else {
        $boolUpdateNoContact = Helpers::fnUpdateNoContact($strARecId);
    }
}

