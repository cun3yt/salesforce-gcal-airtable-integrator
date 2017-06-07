<?
/**
 * Process meetings and fetching accounts and its respective account
 * from SFDC and then map it for easy lookup and reference.
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
$client = Helpers::loadClientData($strClientDomainName);

$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($SFDCAuths) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$sfdcCredentialObject = json_decode($SFDCAuths[0]->getData());
$sfdcToken = $sfdcCredentialObject->tokendata;
unset($sfdcCredentialObject);

unset($SFDCAuths);

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

        $account->checkAgainstSFDC();

        if($account->getSfdcAccountId() == null) {
            $accountDetailSF = Helpers::getAccountDetailFromSFDC($sfdcToken->instance_url,
                                                                $sfdcToken->access_token,
                                                                Helpers::getEmailDomainSegment($account->getEmailDomain()));

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
                    $sfdcToken->instance_url,
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
