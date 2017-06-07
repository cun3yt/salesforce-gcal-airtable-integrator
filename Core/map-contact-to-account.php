<?
/**
 * This script needs to run after meeting (and contact) data is populated.
 */
error_reporting(E_ALL);

require_once 'config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\AccountQuery as AccountQuery;
use DataModels\DataModels\Account as Account;
use DataModels\DataModels\ContactQuery as ContactQuery;
use DataModels\DataModels\Client as Client;

$client = Helpers::loadClientData($strClientDomainName);

$contactQ = new ContactQuery();

$contactPager = getContacts($client, $contactPage = 1);

$contactPagesNb = $contactPager->getLastPage();

while($contactPage <= $contactPagesNb) {

    foreach($contactPager as $contact) {
        $accountQ = new AccountQuery();
        $emailDomain = Helpers::getEmailDomain($contact->getEmail());
        $account = $accountQ->filterByEmailDomain($emailDomain)->filterByClient($client)->findOne();

        if(!$account) {
            $account = new Account();
            $account->setEmailDomain($emailDomain)
                ->setClient($client);
        }

        $contact->setAccount($account)
            ->save();
    }

    ++$contactPage;
    $contactPager = getContacts($client, $contactPage);
}

/**
 * @param Client $client
 * @param int $page
 * @param int $maxPerPage
 * @return \DataModels\DataModels\Contact[]|\Propel\Runtime\Util\PropelModelPager
 */
function getContacts(Client $client, $page, $maxPerPage = 50) {
    $contactQ = new ContactQuery();

    $contactPager = $contactQ->filterByClient($client)
        ->where('account_id IS NULL')
        ->paginate($page, $maxPerPage);

    return $contactPager;
}
