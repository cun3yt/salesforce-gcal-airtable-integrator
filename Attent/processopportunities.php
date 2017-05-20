<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once('config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

$access_token = "";
$instance_url = "";
$strRecordId = "";
$arrSalesUser = Helpers::fnGetSalesUser();
if (is_array($arrSalesUser) && (count($arrSalesUser) > 0)) {
    $arrSalesTokenDetail = $arrSalesUser[0]['fields'];
    if (is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail) > 0)) {
        $arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'], true);
        $access_token = $arrSDetail['access_token'];
        $instance_url = $arrSDetail['instance_url'];
        $strRecordId = $arrSalesUser[0]['id'];
    }
}
$strClientDomain = $strClientDomainName;
$arrGcalUser = Helpers::fnGetProcessAccountsForOppties();

foreach ($arrGcalUser as $arrUser) {
    $arrUpdatedIds = array();
    $arrProcessIds = array();
    $intAccCnts = 0;
    $strARecId = $arrUser['id'];
    $arrEmails = explode(",", $arrUser['fields']['Attendee Email(s)']);
    $strEmail = $strEm;
    $arrAccountDetailold = $arrUser['fields']['Account'];
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
                echo "---" . $IsToBeInserted = Helpers::fnCheckIfOppHistoryToBeInserted($arrAccountDetailSF['records']);
                if ($IsToBeInserted) {
                    if ($IsToBeInserted == "1") {
                        $isUpdatedAccountHistory = Helpers::fnInsertOppHistory($arrAccountDetailSF['records'], $$arrOpportunityDetail[0]['id']);
                        if ($isUpdatedAccountHistory['id']) {
                            $arrOppHIds[] = $isUpdatedAccountHistory['id'];
                            $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                        }
                    } else {
                        $arrOppHIds[] = $IsToBeInserted;
                        $arrUpdatedIds[] = $IsToBeInserted;
                    }
                } else {
                    $arrOppHIds[] = $IsToBeInserted;
                    $arrUpdatedIds[] = $IsToBeInserted;
                }
                $boolUpdateAccount = Helpers::fnUpdateAccountRecordForOppties($arrOpportunityDetail[0]['id'], $arrOppHIds);
            }
        } else {
            $arrAccountDetailSF = Helpers::fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
            if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                $arrUpdatedAccountHistory = Helpers::fnInsertOpportunity($arrAccountDetailSF['records'], $arrAccDetail['id']);
                $isUpdatedAccountHistory = Helpers::fnInsertOppHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
                $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                $boolUpdateAccount = Helpers::fnUpdateAccountRecordForOppties($arrUpdatedAccountHistory['id'], array($isUpdatedAccountHistory['id']));
            } else {
                $arrProcessIds[] = $strARecId;
            }
        }
    }

    if (is_array($arrProcessIds) && (count($arrProcessIds) == $intAccCnts)) {
        $boolUpdateAccount = Helpers::fnUpdateOppProcessedRecord($strARecId);
    } else if(is_array($arrUpdatedIds) && (count($arrUpdatedIds) > 0)) {
        $arrUpdatedIds = array_unique($arrUpdatedIds);
        $boolUpdateAccount = Helpers::fnUpdateMeetingRecord($strARecId, $arrUpdatedIds);
    }
}
