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

$contactPager = getContacts($client, 1);

while(1) {
    if($contactPager->getNbResults() < 1) {
        echo "No unprocessed pages remain. Finished";
        break;
    }

    $pageNb = $contactPager->getLastPage();

    echo "Processing... Number of pages remaining: {$pageNb}\n";

    foreach($contactPager as $contact) {
        $sfdcContact = Helpers::getContactDetailsFromSFDC(
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

    $contactPager = getContacts($client, 1);
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
        ->where("((sfdc_last_check_time IS NULL) OR (sfdc_last_check_time < CURRENT_TIMESTAMP - INTERVAL '3 DAYS'))")
        ->paginate($page, $maxPerPage);

    return $contactPager;
}
