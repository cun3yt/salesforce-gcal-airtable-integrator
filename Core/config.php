<?
error_reporting(E_ALL);
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/generated-conf/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/global-config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

$googleCalAPICredentialFile = "${_SERVER['DOCUMENT_ROOT']}/config/google_api_credential_15five_domain.json";


/**
 * Below needs to be moved to somewhere per-client level.
 */
//
//$strClient = "Attent";
//$strClientFolderName = 'Attent';
//$strClientDomainName = "attent.ai";

//$_SESSION['currentclientfoldername'] = $strClientFolderName;
