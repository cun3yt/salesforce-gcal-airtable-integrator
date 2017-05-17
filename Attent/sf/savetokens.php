<?
error_reporting(~E_WARNING && ~E_NOTICE);
require_once("${_SERVER['DOCUMENT_ROOT']}/Attent/config.php");
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

session_start();

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;

if( !(is_array($_SESSION['arraccess']) && (count($_SESSION['arraccess'])>0)) ) {
    trigger_error("The SFDC API access-related session variable is not set", E_WARNING);
    die;
}

$tokendData = $_SESSION['arraccess'];
$accessToken = $tokendData['access_token'];
$instanceUrl = $tokendData['instance_url'];

$sales['tokendata'] = json_encode($tokendData);
$sales['accesstoken'] = $tokendData['access_token'];
$sales['instanceurl'] = $tokendData['instance_url'];
$userUrl = $tokendData['id'];
$userUrlDetail = explode("/",$userUrl);

$userDetails = Helpers::getUserDetailFromSFDC($userUrl,$sales['accesstoken']);

if($userDetails && isset($userDetails['email'])) {
    $sales['email'] = $userDetails['email'];
}

$sales['userid'] = end($userUrlDetail);

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

$integration = Helpers::getIntegrationIfPresent($customer, $sales['email'],
    CustomerContactIntegration::SFDC);

if($integration) {
    $hasDBWriteHappened = Helpers::updateIntegrationAccountUserToken($integration, $sales);
} else {
    $hasDBWriteHappened = Helpers::createIntegrationAccount($customer, $sales['email'], $sales, CustomerContactIntegration::SFDC);
}

if( !$hasDBWriteHappened ) {
    echo "Something went wrong please try again";
    die;
}

$successfulRedirectUrl = Helpers::generateLink($strClientFolderName.'/loadcals.php');
Helpers::redirect($successfulRedirectUrl);
