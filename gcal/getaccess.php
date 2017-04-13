<?php
session_start();
require_once 'vendor/autoload.php';

//echo "testgetaccess";
//exit;

$client = new Google_Client();
$client->setAuthConfig('clientkey.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setRedirectUri('http://localhost/gcal/getaccess.php');
if (!isset($_GET['code'])) {
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
	$_SESSION['access_token'] = $client->getAccessToken();
	?>
	<script type="text/javascript">
		window.location = "http://localhost/gcal/testcalnew.php";
	</script>
	<?php
}
?>