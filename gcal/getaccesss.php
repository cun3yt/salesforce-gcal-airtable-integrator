<?php
session_start();
require_once 'vendor/autoload.php';

//echo "testgetaccess";
//exit;

$client = new Google_Client();
$client->setAuthConfig('15FiveCal.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR,"https://www.googleapis.com/auth/contacts.readonly","https://www.googleapis.com/auth/userinfo.profile"));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");
$client->setIncludeGrantedScopes(true);
$client->setRedirectUri('http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/gcal/getaccesss.php');
if (!isset($_GET['code'])) {
	$client->setApprovalPrompt('force');
	$auth_url = $client->createAuthUrl();
	$filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
	
	?>
	<script type="text/javascript">
		var strAuthUrl = '<?php echo $filtered_url; ?>';
		window.location = strAuthUrl;
	</script>
	<?php
} else {
	$client->authenticate($_GET['code']);
	//echo "--".$_GET['code'];
	//echo "<pre>";
	//print_r($client->getAccessToken());
	
	
	$_SESSION['access_token'] = $client->getAccessToken();
	//exit;
	?>
	<script type="text/javascript">
		window.location = "http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/gcal/testcalnews.php";
	</script>
	<?php
}
?>