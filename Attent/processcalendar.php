<?
/**
 * This file is responsible to use calender-email data from the meeting record and
 * change it into more meaning form (like Name) for better understanding.
 *
 * System does not update the calendar-email just uses it and gets the formatted
 * information and puts it other column in meeting history airtable table.
 */
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once('config.php');

require_once('../libraries/Helpers.php');

Helpers::setDebugParam($isDebugActive);

$strClientDomain = $strClientDomainName;
$arrGcalUser = Helpers::fnGetProcessCalendar();

if( !(is_array($arrGcalUser) && (count($arrGcalUser)>0)) ) {
    exit;
}

/**
 * Now all the unprocessed calendar records, we have it in array
 * It will be iterated one by one, respective name will be looked up in People table of airtable
 * On match the name will be fetched from the people airtable base and than
 * Respective airtable record will be updated with name for the calendaremail
 */
foreach($arrGcalUser as $arrUser) {
    $strARecId = $arrUser['id'];
    $strEmail = $arrUser['fields']['calendaremail'];

    $strName = Helpers::fnGetUserName($strEmail);
    $strName = $strName ? $strName : "";

    $isNameUpdated = Helpers::fnUpdateUserName($strName, $strARecId);
    echo $isNameUpdated ? "Updated" : "Not Updated";
}
