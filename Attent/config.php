<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
$strClient = "Attent";
$strClientFolderName = 'Attent';
$strClientDomainName = "attent.ai";
$strAirtableApiKey = "keyx8INc59tYr3T01";
$strAirtableBase = "apppLD0hgRCJ4d8dk";
$strAirtableBaseName = "Test_Attent_Internal";
$strAirtableBaseEndpoint = 'https://api.airtable.com/v0/';
$arrPersonalDoamin = array("gmail.com", "yahoo.com", "yahoo.co.in", "aol.com", "att.net", "comcast.net", "facebook.com", "gmail.com", "gmx.com", "googlemail.com", "google.com", "hotmail.com", "hotmail.co.uk", "mac.com", "me.com", "mail.com", "msn.com", "live.com", "sbcglobal.net", "verizon.net", "yahoo.com", "yahoo.co.uk", "rediif.com");
$arrBannedDomains = array("resource.calendar.google.com");
?>