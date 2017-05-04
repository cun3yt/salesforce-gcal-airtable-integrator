<?php
/*
*
* This file is basically responsible to run on periodic basis and referesh the customers access token so that there is 
* uninterrupted connection to google calendar account for getting the data.
*
*/
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// setting and loading the dependencies for google api to work
require_once $_SERVER['DOCUMENT_ROOT'].'/gcal/vendor/autoload.php';
// we need to include config file so as to get set customer environment for refreshing customer google calendar account
require_once 'config.php';
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'].'/gcal/15FiveCal.json');
$client->addScope(array(Google_Service_Calendar::CALENDAR));
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
// Get the registerd google calendar oAuth access entry from customer's airtable base
$arrGcalUser = fnGetGcalUser();


//print("<pre>");
//print_r($arrGcalUser);
//exit;


// we get the google calendar access detail for customer from airtable base
// we check the access token if it is expired
// we than also pull the refresh token(whcih gets saved along during OAuth), we than use refresh token to generated new access token
// we than update the google calendar record with the updated access token.
if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	// getting into the pulled google calendar access record
	
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		//print("<pre>");
		//print_r($arrUser);
		//continue;
		
		$intFrCnt++;
		$arrUserDet = $arrUser['fields'];
		$strEmail = $arrUserDet['user_email'];
		$strARecId = $arrUser['id'];
		$arrTok = json_decode($arrUserDet['user_token'],true); // pulling the access token to check its status into array variable
		//print("<pre>");
		//print_r($arrTok);
		//exit;
		
		
		if(is_array($arrTok) && (count($arrTok)>0))
		{
			$client->setAccessToken($arrTok);
			$intTokenExpired = $client->isAccessTokenExpired(); // check if token is expired or not
			if($intTokenExpired)
			{
				$refreshToken = $client->getRefreshToken();
				$client->refreshToken($refreshToken);
				$newAccessToken = $client->getAccessToken(); // getting new token
				$newAccessToken['refresh_token'] = $refreshToken;
				
				// updating customer's google calendar record with updated access token
				fnUpdateAccessTokenSalesUser(json_encode($newAccessToken),$strARecId);
				
				
				
				//$fh = fopen("check.txt","w");
				//fwrite($fh,$strUpdateQuery);
				//fclose($fh);
			}
			else
			{
				// incase if token is not expired we update same old details again so that there is no change
				fnUpdateAccessTokenSalesUser(json_encode($arrTok),$strARecId);
				
				//$fh = fopen("check.txt","w");
				//fwrite($fh,$strUpdateQuery);
				//fclose($fh);
			}
		}
	}
}


/*
Function to connect to airtable base and get customers gcals OAuth acceess
*/
function fnGetGcalUser()
{
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
	if(!$result)
	{
		echo 'error:' . curl_error($ch);
		return false;
	}
	else
	{
		$arrResponse = json_decode($result,true);
		if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
		{
			$arrSUser = $arrResponse['records'];
			return $arrSUser;
			
		}
		else
		{
			return false;
		}
	}
}

/*
* Function to connect to airtable base and update google calendar access details
* It take 1 parameter as the updated token and 1 as the record if where token is to be updated
* On success it returns true otherwise false
*/

function fnUpdateAccessTokenSalesUser($strToken,$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strToken)
	{
		//echo "--".$strToken;
		//echo "--".$strRecId;
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
		if(is_array($jsonResponse) && (count($jsonResponse)>0))
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	else
	{
		return false;
	}

}

?>