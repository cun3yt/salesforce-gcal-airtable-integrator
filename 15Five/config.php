<?php
/*
* This file is basically responsible setting up client space, configuring client specific parameters.
*/
error_reporting(~E_NOTICE && ~E_DEPRECATED);

require_once($_SERVER['DOCUMENT_ROOT']."/global-config.php");

session_start();

$googleCalAPICredentialFile = $_SERVER['DOCUMENT_ROOT'] . '/config/google_api_credential_15five_domain.json';

$strClient = "15Five"; // This is the name of the client;
$strClientFolderName = '15Five'; // This is the folder name where your customer instance should be, this is case sensitve;

// This is the client domain, it is very important to provide this information, as this information is needed to determine meetings internal or external;
$strClientDomainName = "15Five.com"; 

// this is where you provide your airtable account api key. This is manadatory system won’t work if this is not provided. You need to access airtable account and get this key.
$strAirtableApiKey = "keyOhmYh5N0z83L5F";

// This is the key for your client base in airtable; you will find this info in your airtable account. This is manadatory system won’t work if this is not provided
$strAirtableBase = "appTUmuDLBrSfWRrZ";

// This is where you provide your airtable client base name to the system, so that system put the information at correct place
$strAirtableBaseName = "Meetings";

// This is airtable api end point, this will be found in the airtable account, usually this will not change.
$strAirtableBaseEndpoint = 'https://api.airtable.com/v0/';

// This is about letting the system know for this client about the current personal accounts, which are needed to flag meeting as other.
$arrPersonalDoamin = array("gmail.com","yahoo.com","yahoo.co.in","aol.com","att.net","comcast.net","facebook.com","gmail.com","gmx.com","googlemail.com","google.com","hotmail.com","hotmail.co.uk","mac.com","me.com","mail.com","msn.com","live.com","sbcglobal.net","verizon.net","yahoo.com","yahoo.co.uk","rediif.com");

// This is about letting the system know about some attendee email domain that should be considered as junk and should not be considered for processing.
$arrBannedDomains = array("resource.calendar.google.com");
