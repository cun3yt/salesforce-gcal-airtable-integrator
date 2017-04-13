<?php
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('clientkey.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

$strTok = $_SESSION['access_token'];
//$strTok = "";

if ($strTok) {
	$client->setAccessToken($strTok);
	$service = new Google_Service_Calendar($client);
	
	$calendarList = $service->calendarList->listCalendarList();
	
	//print("<pre>");
	//print_r($calendarList);
	//exit;

	/*$calendarId = 'primary';

	$results = $service->events->listEvents($calendarId);*/
	//print("<pre>");
	
	if(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0))
	{
		
		foreach($calendarList->getItems() as $calendar)
		{
			//echo "--".$calendar->id;
			//exit;
			
			if($calendar->id)
			{
				echo "hi";
				$calendarId = $calendar->id;
				$results = $service->events->listEvents($calendarId);
				
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
						
						$arrResultData[$intFrCnt]['eventdate'] = $newday;
						$arrResultData[$intFrCnt]['eventmonth'] = $newmonth;
						$arrResultData[$intFrCnt]['eventsummary'] = $event->getSummary();
						$arrResultData[$intFrCnt]['calendarid'] = $calendar->id;
						
						$intFrCnt++;
					}
					$_SESSION['calendardata'][] = $arrResultData;
					//print("<pre>");
					//print_r($_SESSION['calendardata']);
				}
			}	
		}
		//print("<pre>");
		//print_r($arrResultData);
		
		print("<pre>");
		print_r($_SESSION['calendardata']);
		//exit;
		
		//array_push($_SESSION['calendardata'],$arrResultData);
	}
	unset($_SESSION['access_token']);
	?>
	<script type="text/javascript">
		//window.close();
		window.opener.location.reload();
	</script>
	<?php
	exit;

} else {
	?>
	<script type="text/javascript">
		window.location = "http://localhost/gcal/getaccess.php";
	</script>
	<?php
	exit;
}

?>