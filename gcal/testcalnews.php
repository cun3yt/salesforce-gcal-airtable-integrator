<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
$strCurrentClient = $_SESSION['currentclient'];
$strCurrentClientFolderName = $_SESSION['currentclientfoldername'];
$strToken = "";
require_once '/var/www/html/gcal/vendor/autoload.php';

$client = new Google_Client();
//$client->setAuthConfig('calendar.json');
$client->setAuthConfig('/var/www/html/gcal/15FiveCal.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR,"https://www.googleapis.com/auth/contacts.readonly","https://www.googleapis.com/auth/userinfo.profile"));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setIncludeGrantedScopes(true);
if($strToken)
{
	$strTok = $strToken;
}
else
{
	$strTok = $_SESSION['access_token'];
}

if (is_array($strTok) && (count($strTok)>0)) {
	
	$client->setAccessToken($strTok);
	$service = new Google_Service_Calendar($client);
	$calendarList = $service->calendarList->listCalendarList();
	
	if(is_array($calendarList->getItems()) && (count($calendarList->getItems())>0))
	{		
		$strUserId = "";
		$intUsrCnt = 0;
		$arrUserData = array();
		foreach($calendarList->getItems() as $calendar)
		{
			if($calendar->id)
			{
				if($calendar->primary == "1")
				{
					$strUserId = $calendar->id;
					$arrUserData[json_encode($strTok)] = $calendar->id;
				}
				$calendarId = $calendar->id;
				/*$results = $service->events->listEvents($calendarId);
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
						$intUId = $strUserId;
						$arrResultData[$intFrCnt]['userid'] = $strUserId;
						
						$strStardDate = date("Y-m-d H:i:s",strtotime($eventdate->format("Y")."-".$eventdate->format("m")."-".$eventdate->format("d")));
						$arrResultData[$intFrCnt]['startdate'] = $strStardDate;
						
						$intFrCnt++;
					}
					//$_SESSION['calendardata'][] = $arrResultData;
					
				}*/
				$_SESSION['userdata'] = $arrUserData;
			}	
		}
	}
	unset($_SESSION['access_token']);
	unset($_SESSION['userid']);
	?>
	<script type="text/javascript">
		//window.close();
		//window.opener.location.reload();
		var strClientFolderName = '<?php echo $strCurrentClientFolderName; ?>';
		window.location.href = "http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/"+strClientFolderName+"/loadcals.php"
	</script>
	<?php
	exit;

} else {
	?>
	<script type="text/javascript">
		window.location = "http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/gcal/getaccesss.php";
	</script>
	<?php
	exit;
}

?>