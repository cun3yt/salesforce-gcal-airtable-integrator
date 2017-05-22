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
error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

const DateFormat = 'Y-m-d';
const DateTimeFormat = 'Y-m-d H:i:s';

use DataModels\DataModels\Meeting as Meeting;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;

use DataModels\DataModels\MeetingAttendee as MeetingAttendee;

require_once('config.php');

$apiClient = Helpers::setupGoogleAPIClient($googleCalAPICredentialFile, true);

/**
 * @var $client Client
 */
list($client, $contacts) = Helpers::loadClientData($strClientDomainName);
$calendarAuths = Helpers::getAuthentications($client);

/**
 * @var $auth ClientCalendarUserOAuth
 */
foreach($calendarAuths as $auth) {
    $emailAddress = $auth->getClientCalendarUser()->getEmail();
    echo "Processing calendars of {$emailAddress} <br/>";
    processCalendars($client, $auth, $apiClient);
}

/**
 * @param Client $client
 * @param ClientCalendarUserOAuth $auth
 * @param Google_Client $apiClient
 */
function processCalendars(Client $client, ClientCalendarUserOAuth $auth, Google_Client $apiClient) {
    $integrationData = json_decode($auth->getData());
    $accessToken = $integrationData->access_token;

    if( !$accessToken ) {
        trigger_error("Access token doesn't exist!", E_USER_WARNING);
        return;
    }

    $apiClient->setAccessToken($auth->getData());
    $service = new Google_Service_Calendar($apiClient);

    try {
        $calendarList = $service->calendarList->listCalendarList();

        if( !(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0)) ) {
            trigger_error("Calendar list is doesn't have items", E_USER_WARNING);
            return;
        }

        foreach($calendarList->getItems() as $calendar) {
            echo "Processing Calendar: {$calendar->id}<br/>";
            if( !isCalInClient($calendar, $client) ) {
                trigger_error("{$calendar->id} is not in the domain: {$client->getEmailDomain()}. 
                    Checking the next calendar", E_USER_WARNING);
                continue;
            }
            processCalendar($client, $service, $calendar);
        }
    } catch(Exception $e) {
        trigger_error("Exception raised: " . $e->getMessage(), E_USER_WARNING);
        $arrMessageDetails = json_decode($e->getMessage(),true);

        $updateFlag = is_array($arrMessageDetails) &&
            (count($arrMessageDetails)>0) && ($arrMessageDetails['error']['message'] == "Invalid Credentials");

        if($updateFlag) {
            $contactEmailAddress = $auth->getClientCalendarUser()->getEmail();
            $auth->setStatus(ClientCalendarUserOAuth::STATUS_EXPIRED);
            $auth->save();
            Helpers::sendAccountExpirationMail($contactEmailAddress);
        }
    }
}

/**
 * @param Client $client
 * @param $service
 * @param $calendar
 */
function processCalendar(Client $client, $service, $calendar) {
    if( !($calendar->id) ) {
        trigger_error("Calendar doesn't have an ID", E_USER_WARNING);
        return;
    }

    date_default_timezone_set($calendar->timeZone);

    $date = date(DateFormat,strtotime(' -1 day'));
    $nextMonthStart = date(DateFormat,strtotime('first day of +1 month'));
    $yearStartFiscal = date("Y")."-"."01"."-"."01";

    // get the latest meeting date in the DB and fetch all meets from that date ahead
    // for current calendar fetch the latest meet present in DB so as to set the lower limit
    // for further pulling calendar meets
    $lastMeeting = Helpers::getLastMeetingInDBForEmailAddress($client, $calendar->id);

    if(!$lastMeeting) {
        $date = date(DateFormat,strtotime($yearStartFiscal));
    } else {
        $eventDateTime = $lastMeeting->getEventDatetime();
        $date = (strtotime($date) <= $eventDateTime->format('U')) ? $date : $eventDateTime->format(DateFormat);
        unset($eventDateTime);
    }

    $calendarId = $calendar->id;
    $optParams = array(
        'timeMin' => date('c',strtotime($date)),
        'timeMax' => date('c',strtotime($nextMonthStart)),
        'orderBy' => 'startTime',
        'singleEvents' => TRUE
    );

    $events = $service->events->listEvents($calendarId, $optParams);

    if( !(is_array($events->getItems()) && (count($events->getItems())>0)) ) {
        return;
    }

    $calTimeZone = $events->timeZone; // Calendar Timezone

    date_default_timezone_set($calTimeZone); // Setting default timezone

    foreach ($events->getItems() as $event) {
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

        $startDate = date(DateTimeFormat,strtotime($eventDate->format("Y")."-".$eventDate->format("m")."-".$eventDate->format("d")));
        if($startDate) {
            $inDForm = date(DateFormat,strtotime($startDate))." 00:00:00";
            $startTime = strtotime($inDForm);
            $processTime = strtotime("+7 day", $startTime);
        }

        $outsideDomain = "";
        foreach($event->getAttendees() as $attendee) {
            $attendeeEmail = $attendee->getEmail();
            $domain = substr(strrchr($attendeeEmail, "@"), 1);

            $eventAttendeesList[] = $attendeeEmail;

            if($domain == $client->getEmailDomain()) {
                continue;
            }

            if(in_array($domain, Helpers::getPersonalEmailDomains() )) {
                $outsideDomain .= "1,";
            } else {
                $eventType = "External";
                $outsideDomain .= "0,";
            }
        }

        if($outsideDomain && (stripos($outsideDomain, "0") === false)) {
            $eventType = "Other";
        }

        if($event->getSummary()) {
            if( !(is_array($eventAttendeesList) && (count($eventAttendeesList)>0)) ) {
                continue;
            }

            $meeting = Helpers::getMeetingIfExists($event);
            // @todo what about changes/deletion to the event? Id check will not show the difference!

            // @todo Assoc. attendee list to account, account_has_contact & contact tables.

            if(!$meeting) {
                saveNewMeeting($client, $event, $calendarId, $eventDate, $eventType);
            }
        }
    }
}

/**
 * @param Client $client
 * @param $event
 * @param $calendarId
 * @param $eventDate
 * @param $eventType
 * @return Meeting
 */
function saveNewMeeting(Client $client, $event, $calendarId, $eventDate, $eventType) {
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
     */
    $meeting->save();
    echo "<pre>";
    var_dump(json_decode($meeting->exportTo('json')));
    echo "</pre>";

    /**
     * Save event creator
     */
    $eventCreator = $event->getCreator();
    $creatorEmailAddress = $eventCreator->getEmail();
    $meetingCreator = getOrSaveMeetingAttendee($client, $creatorEmailAddress);
    $meeting->setEventCreator($meetingCreator)
        ->save();

    /**
     * Save event organizer
     */
    $eventOrganizer = $event->getOrganizer();
    $organizerEmailAddress = $eventOrganizer->getEmail();
    $meetingOwner = getOrSaveMeetingAttendee($client, $organizerEmailAddress);
    $meeting->setEventOwner($meetingOwner)
        ->save();

    /**
     * Save Attendees
     */
    foreach($event->getAttendees() as $att) {
        $emailAddress = $att->getEmail();
        $attendee = getOrSaveMeetingAttendee($client, $emailAddress);
        $meeting->addMeetingAttendee($attendee);
    }

    $meeting->save();

    // @todo Logging will be AWESOME!

    return $meeting;
}

/**
 * @param Client $client
 * @param $attendeeEmailAddress
 * @return \DataModels\DataModels\Contact|\DataModels\DataModels\MeetingAttendee
 */
function getOrSaveMeetingAttendee(Client $client, $attendeeEmailAddress) {
    $creatorContact = NULL;

    if (isEmailAddressInDomain($attendeeEmailAddress, $client->getEmailDomain())) {
        $q = new \DataModels\DataModels\ClientCalendarUserQuery();
        $clientCalendarUser = $q->findOneByEmail($attendeeEmailAddress);
        if (!$clientCalendarUser) {
            $clientCalendarUser = new \DataModels\DataModels\ClientCalendarUser();
            $clientCalendarUser->setEmail($attendeeEmailAddress)
                ->setClient($client)
                ->save();
        }
        $creatorContact = $clientCalendarUser;
    } else {
        $q = new \DataModels\DataModels\ContactQuery();
        $contact = $q->findOneByEmail($attendeeEmailAddress);
        if (!$contact) {
            $contact = new \DataModels\DataModels\Contact();
            $contact->setEmail($attendeeEmailAddress)->save();
        }
        $creatorContact = $contact;
    }

    return $creatorContact;
}

function isCalInClient(Google_Service_Calendar_CalendarListEntry $calendar, Client $client) {
    return isEmailAddressInDomain($calendar->id, $client->getEmailDomain());
}

function isEmailAddressInDomain($emailAddress, $domain) {
    list($identifier, $emailDomain) = explode('@', $emailAddress);
    return ($emailDomain == $domain);
}
