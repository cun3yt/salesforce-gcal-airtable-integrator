<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once 'vendor/autoload.php';
$strDate = date('Y-m-d',strtotime(' -1 day'));
$client = new Google_Client();
$client->setAuthConfig('calendar.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");

function fnSaveAirtableMeetingAttendees($strEventId = "",$arrRecord = array(),$strEvenId = "")
{
	//print("<pre>");
	//print_r($arrRecord);
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		//print("<pre>");
		//print_r($arrRecord);
		//echo $arrRecord['email'];
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'meeting_attendees';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($strEventId)
		{
			$arrFields['fields']['meeting_attendee_id'] = $strEventId;
		}
		
		if($arrRecord['email'])
		{
			$arrFields['fields']['meeting_attendee_email'] = $arrRecord['email'];
		}
		
		if($strEvenId)
		{
			$arrFields['fields']['event_id'] = $strEvenId;
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
		$response = curl_exec($curl);
		curl_close($curl);
		$jsonResponse =  json_decode($response,true);
		//print("<pre>");
		//print_r($jsonResponse);
		if(is_array($jsonResponse) && (count($jsonResponse)>0))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function fnCheckMeetingAttendeeAlreadyPresent($strEventId = "")
{
	//echo "--".$strEventId;
	
	if($strEventId)
	{
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'meeting_attendees';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url .= "?filterByFormula=(meeting_attendee_id='".$strEventId."')";
		$authorization = "Authorization: Bearer ".$strApiKey;
		//echo $url;exit; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);

		//execute post
		$result = curl_exec($ch);
		if(!$result)
		{
			return true;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			if(is_array($arrResponse) && (count($arrResponse)>0))
			{
				$arrRecords = $arrResponse['records'];
				if(is_array($arrRecords) && (count($arrRecords)>0))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
			  return true;	
			}
		}
	}
}

$arrGcalUser = fnGetGcalUser();
//print("<pre>");
//print_r($arrGcalUser);

function fnGetGcalUser()
{
	$api_key = 'keyOhmYh5N0z83L5F';
	$base = 'appTUmuDLBrSfWRrZ';
	$table = 'gaccounts';
	$strApiKey = "keyOhmYh5N0z83L5F";
	$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
	$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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
	if(!$result)
	{
		echo 'error:' . curl_error($ch);
		return false;
	}
	else
	{
		$arrResponse = json_decode($result,true);
		if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
		{
			$arrSUser = $arrResponse['records'];
			return $arrSUser;
			
		}
		else
		{
			return false;
		}
	}
}

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		//print("<pre>");
		//print_r($arrUser);
		//continue;
		
		$intFrCnt++;
		$arrUserDet = $arrUser['fields'];
		$strEmail = $arrUserDet['user_email'];
		$strARecId = $arrUser['id'];
		$arrTok = json_decode($arrUserDet['user_token'],true);
		//print("<pre>");
		//print_r($arrTok);
		//exit;
		
		
		if(is_array($arrTok) && (count($arrTok)>0))
		{
			$client->setAccessToken($arrTok);
			 /*$url = 'https://www.google.com/m8/feeds/contacts/'.$strEmail.'/full?max-results=200&alt=json&v=3.0&oauth_token='.$arrTok['access_token'];
			$xmlresponse =  curl($url);
			echo "--".$contacts = json_decode($xmlresponse,true);
			print("<pre>");
			print_r($contacts);
			exit;*/
			
			//$url = 'https://www.google.com/m8/feeds/groups/default/full?alt=json&v=3.0&oauth_token='.$arrTok['access_token'];
			//$xmlresponse =  curl($url);
			//echo "--".$contacts = json_decode($xmlresponse,true);
			//print("<pre>");
			//print_r($contacts);
			//continue;
			//exit;
			
			
			//$objPlus = new Google_Service_Plus($client);
			//$arrMyDet = $objPlus->people->get('me');
			//print("<pre>");
			//print_r($arrMyDet);
			//exit;
			//$googlePlus->people->get('me');
			$service = new Google_Service_Calendar($client);
			
			//echo "hi";
			//exit;
			try 
			{
				$calendarList = $service->calendarList->listCalendarList();
				if(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0))
				{
					$strUserId = "";
					$intUsrCnt = 0;
					$arrUserData = array();
					foreach($calendarList->getItems() as $calendar)
					{
						//echo "--".$calendar->id;
						//exit;
						
						if($calendar->id)
						{
							//echo "hi";
							if($calendar->primary == "1")
							{
								$strUserId = $calendar->id;
								$arrUserData[json_encode($strTok)] = $calendar->id;
							}
							$calendarId = $calendar->id;
							

							$strMinTime = date('c',strtotime($strDate));
							$optParams = array(
							  'timeMin' => date('c',strtotime($strDate))
							);
							//print("<pre>");
							//print_r($optParams);
							//$results = $service->events->listEvents($calendarId, $optParams);
							$results = $service->events->listEvents("primary",$optParams);
							//$results = $service->events->listEvents("primary");
							
							//print("<pre>");
							//print_r($results);
							//exit;
							
							if(is_array($results->getItems()) && (count($results->getItems())>0))
							{
								//echo "bi";exit;
								$intFrCnt = 0;
								$calTimeZone = $results->timeZone; //GET THE TZ OF THE CALENDAR
								//SET THE DEFAULT TIMEZONE SO PHP DOESN'T COMPLAIN. SET TO YOUR LOCAL TIME ZONE.
								date_default_timezone_set($calTimeZone);
								$arrResultData = array();
								foreach ($results->getItems() as $event) 
								{
									
									

									//echo "event - id - ".$event->eventId;
									
									//$eventdetail = $service->events->get($calendar->id, "eventId");
									
									$strEventId = $calendar->id."_".$event->id;
									
									
									//print("<pre>");
									//print_r($event);
									//continue;
									
									//echo "bi";exit;
									
									//echo "--".$event->start->dateTime;
									//echo "--".$temp_timezone = $results->timeZone;;
									//$timezone = new DateTimeZone($results->timeZone);
									//$eventdate = new DateTime($eventDateStr,$timezone);
									//echo "--".$newmonth = $eventdate->format("M");//CONVERT REGULAR EVENT DATE TO LEGIBLE MONTH
								   //echo "--". $newday = $eventdate->format("j");//CONVERT REGULAR EVENT DATE TO LEGIBLE DAY
									//print("<pre>");
									//print_r($event);
									//exit;
									
									$eventDateStr = $event->start->dateTime;
									if(empty($eventDateStr))
									{
										// it's an all day event
										$eventDateStr = $event->start->date;
									}
									
									$temp_timezone = $event->start->timeZone;
									//THIS OVERRIDES THE CALENDAR TIMEZONE IF THE EVENT HAS A SPECIAL TZ
									if (!empty($temp_timezone)) 
									{
										$timezone = new DateTimeZone($temp_timezone); //GET THE TIME ZONE
										//Set your default timezone in case your events don't have one
									} 
									else 
									{ 
										$timezone = new DateTimeZone($calTimeZone);
									}
									
									$eventdate = new DateTime($eventDateStr,$timezone);
									$newmonth = $eventdate->format("M");//CONVERT REGULAR EVENT DATE TO LEGIBLE MONTH
									$newday = $eventdate->format("j");//CONVERT REGULAR EVENT DATE TO LEGIBLE DAY
									
									$arrResultData[$intFrCnt]['eventid'] = $strEventId;
									$arrResultData[$intFrCnt]['eventdate'] = $newday;
									$arrResultData[$intFrCnt]['eventmonth'] = $newmonth;
									$arrResultData[$intFrCnt]['eventsummary'] = $event->getSummary();
									$arrResultData[$intFrCnt]['calendarid'] = $calendar->id;
									$intUId = $strUserId;
									$arrResultData[$intFrCnt]['userid'] = $strUserId;
									
									$strStardDate = date("Y-m-d H:i:s",strtotime($eventdate->format("Y")."-".$eventdate->format("m")."-".$eventdate->format("d")));
									$arrResultData[$intFrCnt]['startdate'] = $strStardDate;
									
									if(is_array($event->attendees) && (count($event->attendees)>0))
									{
										$arrAttendees = $event->attendees;
										
										foreach($arrAttendees as $arrAttend)
										{
											//echo "--".$arrAttend->email;
											//continue;
											
											$strEventAttId = $strEventId."_".$arrAttend->email;
											$isAttPresent = fnCheckMeetingAttendeeAlreadyPresent($strEventAttId);
											
											if(!$isAttPresent)
											{
												$arrAtt = (array) $arrAttend;
												$isAttendeesSaved = fnSaveAirtableMeetingAttendees($strEventAttId,$arrAtt,$strEventId);
											}
										}
										//print("<pre>");
										//print_r($arrAttendees);
									}
									
									$intFrCnt++;
								}
								//print("<pre>");
								//print_r($_SESSION['calendardata']);
							}
						}	
					}
				}
			}
			catch(Exception $e) 
			{
				print("<pre>");
				echo $e->getMessage();
				$arrMessageDetails = json_decode($e->getMessage(),true);
				if(is_array($arrMessageDetails) && (count($arrMessageDetails)>0))
				{
					if($arrMessageDetails['error']['message'] == "Invalid Credentials")
					{
						echo "Send email to ".$strEmail.", saying credentials not working";
					}
				}
			}
			
		}
	}
}


function curl($url, $post = "") {
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	curl_setopt($curl, CURLOPT_URL, $url);
	//The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	//The number of seconds to wait while trying to connect.
	if ($post != "") {
		curl_setopt($curl, CURLOPT_POST, 5);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	//To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	//To stop cURL from verifying the peer's certificate.
	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}
		
?>