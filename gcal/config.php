<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once($_SERVER['DOCUMENT_ROOT']."/global-config.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/libraries/Helpers.php');
$googleCalAPICredentialFile = $_SERVER['DOCUMENT_ROOT'] . '/config/google_api_credential_15five_domain.json';
$arrClient['15Five'] = array('foldername'=>'15Five');
