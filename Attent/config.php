<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/generated-conf/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/global-config.php');
require_once('../libraries/Helpers.php');
session_start();

$googleCalAPICredentialFile = $_SERVER['DOCUMENT_ROOT'] . '/config/google_api_credential_15five_domain.json';
$strClient = "Attent";
$strClientFolderName = 'Attent';
$strClientDomainName = "attent.ai";
$strAirtableApiKey = "keyx8INc59tYr3T01";
$strAirtableBase = "apppLD0hgRCJ4d8dk";
$strAirtableBaseName = "Test_Attent_Internal";
$strAirtableBaseEndpoint = 'https://api.airtable.com/v0/';

$_SESSION['currentclientfoldername'] = $strClientFolderName;
