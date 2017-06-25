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

        if($opptyDetailSFDC['totalSize'] < 1) {
            $account
                ->setSFDCOpptyLastCheckTime(time())
                ->save();
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
            $sfdcHistoryList = Helpers::SFDCGetOpptyHistoryLatest($sfdcToken->instance_url, $sfdcToken->access_token,
                $oppty->getSFDCId());

            if( $opptyHistory->isThereAnyUpdate($sfdcOppty, $sfdcHistoryList) ) {
                OpportunityHistory::createOpportunityHistory($oppty, $sfdcOppty);
            }
        }

        $account
            ->setSFDCOpptyLastCheckTime(time())
            ->save();

        // @todo Meeting Oppty Mapping will be here! Techila's function for this purpose: fnUpdateMeetingRecord
    }

    $pager = getAccountsWithOpptyNotRecentlySFDCChecked($client, 1);
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
        ->where("((sfdc_oppty_last_check_time IS NULL) OR (sfdc_oppty_last_check_time < CURRENT_TIMESTAMP - INTERVAL '3 DAYS'))")
        ->orderById(Criteria::ASC)
        ->paginate($page, $pageSize);
}
