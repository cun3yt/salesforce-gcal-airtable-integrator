<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

use DataModels\DataModels\CustomerContact as CustomerContact;
use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;


require_once('config.php');

$client = Helpers::setupGoogleAPIClient($googleCalAPICredentialFile, false);

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

$calendarIntegrations = Helpers::getIntegrations($customer, CustomerContactIntegration::GCAL);

foreach($calendarIntegrations as $integration) {
    /**
     * @var $integration CustomerContactIntegration
     * @var $customerContact CustomerContact
     */
    $customerContact = $integration->getCustomerContact();

    $integrationData = json_decode($integration->getData());

    /**
     * @todo below line must be like this: $client->setAccessToken($integration->getData());
     */
    $client->setAccessToken($integrationData->access_token);
    $isTokenExpired = $client->isAccessTokenExpired();

    if(!$isTokenExpired) {
        echo "Google Calendar Token has not expired for {$customerContact->getEmail()}, passing <br/>";
        continue;
    }

    $refreshToken = $integrationData->refresh_token;

    $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
    $newToken = array_merge((array) $integrationData, $newToken);

    $integration->setStatus(CustomerContactIntegration::STATUS_ACTIVE)
        ->setData(json_encode($newToken))
        ->save();

    echo "Calendar access token is refreshed for {$customerContact->getEmail()} <br />";
}
echo "All done!<br />";
