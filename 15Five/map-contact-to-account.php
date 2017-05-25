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

/**
 * @var $client Client
 */
list($client, $calendarUsers) = Helpers::loadClientData($strClientDomainName);

$contactQ = new ContactQuery();
$contactPager = $contactQ->where('account_id IS NULL')
    ->paginate($contactPage = 1, $maxPerPage = 50);

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
    $contactPager = $contactQ->where('account_id IS NULL')
        ->paginate($contactPage, $maxPerPage);
}
