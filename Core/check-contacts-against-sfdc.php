<?
/**
 * This file is responsible for processing meetings and fetching accounts and its respective account
 * from SFDC and than mapping it within DB for easy lookup and reference.
 *
 * @todo delete processcalendar.php entry on crontab
 */
error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once 'config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\AccountQuery as AccountQuery;
use DataModels\DataModels\ContactQuery as ContactQuery;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;

/**
 * @var $client Client
 */
list($client, $calendarUsers) = Helpers::loadClientData($strClientDomainName);
$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($SFDCAuths) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$sfdcCredentialObject = json_decode($SFDCAuths[0]->getData());
$sfdcToken = $sfdcCredentialObject->tokendata;

unset($SFDCAuths);
unset($sfdcCredentialObject);

/**
 * 1. Fetch all accounts belonging to the customer
 * 2. For each account fetch all contacts
 */
$accountQ = new AccountQuery();
$accountPager = $accountQ->filterByClient($client)->paginate($page = 1, $maxPerPage = 50);
$accountPagesNb = $accountPager->getLastPage();

while($page <= $accountPagesNb) {
    echo "Processing Accounts, page {$page} of {$accountPagesNb}<br/>";

    foreach($accountPager as $account) {

        if($account->getSfdcAccountId() == null) {
            $accountDetailSF = Helpers::fnGetAccountDetailFromSf($sfdcCredentialObject->instance_url,
                                                                $sfdcToken->access_token,
                                                                $account->getEmailDomain());

            if( isset($accountDetailSF['records'][0]['id']) ) {
                $account->setSfdcAccountId($accountDetailSF['records'][0]['id']);
                $account->save();
            }

            /**
             * @todo Account history will be needed to be entered to DB.
             */
        }

        $contactQ = new ContactQuery();
        $contactPager = $contactQ->filterByAccount($account)
                            ->where('sfdc_contact_id IS NULL')
                            ->paginate($contactPage = 1, $maxPerPage);

        $contactPagesNb = $contactPager->getLastPage();

        while($contactPage <= $contactPagesNb) {

            echo " Processing Contacts, page {$contactPage} of {$contactPagesNb}<br/>";

            foreach($contactPager as $contact) {
                $sfdcContact = Helpers::fnGetContactDetailFromSf(
                    $sfdcCredentialObject->instance_url,
                    $sfdcToken->access_token,
                    $contact->getEmail(), false);

                if( !isset($sfdcContact['records'][0]) ) {
                    continue;
                }

                $contact->setSfdcContactId($sfdcContact['records'][0]['AccountId']);
                $contact->save();
            }

            ++$contactPage;
            $contactPager = $contactQ->filterByAccount($account)
                ->where('sfdc_contact_id IS NULL')
                ->paginate($contactPage, $maxPerPage);
        }

    }

    ++$page;
    $accountPager = $accountQ->filterByClient($client)->paginate($page, $maxPerPage);
}



die;
// vvvv Techila Code! vvvv

$user = array();

/**
 * foreach meeting record, system will get hold of attendee email, extract the doamin part from the email
 * address check to see if account with that domain present in airtable accounte table
 * if yes than connect sf to get the latest modified account detail from sf.
 * check if the the there is update in the account info pulled from sf, if yes than make
 * an account detail entry in account history table and return back the created history record id
 * for mapping to meeting history table if no update in the account details than get
 * the exiting account history record id and map it with meeting history record.
 * If account detail not present in account airtable than use domain part to pull
 * the latest modified account and its detail, create account record from the pulled info,
 * create a account history record from the pulled account detail info and use the account history
 * record id to map it with meeting history record id.
 * In case where account details are not found in sf through domain than we find the contact in sf
 * with help of attendee email and get account info from the contact detail and process further
 * as described above once we get hold of account.
 */
$updatedIds = array();
$accDomains = array();
$processIds = array();
$ids = array();
$externalNameEmails = 0;
$aRecId = $user['id'];
$user['fields']['accountno'];
$emails = explode(",", $user['fields']['Attendee Email(s)']);

foreach ($emails as $email) {
    $domain = substr(strrchr($email, "@"), 1);

    if( !(strtolower($domain) != strtolower($client->getEmailDomain())) ){
        continue;
    }

    if( in_array(strtolower($domain), Helpers::getBannedDomains()) ){
        continue;
    }

    $externalNameEmails++;
    $domainInfo = explode(".", $domain);
    $emailDomain = $domainInfo[0];  // getting the domain excluding .com
    $accountDetail = Helpers::fnGetAccountDetailByAccountDomain($emailDomain);

    /**
     * If account exists in airtable
     */
    if (is_array($accountDetail) && (count($accountDetail) > 0)) {
        $accountDetailSF = Helpers::fnGetAccountDetailFromSf($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $emailDomain);

        if (is_array($accountDetailSF['records']) && (count($accountDetailSF['records']) > 0)) {
            if (in_array($accountDetailSF['records'][0]['Name'], $accDomains)) {
                continue;
            }

            $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($accountDetailSF['records']);

            if ($IsToBeInserted) {
                if ($IsToBeInserted != "1") {
                    $updatedIds[] = $IsToBeInserted;
                    $accDomains[] = $accountDetailSF['records'][0]['Name'];
                    continue;
                }

                $isUpdatedAccountHistory = Helpers::fnInsertAccountHistory($accountDetailSF['records'], $accountDetail[0]['id']);
                $updatedIds[] = $isUpdatedAccountHistory['id'];
                $accDomains[] = $accountDetailSF['records'][0]['Name'];

                continue;
            }

            $updatedIds[] = $IsToBeInserted;
            $accDomains[] = $accountDetailSF['records'][0]['Name'];

            continue;
        }

        $accountDetailN = Helpers::fnGetContactDetailFromSf($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $email, false);

        if( !(is_array($accountDetailN) && (count($accountDetailN) > 0)) ) {
            $processIds[] = $aRecId;
            continue;
        }

        $accountDetailSFId = Helpers::fnGetAccountDetailFromSfId($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $accountDetailN['records'][0]['AccountId']);

        if( !(is_array($accountDetailSFId['records']) && (count($accountDetailSFId['records']) > 0)) ) {
            $processIds[] = $aRecId;
            continue;
        }

        if (in_array($accountDetailSFId['records'][0]['Name'], $accDomains)) {
            continue;
        }

        $accDomains[] = $accountDetailSFId['records'][0]['Name'];
        $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($accountDetailSFId['records']);

        if( !$IsToBeInserted ) {
            $updatedIds[] = $IsToBeInserted;
            $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
            $accDomains[] = $accountDetailSFId['records'][0]['Name'];

            continue;
        }

        if ($IsToBeInserted != "1") {
            $updatedIds[] = $IsToBeInserted;
            $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
            $accDomains[] = $accountDetailSFId['records'][0]['Name'];
            continue;
        }

        $isUpdatedAccountHistoryId = Helpers::fnInsertAccountHistory($accountDetailSFId['records'], $updatedAccountHistoryId['id']);
        $updatedIds[] = $isUpdatedAccountHistoryId['id'];
        $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
        $accDomains[] = $accountDetailSFId['records'][0]['Name'];

        continue;
    }

    /**
     * If there is no account detail in airtable fetch from SFDC
     */
    $accountDetailSF = Helpers::fnGetAccountDetailFromSf($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $emailDomain);

    if (is_array($accountDetailSF['records']) && (count($accountDetailSF['records']) > 0)) {
        $arrUpdatedAccountHistory = Helpers::fnInsertAccount($accountDetailSF['records'], $emailDomain);
        $isUpdatedAccountHistory = Helpers::fnInsertAccountHistory($accountDetailSF['records'], $arrUpdatedAccountHistory['id']);
        $updatedIds[] = $isUpdatedAccountHistory['id'];
        $ids[] = $arrUpdatedAccountHistory['fields']['AccountNumber'];
        $accDomains[] = $accountDetailSF['records'][0]['Name'];
        continue;
    }

    $accountDetailN = Helpers::fnGetContactDetailFromSf($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $email, false);

    if( !(is_array($accountDetailN) && (count($accountDetailN) > 0)) ) {
        $processIds[] = $aRecId;
        continue;
    }

    $accountDetailSFId = Helpers::fnGetAccountDetailFromSfId($sfdcCredentialObject->instance_url, $sfdcToken->access_token, $accountDetailN['records'][0]['AccountId']);

    if( !(is_array($accountDetailSFId['records']) && (count($accountDetailSFId['records']) > 0)) ) {
        $processIds[] = $aRecId;
        continue;
    }

    if (in_array($accountDetailSFId['records'][0]['Name'], $accDomains)) {
        continue;
    }

    $arrAccountByNameDetail = Helpers::fnGetAccountDetailByName($accountDetailSFId['records'][0]['Name']);

    if (is_array($arrAccountByNameDetail) && (count($arrAccountByNameDetail) > 0)) {
        continue;
    }

    $updatedAccountHistoryId = Helpers::fnInsertAccount($accountDetailSFId['records'], $emailDomain);
    $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($accountDetailSFId['records']);

    if( !$IsToBeInserted ) {
        $updatedIds[] = $IsToBeInserted;
        $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
        $accDomains[] = $accountDetailSFId['records'][0]['Name'];
        continue;
    }

    if ($IsToBeInserted != "1") {
        $updatedIds[] = $IsToBeInserted;
        $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
        $accDomains[] = $accountDetailSFId['records'][0]['Name'];
        continue;
    }

    $isUpdatedAccountHistoryId = Helpers::fnInsertAccountHistory($accountDetailSFId['records'], $updatedAccountHistoryId['id']);
    $updatedIds[] = $isUpdatedAccountHistoryId['id'];
    $ids[] = $updatedAccountHistoryId['fields']['AccountNumber'];
    $accDomains[] = $accountDetailSFId['records'][0]['Name'];
}

if (is_array($processIds) && (count($processIds) == $externalNameEmails)) {
    $boolUpdateAccount = Helpers::fnUpdateAccountProcessedRecord($aRecId);
} else if (is_array($updatedIds) && (count($updatedIds) > 0)) {
    $boolUpdateAccount = Helpers::fnUpdateAccountRecordForSFDC($aRecId, $updatedIds, $ids);
}
