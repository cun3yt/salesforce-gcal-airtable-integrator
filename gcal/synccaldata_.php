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
//echo "hi";exit;
$strCon = mysql_connect("localhost","rothrres_attent","attent");
if(!$strCon)
{
	echo mysql_error();
	exit;
}
else
{
	$strDb = mysql_select_db("rothrres_attent",$strCon);
	if(!$strDb)
	{
		echo mysql_error();
		exit;
	}
	else
	{
		$strGetUserQuery = "SELECT * FROM user WHERE sync_data = '1' AND user_token != 'NULL'";
		$strGetUserQueryExe = mysql_query($strGetUserQuery);
		$intRows = mysql_num_rows($strGetUserQueryExe);
		while($strGetUserQueryExeRoe = mysql_fetch_array($strGetUserQueryExe))
		{
			echo "--".$strEmail = $strGetUserQueryExeRoe['user_email'];
			$strToken = $strGetUserQueryExeRoe['user_token'];
			$arrTok = json_decode($strToken,true);
			//print("<pre>");
			//print_r($arrTok);
			if(is_array($arrTok) && (count($arrTok)>0))
			{
				$client->setAccessToken($arrTok);
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
								$results = $service->events->listEvents($calendarId, $optParams);
								
								//print("<pre>");
								//print_r($results);
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
										
										echo "<br>".$strEventId = $calendar->id."_".$event->id;
										
										
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
										
										$strCheckQuery = "SELECT * FROM user_meetings WHERE user_id = '".$intUId."' AND calendar_id='".$arrResultData[$intFrCnt]['calendarid']."' AND event_start_date='".$strStardDate."' AND event_summary='".addslashes($arrResultData[$intFrCnt]['eventsummary'])."'";
										//echo "--".$strCheckQuery;
										$strCheckQueryExe = mysql_query($strCheckQuery);
										$intRows = mysql_num_rows($strCheckQueryExe);
										if($intRows)
										{
											continue;
										}
										else
										{
											$strMysqlCalendarInserQuery = "INSERT INTO user_meetings(user_id,calendar_id,event_start_date,event_summary) VALUES('".$intUId."','".$arrResultData[$intFrCnt]['calendarid']."','".$strStardDate."','".addslashes($arrResultData[$intFrCnt]['eventsummary'])."')";
											//echo "++".$strMysqlCalendarInserQuery;
											
											$strMysqlCalendarInserQueryExe = mysql_query($strMysqlCalendarInserQuery);
											if(!$strMysqlCalendarInserQueryExe)
											{
												echo "--".$strMysqlCalendarInserQuery;
												echo "<br>".mysql_error();
												//exit;
											}
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
}


		
?>