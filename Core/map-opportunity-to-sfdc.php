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
use DataModels\DataModels\AccountQuery as AccountQuery;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Opportunity as Opportunity;
use DataModels\DataModels\OpportunityHistory as OpportunityHistory;
use Propel\Runtime\ActiveQuery\Criteria as Criteria;


$client = Helpers::loadClientData($strClientDomainName);

$sfdcAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($sfdcAuths) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$sfdcCredentialObject = json_decode($sfdcAuths[0]->getData());
$sfdcToken = $sfdcCredentialObject->tokendata;
$access_token = $sfdcToken->access_token;
$instance_url = $sfdcToken->instance_url;

$pager = getAccountsWithOpptyNotRecentlySFDCChecked($client);

$pageNb = $pager->getLastPage();

while(1) {
    if($pager->getNbResults() < 1) {
        echo "No unprocessed pages remain. Finished";
        break;
    }

    $pageNb = $pager->getLastPage();

    echo "Processing... Number of pages remaining: {$pageNb}\n";

    foreach($pager as $account) {
        $opptyDetailSFDC = Helpers::getOpptyDetailFromSFDC(
            $sfdcToken->instance_url,
            $sfdcToken->access_token,
            $account->getSfdcAccountId()
        );

        if(!isset($opptyDetailSFDC['records'])) {
            $errorCode = $opptyDetailSFDC[0]['errorCode'];
            $errorMsg = $opptyDetailSFDC[0]['message'];

            $msg = "Fatal Error: [Code: {$errorCode}, Message: {$errorMsg} ]";
            trigger_error($msg, E_USER_ERROR);
            die;
        }

        $account->setSFDCOpptyLastCheckTime(time());

        if($opptyDetailSFDC['totalSize'] < 1) {
            $account->save();
            continue;
        }

        $sfdcOppty = $opptyDetailSFDC['records'][0];

        $oppty = Opportunity::findByAccountAndSFDCId($account, $sfdcOppty['Id']);

        if( !$oppty ) {
            $oppty = Opportunity::createOpportunity($account, $sfdcOppty);
        }

        $opptyHistory = $oppty->getLatestOpportunityHistory();

        if( !$opptyHistory ) {
            OpportunityHistory::createOpportunityHistory($oppty, $sfdcOppty);
        } else {
            $sfdcHistoryList = Helpers::SFDCGetOpptyHistoryLatest($sfcdToken->instance_url, $sfdcToken->access_token,
                $oppty->getSFDCOpportunityId());

            if( $opptyHistory->isThereAnyUpdate($sfdcOppty, $sfdcHistoryList) ) {
                OpportunityHistory::createOpportunityHistory($oppty, $sfdcOppty);
            }
        }

        $account->save();
    }

    $pager = getAccountsNotRecentlySFDCChecked($client, 1);
}

/**
 * @param Client $client
 * @param int $page
 * @param int $pageSize
 * @return \DataModels\DataModels\Account[]|\Propel\Runtime\Util\PropelModelPager
 */
function getAccountsWithOpptyNotRecentlySFDCChecked(Client $client, int $page = 1, $pageSize = 50) {
    $q = new AccountQuery();

    return $q->filterByClient($client)
        ->filterBySfdcAccountId(NULL, Criteria::NOT_EQUAL)
        ->where("(sfdc_oppty_last_check_time IS NULL) OR (sfdc_oppty_last_check_time < CURRENT_TIMESTAMP - INTERVAL '3 DAYS')")
        ->paginate($page, $pageSize);
}




die;

// vvvv DANGER! Techila Code Below vvvv


foreach ($arrGcalUser as $arrUser) {
    $arrUpdatedIds = array();
    $arrProcessIds = array();
    $intAccCnts = 0;
    $strARecId = $arrUser['id'];
    $arrAccountDetail = $arrUser['fields']['acoount_id'];

    foreach ($arrAccountDetail as $arrAccount) {
        $intAccCnts++;
        $arrAccDetail = Helpers::fnGetAccountDetail($arrAccount);
        $arrOpportunityDetail = Helpers::fnGetOpportunityDetail($arrAccDetail['fields']['Account']);
        if (is_array($arrOpportunityDetail) && (count($arrOpportunityDetail) > 0)) {
            $arrAccountDetailSF = Helpers::getOpptyDetailFromSFDC($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
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

                Helpers::fnUpdateAccountRecordForOppties($arrOpportunityDetail[0]['id'], $arrOppHIds);
            }

            continue;
        }

        $arrAccountDetailSF = Helpers::getOpptyDetailFromSFDC($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);

        if( !(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) ) {
            $arrProcessIds[] = $strARecId;
            continue;
        }

        $arrUpdatedAccountHistory = Helpers::fnInsertOpportunity($arrAccountDetailSF['records'], $arrAccDetail['id']);
        $isUpdatedAccountHistory = Helpers::fnInsertOppHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
        $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
        Helpers::fnUpdateAccountRecordForOppties($arrUpdatedAccountHistory['id'], array($isUpdatedAccountHistory['id']));
    }

    if(!is_array($arrProcessIds)) {
        continue;
    }

    if (count($arrProcessIds) == $intAccCnts) {
        Helpers::fnUpdateOppProcessedRecord($strARecId);
    } elseif(count($arrUpdatedIds) > 0) {
        $arrUpdatedIds = array_unique($arrUpdatedIds);
        Helpers::fnUpdateMeetingRecord($strARecId, $arrUpdatedIds);
    }
}
