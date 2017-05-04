<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
require_once 'config.php';
$strClientDomain = $strClientDomainName;
$strPersonalDomain = implode(",",$arrPersonalDomain);
$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");

$arrGcalUser = fnGetGcalUser();

function fnGetGcalUser() {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	$base = $strAirtableBase;
	$table = 'gaccounts';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table;
	$authorization = "Authorization: Bearer ".$strApiKey;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($ch,CURLOPT_URL, $url);

	$result = curl_exec($ch);

	if(!$result) {
		echo 'error:' . curl_error($ch);
		return false;
	}

    $arrResponse = json_decode($result,true);

    if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
        $arrSUser = $arrResponse['records'];
        return $arrSUser;
    }

    return false;
}

if(is_array($arrGcalUser) && (count($arrGcalUser)>0)) {
	$intFrCnt = 0;

	foreach($arrGcalUser as $arrUser) {
		$intFrCnt++;
		$arrUserDet = $arrUser['fields'];
		$strEmail = $arrUserDet['user_email'];
		$strARecId = $arrUser['id'];
		$arrTok = json_decode($arrUserDet['user_token'],true);

		if(is_array($arrTok) && (count($arrTok)>0)) {
			$client->setAccessToken($arrTok);
			$service = new Google_Service_Calendar($client);
			
			try {
				$calendarList = $service->calendarList->listCalendarList();

				if(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0)) {
					$strUserId = "";
					$intUsrCnt = 0;
					$arrUserData = array();
					$intCalNt = 0;

					foreach($calendarList->getItems() as $calendar) {
                        if( !($calendar->id) ) {
                            continue;
                        }

                        if(strpos($calendar->id, $strClientDomain) === false) {
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

                        $arrMeets = fnGetLatestMeetsForUser($calendar->id);

                        if(is_array($arrMeets) && (count($arrMeets)>0))
                        {
                            echo "<br>--".$strMeetingDate = $arrMeets[0]['fields']['Meeting Date'];
                            if($strMeetingDate)
                            {
                                $strMeetingFormattedDate = strtotime($strMeetingDate);
                                //echo "---".strtotime($strDate);
                                if($strMeetingFormattedDate > strtotime($strDate))
                                {
                                    $strDate = $strDate;
                                }
                                else
                                {
                                    $strDate = date('Y-m-d',$strMeetingFormattedDate);
                                }
                            }
                            else
                            {

                                $strDate = date('Y-m-d',strtotime($strNeededStartDate));
                            }

                        }
                        else
                        {
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

                                    if($domain != $strClientDomain) {
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
                                    $isAttPresent = fnCheckMeetingAlreadyPresent($arrResultData[$intFrCnt]);

                                    if(!$isAttPresent) {
                                        $isAttendeesSaved = fnSaveAirtableMeetings($arrResultData[$intFrCnt]);
                                    }

                                    $intFrCnt++;
                                }
                            }
                        }
					}
				}
			} catch(Exception $e) {
				print("<pre>");
				echo $e->getMessage();
				$arrMessageDetails = json_decode($e->getMessage(),true);

				$updateFlag = is_array($arrMessageDetails) &&
                    (count($arrMessageDetails)>0) && ($arrMessageDetails['error']['message'] == "Invalid Credentials");

				if($updateFlag) {
                    fnUpdatesGstatus($strEmail);
                    fnSendAccountExpirationMail($strEmail);
				}
			}
		}
	}
}

function fnGetLatestMeetsForUser($strEmail) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("Meetingsreverse");
    $url .= '&filterByFormula=('.rawurlencode("{calendaremail}='".$strEmail."'").')';

    $authorization = "Authorization: Bearer ".$strApiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);

    //execute post
    $result = curl_exec($ch);//exit;

    if(!$result) {
        echo 'error:' . curl_error($ch);
        return false;
    }

    $arrResponse = json_decode($result,true);

    if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
        $arrSUser = $arrResponse['records'];
        return $arrSUser;
    }

    return false;
}

function fnSendAccountExpirationMail($strEmail = "") {
	global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

    if(!$strEmail) {
        return false;
    }

    $to = $strEmail;
    $subject = "Google Calendar Access Expired";
    $strFrom = $strFromEmailAddress;

    $message = "Hello There,".'<br/><br/>';
    $message .= 'The Access to your calendar has been expired. <br/><br/>';

    $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

    $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
    $message .= 'Thanks';

    require_once 'Mail.php';

    $headers = array (
      'From' => $strFrom,
      'To' => $to,
      'Subject' => $subject);

    $smtpParams = array (
      'host' => $strSmtpHost,
      'port' => $strSmtpPPort,
      'auth' => true,
      'username' => $strSmtpUsername,
      'password' => $strSmtpPassword
    );

     // Create an SMTP client.
    $mail = Mail::factory('smtp', $smtpParams);

    // Send the email.
    $result = $mail->send($to, $headers, $message);

    if (PEAR::isError($result)) {
      echo("Email not sent. " .$result->getMessage() ."\n");
      return false;
    }

    echo("Email sent!"."\n");
    return true;
}

function fnUpdatesGstatus($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $strId = fnGetUsergAcc($strEmail);

    if(!$strId) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['status'] = "expired";

    $srtF = json_encode($arrFields);
    $curl = curl_init($url);
    // Accept any server (peer) certificate on dev envs
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
    $info = curl_getinfo($curl);
    $response = curl_exec($curl);

    if(!$response) {
        echo curl_error($curl);
    }

    curl_close($curl);
    $jsonResponse =  json_decode($response,true);

    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function fnGetUsergAcc($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $api_key = 'keyOhmYh5N0z83L5F';
    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= "?filterByFormula=(user_email='".$strEmail."')";
    $authorization = "Authorization: Bearer ".$strApiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);

    //execute post
    $result = curl_exec($ch);

    if(!$result) {
        return true;
    }

    $arrResponse = json_decode($result,true);

    if( !(is_array($arrResponse) && (count($arrResponse)>0))) {
        return true;
    }

    $arrRecords = $arrResponse['records'];

    if(is_array($arrRecords) && (count($arrRecords)>0)) {
        return $arrRecords[0]['id'];
    }

    return false;
}

function fnSaveAirtableMeetings($arrRecord = array()) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if( !(is_array($arrRecord) && (count($arrRecord)>0)) ) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;

    $authorization = "Authorization: Bearer ".$strApiKey;

    if($arrRecord['eventsummary']) {
        $arrFields['fields']['Meeting Name'] = $arrRecord['eventsummary'];
    }

    if($arrRecord['startdate']) {
        $arrFields['fields']['Meeting Date'] = date("m/d/Y",strtotime($arrRecord['startdate']));
    }

    if($arrRecord['calendarid']) {
        $arrFields['fields']['calendaremail'] = $arrRecord['calendarid'];
    }

    if($arrRecord['creayedbyname']) {
        $arrFields['fields']['Created By'] = $arrRecord['creayedbyname'];
    }

    if($arrRecord['ceatedbyemail']) {
        $arrFields['fields']['created_by_email'] = $arrRecord['ceatedbyemail'];
    }

    if($arrRecord['eventdescription']) {
        $arrFields['fields']['Description'] = $arrRecord['eventdescription'];
    }

    if($arrRecord['attendeesemail']) {
        $arrFields['fields']['Attendee Email(s)'] = $arrRecord['attendeesemail'];
    }

    if($arrRecord['meetingtype']) {
        $arrFields['fields']['Meeting'] = $arrRecord['meetingtype'];
    }

    if($arrRecord['processtime']) {
        $arrFields['fields']['meetingprocesstime'] = $arrRecord['processtime'];
    }

    if($arrRecord['eventid']) {
        $arrFields['fields']['gcal_mee_id'] = $arrRecord['eventid'];
    }

    $srtF = json_encode($arrFields);
    $curl = curl_init($url);
    // Accept any server (peer) certificate on dev envs
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
    $info = curl_getinfo($curl);
    echo "---".$response = curl_exec($curl);//exit;
    curl_close($curl);
    $jsonResponse =  json_decode($response,true);
    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function fnCheckMeetingAlreadyPresent($arrRecord = array()) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if( !(is_array($arrRecord) && (count($arrRecord)>0)) ) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= "?filterByFormula=(gcal_mee_id='".$arrRecord['eventid']."')";
    $authorization = "Authorization: Bearer ".$strApiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);

    //execute post
    echo "--".$result = curl_exec($ch);

    if(!$result) {
        return true;
    }

    $arrResponse = json_decode($result,true);

    if(is_array($arrResponse) && (count($arrResponse)>0)) {
        $arrRecords = $arrResponse['records'];
        return (is_array($arrRecords) && (count($arrRecords)>0));
    }

    return true;
}
