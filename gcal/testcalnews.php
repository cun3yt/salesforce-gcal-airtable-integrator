<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
require_once('./config.php');
session_start();
$strCurrentClient = $_SESSION['currentclient'];
$strCurrentClientFolderName = $_SESSION['currentclientfoldername'];
$strToken = "";
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'].'/gcal/15FiveCal.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR,"https://www.googleapis.com/auth/contacts.readonly","https://www.googleapis.com/auth/userinfo.profile"));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setIncludeGrantedScopes(true);
if($strToken) {
	$strTok = $strToken;
} else {
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
				$_SESSION['userdata'] = $arrUserData;
			}	
		}
	}
	unset($_SESSION['access_token']);
	unset($_SESSION['userid']);
?>
	<script type="text/javascript">
		var strClientFolderName = '<?=$strCurrentClientFolderName?>';
		window.location.href = "<?=Helpers::generateLink("{$strCurrentClientFolderName}/loadcals.php")?>";
	</script>
<? } else {?>
	<script type="text/javascript">
		window.location = "<?=Helpers::generateLink("gcal/getaccesss.php")?>";
	</script>
<? }
exit;
