<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
require_once 'config.php';
$client = new Google_Client();
$client->setAuthConfig($googleCalAPICredentialFile);
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

$arrGcalUser = fnGetGcalUser();

if(is_array($arrGcalUser) && (count($arrGcalUser)>0)) {
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser) {
		$intFrCnt++;
		$arrUserDet = $arrUser['fields'];
		$strEmail = $arrUserDet['user_email'];
		$strARecId = $arrUser['id'];
		$arrTok = json_decode($arrUserDet['user_token'],true);

        if( !(is_array($arrTok) && (count($arrTok)>0)) ) {
            continue;
        }

        $client->setAccessToken($arrTok);
        $intTokenExpired = $client->isAccessTokenExpired();

        if($intTokenExpired) {
            $refreshToken = $client->getRefreshToken();
            $client->refreshToken($refreshToken);
            $newAccessToken = $client->getAccessToken();
            $newAccessToken['refresh_token'] = $refreshToken;
            fnUpdateAccessTokenSalesUser(json_encode($newAccessToken),$strARecId);
        } else {
            fnUpdateAccessTokenSalesUser(json_encode($arrTok),$strARecId);
        }
	}
}

/**
 * Function to connect to airtable base and get customers gcals OAuth acceess
 *
 * @return bool
 */
function fnGetGcalUser() {
    global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= "?filterByFormula=(sync_data='1')";
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

/**
 * Function to connect to airtable base and update google calendar access details
 * It take 1 parameter as the updated token and 1 as the record if where token is to be updated
 * On success it returns true otherwise false
 *
 * @param $strToken
 * @param $strRecId
 * @return bool
 */
function fnUpdateAccessTokenSalesUser($strToken,$strRecId) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strToken) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['user_token'] = $strToken;

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
    curl_close($curl);
    $jsonResponse =  json_decode($response,true);
    print("<pre>");
    print_r($jsonResponse);
    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}
