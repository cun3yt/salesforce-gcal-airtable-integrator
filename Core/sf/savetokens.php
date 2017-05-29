<?
error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

$clientFolderName = $_SESSION['currentclientfoldername'];

require_once("${_SERVER['DOCUMENT_ROOT']}/${clientFolderName}/config.php");
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;

if( !(is_array($_SESSION['arraccess']) && (count($_SESSION['arraccess'])>0)) ) {
    trigger_error("The SFDC API access-related session variable is not set", E_USER_WARNING);
    die;
}

$tokendData = $_SESSION['arraccess'];
$accessToken = $tokendData['access_token'];
$instanceUrl = $tokendData['instance_url'];

$sales['tokendata'] = $tokendData;
$sales['accesstoken'] = $tokendData['access_token'];
$sales['instanceurl'] = $tokendData['instance_url'];
$userUrl = $tokendData['id'];
$userUrlDetail = explode("/",$userUrl);

$userDetails = Helpers::getUserDetailFromSFDC($userUrl,$sales['accesstoken']);

if($userDetails && isset($userDetails['email'])) {
    $sales['email'] = $userDetails['email'];
}

$sales['userid'] = end($userUrlDetail);

/**
 * @var $client Client
 */
list($client, $contacts) = Helpers::loadClientData($strClientDomainName);

$authentication = Helpers::getOAuthIfPresent($client, $sales['email'],
    ClientCalendarUserOAuth::SFDC);

if($authentication) {
    $hasDBWriteHappened = Helpers::updateAuthenticationToken($authentication, json_encode($sales));
} else {
    $hasDBWriteHappened = Helpers::createAuthAccount(
        $client,
        $sales['email'],
        json_encode($sales),
        ClientCalendarUserOAuth::SFDC);
}

if( !$hasDBWriteHappened ) {
    echo "Something went wrong please try again";
    die;
}

$successfulRedirectUrl = Helpers::generateLink($strClientFolderName.'/index.php');
Helpers::redirect($successfulRedirectUrl);
