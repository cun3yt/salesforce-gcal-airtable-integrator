<?
require_once '../../resttest/config.php';

require_once '../config.php';
require_once('../libraries/Helpers.php');

$arrSalesUser = Helpers::fnGetSalesUser();
$access_token = "";
$instance_url = "";
$strRecordId = "";
$strEm = "";
if(is_array($arrSalesUser) && (count($arrSalesUser)>0)) {
	$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
	
	if(is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail)>0)) {
		$arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'],true);
		$access_token = $arrSDetail['refresh_token'];
		$instance_url = $arrSDetail['instance_url'];
		$strRecordId = $arrSalesUser[0]['id'];
		$strEm = $arrSalesTokenDetail['email'];
	}
}

if($access_token) {
	$strNewAccessToken = refreshtoken($instance_url, $access_token,$strEm);

	if($strNewAccessToken) {
		fnUpdateAccessTokenSalesUser($instance_url,$strNewAccessToken,$strRecordId);
	}
}

function fnUpdateAccessTokenSalesUser($instance_url, $access_token,$strRecId) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if( !$access_token ) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'salesuser';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['user_token'] = $access_token;

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

    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function refreshtoken($instance_url, $access_token,$strEmail = "") {
	$url = "https://login.salesforce.com/services/oauth2/token";
	$strPostVariables = "grant_type=refresh_token&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&refresh_token=".$access_token;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $strPostVariables);
    curl_setopt($curl, CURLOPT_HTTPHEADER,array("Accept: application/json"));

    echo "---".$json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);
	print("<pre>");
	print_r($response);
	//exit;
	
	if(is_array($response) && (count($response)>0)) {
		if(isset($response['error'])) {
			if($response['error_description'] == "expired access/refresh token") {
				// send email to admin for sales force account token access resetup
				fnUpdatesSGstatus($strEmail);
				fnUpdateSalesAmidminForTokenExpiry($strEmail);
			}
		} else {
			if($response['access_token']) {
				return $response['access_token'];
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

function fnUpdatesSGstatus($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $strId = fnGetUsergAcc($strEmail);

    if(!$strId) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'salesuser';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['status'] = "expired";

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

    if(!$response) {
        echo curl_error($curl);
    }

    curl_close($curl);
    $jsonResponse =  json_decode($response,true);

    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function fnGetUsergAcc($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'salesuser';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= "?filterByFormula=(email='".$strEmail."')";
    $authorization = "Authorization: Bearer ".$strApiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch,CURLOPT_URL, $url);

    //execute post
    $result = curl_exec($ch);

    if(!$result) {
        return true;
    }

    $arrResponse = json_decode($result,true);

    if(is_array($arrResponse) && (count($arrResponse)>0)) {
        $arrRecords = $arrResponse['records'];

        if(is_array($arrRecords) && (count($arrRecords)>0)) {
            return $arrRecords[0]['id'];
        }

        return false;
    }

    return true;
}

function fnUpdateSalesAmidminForTokenExpiry($strEmail) {
	global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

    if(!$strEmail) {
        return false;
    }

    $to = $strEmail;
    $subject = "Salesforce Oauth Access Expired";
    $strFrom = $strFromEmailAddress;

    $message = "Hello There,".'<br/><br/>';
    $message .= 'The Access to your salesforce account has been expired. <br/><br/>';

    $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

    $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
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

    if (PEAR::isError($result)) {
        echo("Email not sent. " .$result->getMessage() ."\n");
        return false;
    }

    echo("Email sent!"."\n");
    return true;
}
