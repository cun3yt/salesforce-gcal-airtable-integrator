<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

$strClient = "Techila";
$strClientFolderName = 'Techila';
$strClientDomainName = "techilaservices.com";

$strAirtableApiKey = "keyOhmYh5N0z83L5F";
$strAirtableBase = "app4DEs9dD15SCbKx";
$strAirtableBaseName = "Techila";
$strAirtableBaseEndpoint = 'https://api.airtable.com/v0/';


$arrPersonalDoamin = array("gmail.com","yahoo.com","yahoo.co.in","aol.com","att.net","comcast.net","facebook.com","gmail.com","gmx.com","googlemail.com","google.com","hotmail.com","hotmail.co.uk","mac.com","me.com","mail.com","msn.com","live.com","sbcglobal.net","verizon.net","yahoo.com","yahoo.co.uk","rediif.com");

$arrBannedDomains = array("resource.calendar.google.com");

$strFromEmailAddress = "nirav@15five.com";
$strSmtpHost = "email-smtp.us-west-2.amazonaws.com";
$strSmtpUsername = "AKIAIBPZMF6PMB6XK2OA";
$strSmtpPassword = "At6xulRB6J8VtWqlLWQZ5+NWas6G2GchiYVInzyeD2Xe";
$strSmtpPPort = "587";
?>