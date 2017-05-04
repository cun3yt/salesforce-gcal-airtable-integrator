<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT']."/global-config.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/libraries/Helpers.php');
session_start();
$arrClient['15Five'] = array('foldername'=>'15Five');
