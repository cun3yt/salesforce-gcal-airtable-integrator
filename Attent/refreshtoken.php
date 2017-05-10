<?
use DataModels\DataModels\CustomerContact as CustomerContact;
use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
require_once 'config.php';
$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false ) ));
$client->setHttpClient($guzzleClient);

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

$calendarIntegrations = Helpers::getIntegrations($customer, CustomerContactIntegration::GCAL);

foreach($calendarIntegrations as $integration) {
    /**
     * @var $customerContact CustomerContact
     */
    $customerContact = $integration->getCustomerContact();

    $integrationData = json_decode($integration->getData());

    $client->setAccessToken($integrationData->access_token);
    $isTokenExpired = $client->isAccessTokenExpired();

    if(!$isTokenExpired) {
        continue;
    }

    $refreshToken = $integrationData->refresh_token;

    $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
    $newToken = array_merge((array) $integrationData, $newToken);

    $integration->setStatus(CustomerContactIntegration::STATUS_ACTIVE)
        ->setData(json_encode($newToken))
        ->save();
}
