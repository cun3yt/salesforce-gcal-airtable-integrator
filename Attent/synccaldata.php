<?
/**
 * This file is responsible for pulling the meetings from the customer google calendar account and putting it under
 * meeting history document (e.g. DB table). There is a crontab for this file.
 *
 * System will get the access token from DB and use it to connect to google calendar account.
 * If system is not able to connect to google calendar due to token expiry,
 * system will send mail to customer for access revoke and mark the token as expired.
 *
 * It pulls the meeting and insert it into meeting history table and will pull all
 * the meetings for the current year starting from the first month of the current year till end of the ongoing month.
 *
 * For every event or meeting pulled from google calendar system will derive
 * the meeting opportunity processing time,
 * which will be (meeting date + 3days) and add this time in the meeting history table
 *
 */
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

const DateFormat = 'Y-m-d';
const DateTimeFormat = 'Y-m-d H:i:s';

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;

require_once($_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php');
require_once('config.php');
$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);
$calendarIntegrations = Helpers::getIntegrations($customer);

// going through through the pulled google OAuth access record
foreach($calendarIntegrations as $integration) {
    $integrationData = json_decode($integration->getData());
    $accessToken = $integrationData->access_token;

    if( !$accessToken ) {
        trigger_error("Access token doesn't exist!", E_USER_WARNING);
        continue;
    }

    $client->setAccessToken($accessToken);
    $service = new Google_Service_Calendar($client);

    try {
        $calendarList = $service->calendarList->listCalendarList();

        if( !(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0)) ) {
            trigger_error("Calendar list is doesn't have items", E_USER_WARNING);
            continue;
        }

        $strUserId = "";

        foreach($calendarList->getItems() as $calendar) {
            if( !($calendar->id) ) {
                trigger_error("Calendar doesn't have an ID", E_USER_WARNING);
                continue;
            }

            // filter all other calendar other than client domain
            if(strpos($calendar->id, $strClientDomainName) === false) {
                continue;
            }

            if($calendar->primary == "1") {
                $strUserId = $calendar->id;
            }

            date_default_timezone_set($calendar->timeZone);

            $date = date(DateFormat,strtotime(' -1 day'));
            $nextMonthStart = date(DateFormat,strtotime('first day of +1 month'));
            $yearStartFiscal = date("Y")."-"."01"."-"."01";

            // get the latest meeting date in the DB and fetch all meets from that date ahead
            // for current calendar fetch the latest meet present in DB so as to set the lower limit
            // for further pulling calendar meets

            $lastMeeting = Helpers::getLastMeetingInDBForEmailAddress($customer, $calendar->id);

            if(!$lastMeeting) {
                $date = date(DateFormat,strtotime($yearStartFiscal));
            } else {
                $eventDateTime = strtotime($lastMeeting->getEventDatetime());
                $date = (strtotime($date) <= $eventDateTime) ? $date : date(DateFormat, $eventDateTime);
            }

            $calendarId = $calendar->id;
            $optParams = array(
              'timeMin' => date('c',strtotime($date)),
              'timeMax' => date('c',strtotime($nextMonthStart)),
              'orderBy' => 'startTime',
              'singleEvents' => TRUE
            );

            $results = $service->events->listEvents($calendarId, $optParams);

            if( !(is_array($results->getItems()) && (count($results->getItems())>0)) ) {
                continue;
            }

            $intFrCnt = 0;
            $calTimeZone = $results->timeZone; // Calendar Timezone

            date_default_timezone_set($calTimeZone); // Setting default timezone

            $arrResultData = array();

            foreach ($results->getItems() as $event) {

                // ================================ @todo Continue!! ================

                $strEventId = $calendar->id."_".$event->id;
                $eventDateStr = $event->start->dateTime;

                if(empty($eventDateStr)) { // All-day event
                    $eventDateStr = $event->start->date;
                }

                $temp_timezone = $event->start->timeZone;   // If the event has a special timezone

                // If there is no timezone
                $timezone = empty($temp_timezone) ? new DateTimeZone($calTimeZone) : new DateTimeZone($temp_timezone);

                $eventdate = new DateTime($eventDateStr,$timezone);
                $newmonth = $eventdate->format("M");    // Converting to legible month
                $newday = $eventdate->format("j");      // Converting to legible day
                $arrEventCreator = (array) $event->creator;
                $arrEventAttendees = array();
                $strInternalDomain = "Internal";
                $arrResultData[$intFrCnt]['eventid'] = $event->id;
                $arrResultData[$intFrCnt]['eventdate'] = $newday;
                $arrResultData[$intFrCnt]['eventmonth'] = $newmonth;
                $arrResultData[$intFrCnt]['eventsummary'] = $event->getSummary();
                $arrResultData[$intFrCnt]['eventdescription'] = $event->description;
                $arrResultData[$intFrCnt]['calendarid'] = $calendar->id;
                $arrResultData[$intFrCnt]['userid'] = $strUserId;

                $strStardDate = date(DateTimeFormat,strtotime($eventdate->format("Y")."-".$eventdate->format("m")."-".$eventdate->format("d")));
                $arrResultData[$intFrCnt]['startdate'] = $strStardDate;
                if($strStardDate) {
                    $inDForm = date(DateFormat,strtotime($strStardDate))." 00:00:00";
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

                            if(in_array($domain, $personalEmailDomains)) {
                                $strOtherDomain .= "1,";
                            } else {
                                $strInternalDomain = "External";
                                $strOtherDomain .= "0,";
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
        trigger_error("Exception is raised: " . $e->getMessage(), E_WARNING);
        $arrMessageDetails = json_decode($e->getMessage(),true);

        $updateFlag = is_array($arrMessageDetails) &&
            (count($arrMessageDetails)>0) && ($arrMessageDetails['error']['message'] == "Invalid Credentials");

        if($updateFlag) {
            $contactEmailAddress = $integration->getCustomerContact()->getEmail();
            $integration->setStatus(CustomerContactIntegration::STATUS_EXPIRED);
            $integration->save();
//            Helpers::fnSendAccountExpirationMail($contactEmailAddress);
        }
    }
}
