<?
/**
 * This file is responsible for pulling the meetings from the customer google calendar account and putting it under
 * meeting history document (e.g. DB table). There is a crontab for this file
 */
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php');
require_once('config.php');
$strPersonalDomain = implode(",",$arrPersonalDomain);
$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");

$arrGcalUser = Helpers::fnGetGcalUser();

/**
 * system will get the access token from airtable
 * use it to connect to google calendar account
 * if system not able to connect to google calendar due to token expiry, system will send mail to customer for access revoke and mark the token as expired in airtable
 * pull the meeting and insert it into meeting history airtable
 * system will pull all the meetings for the current year starting from the first month of the current year till end of the ongoing month.
 * for every event or meeting pulled from google calendar system will derive the meeting opportunity procesing time, which will meeting date + 3days and add this time in meeting history table
 */

if( !(is_array($arrGcalUser) && (count($arrGcalUser)>0)) ) {
    exit;
}

$intFrCnt = 0;

// going through through the pulled google OAuth access record
foreach($arrGcalUser as $arrUser) {
    $intFrCnt++;
    $arrUserDet = $arrUser['fields'];
    $strEmail = $arrUserDet['user_email'];
    $strARecId = $arrUser['id'];
    $arrTok = json_decode($arrUserDet['user_token'],true);

    if( !(is_array($arrTok) && (count($arrTok)>0)) ) {
        continue;
    }

    $client->setAccessToken($arrTok);
    $service = new Google_Service_Calendar($client);

    try {
        $calendarList = $service->calendarList->listCalendarList();

        if( !(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0)) ) {
            continue;
        }

        $strUserId = "";
        $intUsrCnt = 0;
        $arrUserData = array();
        $intCalNt = 0;

        foreach($calendarList->getItems() as $calendar) {
            if( !($calendar->id) ) {
                continue;
            }

            // filter all other calendar other than client domain
            if(strpos($calendar->id, $strClientDomainName) === false) {
                continue;
            }

            $intCalNt++;

            if($calendar->primary == "1") {
                $strUserId = $calendar->id;
                $arrUserData[json_encode($strTok)] = $calendar->id;
            }

            date_default_timezone_set($calendar->timeZone);
            echo "---".$strDate = date('Y-m-d',strtotime(' -1 day'));
            $strEndDate = date("Y-m-d",strtotime('first day of +1 month'));
            $strNeededStartDate = date("Y")."-"."01"."-"."01";

            // get the latest present meeting date from airtable and fetch all meets from that date ahead
            // for current calendar fetch the latest meet present in airtable so as to set the lower limit for further pulling calendar meets

            $arrMeets = Helpers::fnGetLatestMeetsForUser($calendar->id);

            if(is_array($arrMeets) && (count($arrMeets)>0)) {
                $strMeetingDate = $arrMeets[0]['fields']['Meeting Date'];

                if($strMeetingDate) {
                    $strMeetingFormattedDate = strtotime($strMeetingDate);

                    if($strMeetingFormattedDate <= strtotime($strDate)) {
                        $strDate = date('Y-m-d',$strMeetingFormattedDate);
                    }
                }
                else {
                    $strDate = date('Y-m-d',strtotime($strNeededStartDate));
                }
            }
            else {
                $strDate = date('Y-m-d',strtotime($strNeededStartDate));
            }

            $calendarId = $calendar->id;
            $optParams = array(
              'timeMin' => date('c',strtotime($strDate)),
              'timeMax' => date('c',strtotime($strEndDate)),
              'orderBy' => 'startTime',
              'singleEvents' => TRUE
            );

            $results = $service->events->listEvents($calendarId, $optParams);

            if( !(is_array($results->getItems()) && (count($results->getItems())>0)) ) {
                continue;
            }

            $intFrCnt = 0;
            $calTimeZone = $results->timeZone; //GET THE TZ OF THE CALENDAR
            //SET THE DEFAULT TIMEZONE SO PHP DOESN'T COMPLAIN. SET TO YOUR LOCAL TIME ZONE.
            date_default_timezone_set($calTimeZone);
            $arrResultData = array();

            foreach ($results->getItems() as $event) {
                $strEventId = $calendar->id."_".$event->id;
                $eventDateStr = $event->start->dateTime;

                if(empty($eventDateStr)) { // it's an all day event
                    $eventDateStr = $event->start->date;
                }

                $temp_timezone = $event->start->timeZone;
                //THIS OVERRIDES THE CALENDAR TIMEZONE IF THE EVENT HAS A SPECIAL TZ

                // Set your default timezone in case your events don't have one
                $timezone = empty($temp_timezone) ? new DateTimeZone($calTimeZone) : new DateTimeZone($temp_timezone);

                $eventdate = new DateTime($eventDateStr,$timezone);
                $newmonth = $eventdate->format("M");//CONVERT REGULAR EVENT DATE TO LEGIBLE MONTH
                $newday = $eventdate->format("j");//CONVERT REGULAR EVENT DATE TO LEGIBLE DAY
                $arrEventCreator = (array) $event->creator;
                $arrEventAttendees = array();
                $strInternalDomain = "Internal";
                $arrResultData[$intFrCnt]['eventid'] = $event->id;
                $arrResultData[$intFrCnt]['eventdate'] = $newday;
                $arrResultData[$intFrCnt]['eventmonth'] = $newmonth;
                $arrResultData[$intFrCnt]['eventsummary'] = $event->getSummary();
                $arrResultData[$intFrCnt]['eventdescription'] = $event->description;
                $arrResultData[$intFrCnt]['calendarid'] = $calendar->id;
                $intUId = $strUserId;
                $arrResultData[$intFrCnt]['userid'] = $strUserId;

                $strStardDate = date("Y-m-d H:i:s",strtotime($eventdate->format("Y")."-".$eventdate->format("m")."-".$eventdate->format("d")));
                $arrResultData[$intFrCnt]['startdate'] = $strStardDate;
                if($strStardDate) {
                    $inDForm = date("Y-m-d",strtotime($strStardDate))." 00:00:00";
                    $strStartTime = strtotime($inDForm);
                    $strProcessTime = strtotime("+7 day", $strStartTime);
                    $arrResultData[$intFrCnt]['processtime'] = $strProcessTime;
                }

                if(is_array($arrEventCreator) && (count($arrEventCreator)>0)) {
                    if($arrEventCreator['email']) {
                        $arrResultData[$intFrCnt]['ceatedbyemail'] = $arrEventCreator['email'];
                    }

                    if($arrEventCreator['displayName']) {
                        $arrResultData[$intFrCnt]['creayedbyname'] = $arrEventCreator['displayName'];
                    }
                }

                if(is_array($event->attendees) && (count($event->attendees)>0)) {
                    $strOtherDomain = "";

                    foreach($event->attendees as $arrAttendee) {
                        $domain = substr(strrchr($arrAttendee['email'], "@"), 1);

                        if($domain != $strClientDomainName) {
                            $pos1 = stripos($strPersonalDomain, $domain);

                            if($pos1 === false) {
                                $strInternalDomain = "External";
                                $strOtherDomain .= "0,";
                            } else {
                                $strOtherDomain .= "1,";
                            }
                        }

                        $arrEventAttendees[] = $arrAttendee['email'];
                    }
                    $arrResultData[$intFrCnt]['attendeesemail'] = implode(",",$arrEventAttendees);
                }

                if($strOtherDomain) {
                    $pos2 = stripos($strOtherDomain, "0");
                    if($pos2 === false) {
                        $arrResultData[$intFrCnt]['meetingtype'] = "Other";
                    }
                    else {
                        $arrResultData[$intFrCnt]['meetingtype'] = $strInternalDomain;
                    }
                } else {
                    $arrResultData[$intFrCnt]['meetingtype'] = $strInternalDomain;
                }

                if($arrResultData[$intFrCnt]['eventsummary']) {
                    if(is_array($arrEventAttendees) && (count($arrEventAttendees)>0)) {
                        $isAttPresent = Helpers::fnCheckMeetingAlreadyPresent($arrResultData[$intFrCnt]);

                        if(!$isAttPresent) {
                            $isAttendeesSaved = Helpers::fnSaveAirtableMeetings($arrResultData[$intFrCnt]);
                        }

                        $intFrCnt++;
                    }
                }
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
        $arrMessageDetails = json_decode($e->getMessage(),true);

        $updateFlag = is_array($arrMessageDetails) &&
            (count($arrMessageDetails)>0) && ($arrMessageDetails['error']['message'] == "Invalid Credentials");

        if($updateFlag) {
            Helpers::fnUpdatesGstatus($strEmail);
            Helpers::fnSendAccountExpirationMail($strEmail);
        }
    }
}
