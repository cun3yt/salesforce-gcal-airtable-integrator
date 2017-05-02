<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once('config.php');
$strClientDomain = $strClientDomainName;
$arrGcalUser = fnGetProcessCalendar();

function fnGetProcessCalendar() {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

	$base = $strAirtableBase;
	$table = 'Meeting%20History';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=50&view=".rawurlencode("calendar_not_processed");
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

function fnGetUserName($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

	if(!$strEmail) {
	    return false;
    }

    $base = $strAirtableBase;
    $table = 'People';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= '?filterByFormula=('.rawurlencode("Email ='".$strEmail."'").")";

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
        $strName = $arrSUser[0]['fields']['Name'];
        return $strName;
    }

    return false;
}

if(is_array($arrGcalUser) && (count($arrGcalUser)>0)) {
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser) {
		$intFrCnt++;
		$strARecId = $arrUser['id'];
		$strEmail = $arrUser['fields']['calendaremail'];
		$strName = fnGetUserName($strEmail);

		if($strName) {
			echo "--".$boolNameUpdated = fnUpdateUserName($strName,$strARecId);
		} else {
			echo "--".$boolNameUpdated = fnUpdateUserName("",$strARecId);
		}
	}
}

function fnUpdateUserName($strName,$strRecId) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strRecId) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['Calendar'] = $strName;
    $arrFields['fields']['calendar_processed'] = "processed";

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
