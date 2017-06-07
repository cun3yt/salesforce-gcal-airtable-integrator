<?
/**
 * Run as a script not webpage
 */
error_reporting(E_ALL);

require_once('config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\AccountQuery as AccountQuery;
use DataModels\DataModels\AccountHistory as AccountHistory;
use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;

$client = Helpers::loadClientData($strClientDomainName);

$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($SFDCAuths) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$sfdcCredentialObject = json_decode($SFDCAuths[0]->getData());
$sfdcToken = $sfdcCredentialObject->tokendata;

$pager = getAccountsNotRecentlySFDCChecked($client);

while(1) {
    if($pager->getNbResults() < 1) {
        echo "No unprocessed pages remain. Finished";
        break;
    }

    $pageNb = $pager->getLastPage();

    echo "Processing... Number of pages remaining: {$pageNb}\n";

    foreach($pager as $account) {
        $emailDomainSegment = Helpers::getEmailDomainSegment($account->getEmailDomain());

        $accountDetailSF = Helpers::getAccountDetailFromSFDC($sfdcToken->instance_url,
            $sfdcToken->access_token,
            $emailDomainSegment);

        if(!isset($accountDetailSF['records'])) {
            $errorCode = $accountDetailSF[0]['errorCode'];
            $errorMsg = $accountDetailSF[0]['message'];

            $msg = "Fatal Error: [Code: {$errorCode}, Message: {$errorMsg} ]";
            trigger_error($msg, E_USER_ERROR);
            die;
        }

        $account->setSFDCLastCheckTime(time());

        if($accountDetailSF['totalSize'] < 1) {
            $account->save();
            continue;
        } else if($accountDetailSF['totalSize'] >= 2) {
            trigger_error(
                "There are more than one matching accounts for this email domain segment: {$emailDomainSegment}, using one of them",
                E_USER_NOTICE
            );
        }

        $sfdcAccount = $accountDetailSF['records'][0];

        $accountHistory = $account->getLatestAccountHistory();

        if( !$accountHistory ) {
            AccountHistory::createAccountHistory($account, $sfdcAccount);
        } else {
            $sfdcHistoryList = Helpers::SFDCGetAccountHistoryLatest($sfdcToken->instance_url, $sfdcToken->access_token,
                $sfdcAccount['Id']);

            if ( $accountHistory->isThereAnyUpdate($sfdcAccount, $sfdcHistoryList) ) {
                AccountHistory::createAccountHistory($account, $sfdcAccount);
            }
        }

        $account->setSfdcAccountId($sfdcAccount['Id'])
            ->save();
    }

    $pager = getAccountsNotRecentlySFDCChecked($client, 1);
}

/**
 * @param Client $client
 * @param int $page
 * @param int $pageSize
 * @return \DataModels\DataModels\Account[]|\Propel\Runtime\Util\PropelModelPager
 */
function getAccountsNotRecentlySFDCChecked(Client $client, int $page = 1, $pageSize = 50) {
    $q = new AccountQuery();

    return $q->filterByClient($client)
        ->where("(sfdc_last_check_time IS NULL) OR (sfdc_last_check_time < CURRENT_TIMESTAMP - INTERVAL '3 DAYS')")
        ->orderById(\Propel\Runtime\ActiveQuery\Criteria::ASC)
        ->paginate($page, $pageSize);
}
