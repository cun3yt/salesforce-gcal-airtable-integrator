<?
/**
 * map attendee (contact) to SFDC
 *
 * @todo add SFDC connection validation, i.e. Via OAuth User we can still access to SFDC
 */
error_reporting(E_ALL);

require_once('config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Contact as Contact;
use DataModels\DataModels\ContactQuery as ContactQuery;

$client = Helpers::loadClientData($strClientDomainName);

$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if(count($SFDCAuths) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$sfdcCredentialObject = json_decode($SFDCAuths[0]->getData());
$sfdcToken = $sfdcCredentialObject->tokendata;

$contactPager = getContacts($client, $contactPage = 1);

$contactPageNb = $contactPager->getLastPage();

while($contactPage <= $contactPageNb) {
    echo "Processing Contacts: page {$contactPage} of {$contactPageNb}<br/>";

    foreach($contactPager as $contact) {
        $sfdcContact = Helpers::fnGetContactDetailFromSf(
            $sfdcToken->instance_url,
            $sfdcToken->access_token,
            $contact->getEmail(),
            false
        );

        if(!isset($sfdcContact['records'][0])) {
            $contact->setSFDCLastCheckTime(time())
                ->save();
            continue;
        }

        $contact->setSfdcContactId($sfdcContact['records'][0]['Id'])
            ->setSfdcAccountId($sfdcContact['records'][0]['AccountId'])
            ->setSfdcContactName($sfdcContact['records'][0]['Name'])
            ->setSfdcTitle($sfdcContact['records'][0]['Title'])
            ->setSFDCLastCheckTime(time())
            ->save();
    }

    ++$contactPage;
    $contactPager = getContacts($client, $contactPage);
}

/**
 * @param Client $client
 * @param int $page
 * @param int $maxPerPage
 * @return Contact[]|\Propel\Runtime\Util\PropelModelPager
 */
function getContacts(Client $client, $page, $maxPerPage = 50) {
    $contactQ = new ContactQuery();

    $contactPager = $contactQ->filterByClient($client)
        ->where("(sfdc_last_check_time IS NULL) OR (sfdc_last_check_time > CURRENT_TIMESTAMP - INTERVAL '3 DAYS')")
        ->paginate($page, $maxPerPage);

    return $contactPager;
}
