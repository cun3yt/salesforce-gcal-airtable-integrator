<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once '/var/www/html/gcal/vendor/autoload.php';
require_once 'config.php';
$strClientDomain = $strClientDomainName;
$strPersonalDomain = implode(",",$arrPersonalDoamin);
$client = new Google_Client();
$client->setAuthConfig('/var/www/html/gcal/15FiveCal.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");
//echo "hi";exit;

$arrGcalUser = fnGetGcalUser();
//print("<pre>");
//print_r($arrGcalUser);

function fnGetGcalUser()
{
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
			$service = new Google_Service_Calendar($client);
			
			//echo "hi";
			//exit;
			try 
			{
				$calendarList = $service->calendarList->listCalendarList();
				//print("<pre>");
				//print_r($calendarList);
				//exit;
				if(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0))
				{
					$strUserId = "";
					$intUsrCnt = 0;
					$arrUserData = array();
					$intCalNt = 0;
					foreach($calendarList->getItems() as $calendar)
					{
						//echo "--".$calendar->id;
						//exit;
						
						if($calendar->id)
						{
							
							if(strpos($calendar->id, $strClientDomain) !== false) 
							{
								$intCalNt++;
								
								//if($intCalNt == 2)
								//{
									//break;
								//}
								
								//print("Calendar <pre>");
								//print_r($calendar);
								//continue;
								//echo "hi";
								if($calendar->primary == "1")
								{
									$strUserId = $calendar->id;
									$arrUserData[json_encode($strTok)] = $calendar->id;
								}
								//if($calendar->id == "nazar@15five.com")
								//{
									//echo "--".$calendar->timeZone;
									date_default_timezone_set($calendar->timeZone);
									//$strDate = date('Y-m-d',strtotime(' -58 day'));
									echo "---".$strDate = date('Y-m-d',strtotime(' -1 day'));
									$strEndDate = date("Y-m-d",strtotime('first day of +1 month'));
									$strNeededStartDate = date("Y")."-"."01"."-"."01";
									// get the latest present meeting date from airtable and fetch all meets from that date ahead 
									
									$arrMeets = fnGetLatestMeetsForUser($calendar->id);
									//print("<pre>");
									//print_r($arrMeets);
									//continue;
									
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
									
									
									
									

									//echo "---".$strMinTime = date('c',strtotime($strDate));
									
									
									$optParams = array(
									  'timeMin' => date('c',strtotime($strDate)),
									  'timeMax' => date('c',strtotime($strEndDate)),
									  'orderBy' => 'startTime',
									  'singleEvents' => TRUE
									);
									//print("<pre>");
									//print_r($optParams);
									//continue;
									$results = $service->events->listEvents($calendarId, $optParams);
									
									
									
									
									//print("<pre>");
									//print_r($results);
									//continue;
									//exit;;
									
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
											if($strStardDate)
											{
												
												$inDForm = date("Y-m-d",strtotime($strStardDate))." 00:00:00";
												$strStartTime = strtotime($inDForm);
												$strProcessTime = strtotime("+7 day", $strStartTime);
												$arrResultData[$intFrCnt]['processtime'] = $strProcessTime;
												
												//echo "---".$strProcessTime;
												//echo "---".date("Y-m-d H:i:s",$strProcessTime);
											}
											
											
											//print("<pre>");
											//print_r($arrEventCreator);
											if(is_array($arrEventCreator) && (count($arrEventCreator)>0))
											{
												if($arrEventCreator['email'])
												{
													$arrResultData[$intFrCnt]['ceatedbyemail'] = $arrEventCreator['email'];
												}
												
												if($arrEventCreator['displayName'])
												{
													$arrResultData[$intFrCnt]['creayedbyname'] = $arrEventCreator['displayName'];
												}
												
											}
											if(is_array($event->attendees) && (count($event->attendees)>0))
											{
												$strOtherDomain = "";
												foreach($event->attendees as $arrAttendee)
												{
													//echo "<br>--".$arrAttendee['email'];
													
													$domain = substr(strrchr($arrAttendee['email'], "@"), 1);
													if($domain != $strClientDomain)
													{
														//echo "--domain other than client domain--".$domain;
														
														$pos1 = stripos($strPersonalDomain, $domain);
														if($pos1 === false)
														{
															//echo "hello--".
															
															$strInternalDomain = "External";
															$strOtherDomain .= "0,";
														}
														else
														{
															$strOtherDomain .= "1,";
														}
														//echo "--Other domain flag is--".$strOtherDomain;
													}
														
													$arrEventAttendees[] = $arrAttendee['email'];
												}
												$arrResultData[$intFrCnt]['attendeesemail'] = implode(",",$arrEventAttendees);
											}
											
											if($strOtherDomain)
											{
												$pos2 = stripos($strOtherDomain, "0");
												if($pos2 === false)
												{
													$arrResultData[$intFrCnt]['meetingtype'] = "Other";
												}
												else
												{
													$arrResultData[$intFrCnt]['meetingtype'] = $strInternalDomain;
												}
											}
											else
											{
												$arrResultData[$intFrCnt]['meetingtype'] = $strInternalDomain;
											}
									
											
											//echo "---".$arrResultData[$intFrCnt]['meetingtype'];
											//echo "---".$strPersonalDomain;
											
											//print("<pre>");
											//print_r($arrResultData);
											
											if($arrResultData[$intFrCnt]['eventsummary'])
											{
												//echo "--check--";
												//print("<pre>");
												//print_r($arrResultData);
												//continue;
												
												if(is_array($arrEventAttendees) && (count($arrEventAttendees)>0))
												{
													$isAttPresent = fnCheckMeetingAlreadyPresent($arrResultData[$intFrCnt]);
											
													//$fh = fopen("checkmeetinginserted.txt","a");
													//fwrite($fh,"Meeting Present or not ---".$isAttPresent);
													//fclose($fh);
																
													if(!$isAttPresent)
													{
														//$arrAtt = (array) $arrAttend;
														$isAttendeesSaved = fnSaveAirtableMeetings($arrResultData[$intFrCnt]);
													}
													
													$intFrCnt++;
												}
												else
												{
													continue;
												}
											}
											else
											{
												continue;
											}
										}
										//print("<pre>");
										//print_r($_SESSION['calendardata']);
									}
								//}
							}
							else
							{
								continue;
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
						
						fnUpdatesGstatus($strEmail);
						
						
						//echo "Send email to ".$strEmail.", saying credentials not working";
						//fnSendAccountExpirationMail($strEmail);
						fnSendAccountExpirationMail($strEmail);
					}
				}
			}
		}
	}
}

function fnGetLatestMeetsForUser($strEmail)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		//$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=50&view=".rawurlencode("account_not_processed");
		$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("Meetingsreverse");
		$url .= '&filterByFormula=('.rawurlencode("{calendaremail}='".$strEmail."'").')';	
		
		//echo $url;
		//exit; 
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
		if(!$result)
		{
			echo 'error:' . curl_error($ch);
			return false;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			//print("<pre>");
			//print_r($arrResponse);
			//exit;
			
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
	else
	{
		return false;
	}
}

function fnSendAccountExpirationMail($strEmail = "")
{
	global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;
	if($strEmail)
	{
		$to = $strEmail;
		$subject = "Google Calendar Access Expired";
		$strFrom = $strFromEmailAddress;
		
		$message = "Hello There,".'<br/><br/>';
		$message .= 'The Access to your calendar has been expired. <br/><br/>';
		$message .= 'Please login at following URL to revoke the access: <a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/'.$strClientFolderName.'/loadcals.php">Revoke Access</a> <br/><br/><br/>';
		$message .= 'Thanks';
		
		/* $headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		$headers .= 'From: johnrola36@gmail.com'."\r\n";							
		$retval = mail ($to,$subject,$message,$headers); */
		
		
		
		/* define('USERNAME','AKIAIBPZMF6PMB6XK2OA');
		define('PASSWORD','At6xulRB6J8VtWqlLWQZ5+NWas6G2GchiYVInzyeD2Xe');
		define('HOST', 'email-smtp.us-west-2.amazonaws.com');
		define('PORT', '587'); */
		
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

		if (PEAR::isError($result)) 
		{
		  echo("Email not sent. " .$result->getMessage() ."\n");
		  
		  return false;
		} 
		else 
		{
		  echo("Email sent!"."\n");
		  return true;
		}
	}
	else
	{
		return false;
	}
}

function fnUpdatesGstatus($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$strId = fnGetUsergAcc($strEmail);
		if($strId)
		{
			$base = $strAirtableBase;
			$table = 'gaccounts';
			$strApiKey = $strAirtableApiKey;
			$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strId;

			$authorization = "Authorization: Bearer ".$strApiKey;
			//$arrFields['fields']['Account'] = array($strId)$strName;
			$arrFields['fields']['status'] = "expired";
			
			//print("<pre>");
			//print_r($arrFields);
			//return;
			
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
			
			if(!$response)
			{
				echo curl_error($curl);
			}
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
	else
	{
		return false;
	}
}

function fnGetUsergAcc($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= "?filterByFormula=(user_email='".$strEmail."')";
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
					return $arrRecords[0]['id'];
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

function fnSaveAirtableMeetings($arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		//print("<pre>");
		//print_r($arrRecord);
		//return;
		
		
		$base = $strAirtableBase;
		//$table = 'meetings';
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		
		if($arrRecord['eventsummary'])
		{
			$arrFields['fields']['Meeting Name'] = $arrRecord['eventsummary'];
		}
		
		if($arrRecord['startdate'])
		{
			$arrFields['fields']['Meeting Date'] = date("m/d/Y",strtotime($arrRecord['startdate']));
		}
		
		if($arrRecord['calendarid'])
		{
			$arrFields['fields']['calendaremail'] = $arrRecord['calendarid'];
		}
		
		if($arrRecord['creayedbyname'])
		{
			$arrFields['fields']['Created By'] = $arrRecord['creayedbyname'];
		}
		
		if($arrRecord['ceatedbyemail'])
		{
			$arrFields['fields']['created_by_email'] = $arrRecord['ceatedbyemail'];
		}
		
		if($arrRecord['eventdescription'])
		{
			$arrFields['fields']['Description'] = $arrRecord['eventdescription'];
		}
		
		if($arrRecord['attendeesemail'])
		{
			$arrFields['fields']['Attendee Email(s)'] = $arrRecord['attendeesemail'];
		}
		
		if($arrRecord['meetingtype'])
		{
			$arrFields['fields']['Meeting'] = $arrRecord['meetingtype'];
		}
		
		if($arrRecord['processtime'])
		{
			$arrFields['fields']['meetingprocesstime'] = $arrRecord['processtime'];
		}
		
		if($arrRecord['eventid'])
		{
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


function fnCheckMeetingAlreadyPresent($arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		$base = $strAirtableBase;
		//$table = 'meetings';
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= "?filterByFormula=(gcal_mee_id='".$arrRecord['eventid']."')";
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
		echo "--".$result = curl_exec($ch);
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
		
?>