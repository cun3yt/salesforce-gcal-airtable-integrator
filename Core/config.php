<?
error_reporting(E_ALL);
ini_set('max_execution_time', 150);
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/generated-conf/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/global-config.php');
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

$googleCalAPICredentialFile = "${_SERVER['DOCUMENT_ROOT']}/config/google_api_credential_15five_domain.json";

