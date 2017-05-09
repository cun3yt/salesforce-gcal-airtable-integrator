<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
require_once('./config.php');
require_once('../libraries/Helpers.php');

Helpers::setDebugParam($isDebugActive);

$strCurrentClientFolderName = $_SESSION['currentclientfoldername'];
$strToken = "";
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(
    array(
        Google_Service_Calendar::CALENDAR,
        "https://www.googleapis.com/auth/contacts.readonly",
        "https://www.googleapis.com/auth/userinfo.profile"
    )
);
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false ) ));
$client->setHttpClient($guzzleClient);
$client->setIncludeGrantedScopes(true);

$strTok = $strToken ? $strToken : $_SESSION['access_token'];

if( !(is_array($strTok) && (count($strTok)>0)) ) {
    Helpers::redirect(Helpers::generateLink("gcal/getaccess.php"));
}

$client->setAccessToken($strTok);
$service = new Google_Service_Calendar($client);
$calendarList = $service->calendarList->listCalendarList();
if (is_array($calendarList->getItems()) && (count($calendarList->getItems()) > 0)) {
    $strUserId = "";
    $intUsrCnt = 0;
    $arrUserData = array();
    foreach ($calendarList->getItems() as $calendar) {
        if (!($calendar->id)) {
            continue;
        }
        if ($calendar->primary == "1") {
            $strUserId = $calendar->id;
            $arrUserData[json_encode($strTok)] = $calendar->id;
        }
        $calendarId = $calendar->id;
        $_SESSION['userdata'] = $arrUserData;
    }
}
unset($_SESSION['access_token']);
unset($_SESSION['userid']);

Helpers::redirect(Helpers::generateLink("{$strCurrentClientFolderName}/loadcals.php"));
