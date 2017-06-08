<?
require_once('./config.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
require_once('../libraries/Helpers.php');

Helpers::setDebugParam($isDebugActive);

$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(
        array(
            Google_Service_Calendar::CALENDAR,
            "https://www.googleapis.com/auth/contacts.readonly",
            "https://www.googleapis.com/auth/userinfo.profile"
        ));

$guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
$client->setHttpClient($guzzleClient);

$client->setAccessType("offline");
$client->setIncludeGrantedScopes(true);
$client->setRedirectUri(Helpers::generateLink('gcal/getaccess.php'));

$urlToDirect = "";

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $urlToDirect = Helpers::generateLink("gcal/add_new_gcal.php");
} else {
    $client->setApprovalPrompt('force');
    $auth_url = $client->createAuthUrl();
    $urlToDirect = filter_var($auth_url, FILTER_SANITIZE_URL);
}

Helpers::redirect($urlToDirect);
