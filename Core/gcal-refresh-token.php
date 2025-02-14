<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

use DataModels\DataModels\ClientCalendarUser as ClientCalendarUser;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;

require_once('config.php');

$apiClient = Helpers::setupGoogleAPIClient($googleCalAPICredentialFile, false);

$client = Helpers::loadClientData($strClientDomainName);

$calendarAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::GCAL);

foreach($calendarAuths as $auth) {
    /**
     * @var $clientCalendarUser ClientCalendarUser
     */
    $clientCalendarUser = $auth->getClientCalendarUser();
    $authData = (array) json_decode($auth->getData());

    $apiClient->setAccessToken($authData);
    $isTokenExpired = $apiClient->isAccessTokenExpired();

    if(!$isTokenExpired) {
        echo "Google Calendar Token has not expired for {$clientCalendarUser->getEmail()}, passing <br/>";
        continue;
    }

    $refreshToken = $authData['refresh_token'];

    $newToken = $apiClient->fetchAccessTokenWithRefreshToken($refreshToken);
    $newToken = array_merge($authData, $newToken);

    $auth->setStatus(ClientCalendarUserOAuth::STATUS_ACTIVE)
        ->setData(json_encode($newToken))
        ->save();

    echo "Calendar access token is refreshed for {$clientCalendarUser->getEmail()} <br />";
}
echo "All done!<br />";
