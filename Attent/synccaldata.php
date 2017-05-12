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
 */

error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

const DateFormat = 'Y-m-d';
const DateTimeFormat = 'Y-m-d H:i:s';

use DataModels\DataModels\Meeting as Meeting;
use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;
use DataModels\DataModels\Customer as Customer;

use DataModels\DataModels\MeetingAttendee as MeetingAttendee;

require_once('config.php');

$client = setupGoogleAPIClient($googleCalAPICredentialFile);

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);
$calendarIntegrations = Helpers::getIntegrations($customer);

foreach($calendarIntegrations as $integration) {
    processIntegrationCalendars($customer, $integration, $client);
}

/**
 * @param string $googleCalAPICredentialFile
 * @return Google_Client
 */
function setupGoogleAPIClient($googleCalAPICredentialFile) {
    require_once($_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php');
    $client = new Google_Client();
    $client->setAuthConfig($googleCalAPICredentialFile);
    $client->addScope(array(Google_Service_Calendar::CALENDAR));
    $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
    $client->setHttpClient($guzzleClient);
    $client->setAccessType("offline");
    return $client;
}

/**
 * @param Customer $customer
 * @param CustomerContactIntegration $integration
 * @param Google_Client $client
 */
function processIntegrationCalendars(Customer $customer, CustomerContactIntegration $integration, Google_Client $client) {
    $integrationData = json_decode($integration->getData());
    $accessToken = $integrationData->access_token;

    if( !$accessToken ) {
        trigger_error("Access token doesn't exist!", E_USER_WARNING);
        return;
    }

    $client->setAccessToken($accessToken);
    $service = new Google_Service_Calendar($client);

    try {
        $calendarList = $service->calendarList->listCalendarList();

        if( !(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0)) ) {
            trigger_error("Calendar list is doesn't have items", E_USER_WARNING);
            return;
        }

        foreach($calendarList->getItems() as $calendar) {
            if( !isCalInCustomer($calendar, $customer) ) {
                trigger_error("{$calendar->id} is not in Customer's domain: {$customer->getEmailDomain()}. 
                    Checking the next calendar", E_WARNING);
                continue;
            }
            processCalendar($customer, $service, $calendar);
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
            Helpers::sendAccountExpirationMail($contactEmailAddress);
        }
    }
}

/**
 * @param Customer $customer
 * @param $service
 * @param $calendar
 */
function processCalendar(Customer $customer, $service, $calendar) {
    $strUserId = "";
    if( !($calendar->id) ) {
        trigger_error("Calendar doesn't have an ID", E_USER_WARNING);
        return;
    }

    // filter all other calendar other than client domain
    if(strpos($calendar->id, $customer->getEmailDomain()) === false) {
        return;
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
        unset($eventDateTime);
    }

    $calendarId = $calendar->id;
    $optParams = array(
        'timeMin' => date('c',strtotime($date)),
        'timeMax' => date('c',strtotime($nextMonthStart)),
        'orderBy' => 'startTime',
        'singleEvents' => TRUE
    );

    $events = $service->events->listEvents($calendarId, $optParams); // @todo $results better named "meetings" or "events"

    if( !(is_array($events->getItems()) && (count($events->getItems())>0)) ) {
        return;
    }

    $calTimeZone = $events->timeZone; // Calendar Timezone

    date_default_timezone_set($calTimeZone); // Setting default timezone

    foreach ($events->getItems() as $event) {
        $resultData = array();

        $eventDatetime = $event->start->dateTime;

        if(empty($eventDatetime)) { // All-day event
            $eventDatetime = $event->start->date;
        }

        $tempTimezone = $event->start->timeZone;   // If the event has a special timezone

        // If there is no timezone
        $timezone = empty($tempTimezone) ? new DateTimeZone($calTimeZone) : new DateTimeZone($tempTimezone);

        $eventDate = new DateTime($eventDatetime,$timezone);
        $creator = $event->getCreator();
        $eventAttendeesList = array();
        $eventType = "Internal";
        $resultData['userid'] = $strUserId;

        $startDate = date(DateTimeFormat,strtotime($eventDate->format("Y")."-".$eventDate->format("m")."-".$eventDate->format("d")));
        $resultData['startdate'] = $startDate;
        if($startDate) {
            $inDForm = date(DateFormat,strtotime($startDate))." 00:00:00";
            $startTime = strtotime($inDForm);
            $processTime = strtotime("+7 day", $startTime);
            $resultData['processtime'] = $processTime;
        }

        $resultData['ceatedbyemail'] = $creator ? $creator->getEmail() : "";
        $resultData['createdByName'] = $creator ? $creator->getDisplayName() : "";

        // ----------
        $outsideDomain = "";
        foreach($event->getAttendees() as $attendee) {
            $attendeeEmail = $attendee->getEmail();
            $domain = substr(strrchr($attendeeEmail, "@"), 1);

            $eventAttendeesList[] = $attendeeEmail;

            if($domain == $customer->getEmailDomain()) {
                continue;
            }

            if(in_array($domain, Helpers::getPersonalEmailDomains() )) {
                $outsideDomain .= "1,";
            } else {
                $eventType = "External";
                $outsideDomain .= "0,";
            }
        }
        $resultData['attendeesemail'] = implode(",",$eventAttendeesList);
        // ----------

        if($outsideDomain && (stripos($outsideDomain, "0") === false)) {
            $eventType = "Other";
        }

        $resultData['meetingtype'] = $eventType;

        if($event->getSummary()) {
            if( !(is_array($eventAttendeesList) && (count($eventAttendeesList)>0)) ) {
                continue;
            }

            $meeting = Helpers::getMeetingIfExists($event);
            // @todo what about changes to the event? Id check will not show the difference!

            // @todo Save attendee list to meeting_attendee, meeting_has_attendee, account, account_has_contact & contact tables.

            if(!$meeting) {
                saveNewMeeting($event, $calendarId, $eventDate, $eventType);
            }
        }

        unset($resultData);
    }
}

/**
 * @param $event
 * @param $calendarId
 * @param $eventDate
 * @param $eventType
 * @return Meeting
 */
function saveNewMeeting($event, $calendarId, $eventDate, $eventType, $dryRun = true) {
    $meeting = new Meeting();
    $meeting->setEventId($event->getId())
        ->setName($event->getSummary())
        ->setEventDatetime($eventDate)
        ->setAdditionalData(json_encode(array('calendarId' => $calendarId)))
        ->setEventCreatedAt($event->getCreated())
        ->setEventUpdatedAt($event->getUpdated())
        ->setRawData(json_encode($event->toSimpleObject()))
        ->setEventType($eventType);

    if($desc = $event->getDescription()) {
        $meeting->setEventDescription($desc);
    }

    /**
     * Saving owner, attendees
     *
     * @todo less priority: propelorm's way of inheritance for (meeting_attendee <- ...) mapping
     */

    if(!$dryRun) {
        $meeting->save();
    } else {
        echo "<pre>";
        echo "DRY RUN SAVE";
        var_dump(json_decode($meeting->exportTo('json')));
        echo "</pre>";
    }

    // @todo Logging will be AWESOME!

    return $meeting;
}

function isCalInCustomer(Google_Service_Calendar_CalendarListEntry $calendar, Customer $customer) {
    list($identifier, $domain) = explode('@', $calendar->id);
    return ($domain == $customer->getEmailDomain());
}
