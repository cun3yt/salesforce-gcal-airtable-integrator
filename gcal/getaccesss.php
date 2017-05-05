<?
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
require_once('./config.php');
require_once('../libraries/Helpers.php');

$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR,"https://www.googleapis.com/auth/contacts.readonly","https://www.googleapis.com/auth/userinfo.profile"));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
$client->setAccessType("offline");
$client->setIncludeGrantedScopes(true);
$client->setRedirectUri(Helpers::generateLink('gcal/getaccesss.php'));

if (!isset($_GET['code'])) {
	$client->setApprovalPrompt('force');
	$auth_url = $client->createAuthUrl();
	$filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
?>
	<script type="text/javascript">
		var strAuthUrl = '<?=$filtered_url?>';
		window.location = strAuthUrl;
	</script>
<? } else {
	$client->authenticate($_GET['code']);
	$_SESSION['access_token'] = $client->getAccessToken();
?>
	<script type="text/javascript">
		window.location = "<?=BASE_URL."/gcal/testcalnews.php"?>";
	</script>
<? }
