<?php
/*
*
* This file is basically responsible to run on periodic basis and referesh the customers access token so that there is 
* uninterrupted connection to customer sf account.
*
*/

// setting and loading the dependencies for google api to work
require_once '../../resttest/config.php';
// we need to include config file so as to get set customer environment for refreshing customer google calendar account
require_once '../config.php';
// Get the registerd salesforce oAuth access entry from customer's airtable base
$arrSalesUser = fnGetSalesUser();
//print("<pre>");
//print_r($arrSalesUser);exit;

// we connect to airtable and salesuser table
// get the access detail
// refersh the token, during the process if token is invalid we updates sales access record as expired
// and send notification mail to sales user for revoking access





// We declare some global salesforce access token variables that will be needed to fetching attendees contact data.
$access_token = "";
$instance_url = "";
$strRecordId = "";
$strEm = "";
if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
{
	$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
	
	print("<pre>");
	print_r($arrSalesTokenDetail);
	
	if(is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail)>0))
	{
		$arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'],true);
		$access_token = $arrSDetail['refresh_token'];
		$instance_url = $arrSDetail['instance_url'];
		$strRecordId = $arrSalesUser[0]['id'];
		$strEm = $arrSalesTokenDetail['email'];
	}
}




// check if access token is set
if($access_token)
{
	
	// initiate refresh token process
	$strNewAccessToken = refreshtoken($instance_url, $access_token,$strEm);
	
	// check to see if we have new access token
	if($strNewAccessToken)
	{
		//echo "--".$instance_url;
		//echo "--".$access_token;
		//echo "--".$strRecordId;exit;
		
		// update airtable base for new access token
		fnUpdateAccessTokenSalesUser($instance_url,$strNewAccessToken,$strRecordId);
		
	}
}

/*
Function to connect to airtable base and get customers salesforce OAuth acceess
*/

function fnGetSalesUser()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

	$base = $strAirtableBase;
	$table = 'salesuser';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table;
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
*Function to connect to airtable base and update sales force access details with new token
*It accepts new token as parameter and sales access record id where new token is to be updated
*/

function fnUpdateAccessTokenSalesUser($instance_url, $access_token,$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($access_token)
	{
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
		//print("<pre>");
		//print_r($jsonResponse);
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

/*
* Function to connect to salesforce and generate new access token
* It takes current refresh token as parameter, work on it and on success return new access token on failure it returns false 
*/

function refreshtoken($instance_url, $access_token,$strEmail = "") {
    
	//echo "HI";exit;
	
	//echo "--".$instance_url;
	//echo "--".$access_token;
	//exit;
	$url = "https://login.salesforce.com/services/oauth2/token";
	$strPostVariables = "grant_type=refresh_token&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&refresh_token=".$access_token;
	
	//$strPostVariables = "grant_type=refresh_token&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&refresh_token=asdad";
	
	//echo $url;exit;
	
	//echo $strPostVariables;exit;

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
	
	if(is_array($response) && (count($response)>0))
	{
		if(isset($response['error']))
		{
			if($response['error_description'] == "expired access/refresh token")
			{
				// send email to admin for sales force account token access resetup
				fnUpdatesSGstatus($strEmail);
				
				fnUpdateSalesAmidminForTokenExpiry($strEmail);
			}
		}
		else
		{
			if($response['access_token'])
			{
				return $response['access_token'];
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


/*
* Function to connect to update customer salesforce access status
* It takes parameter as email address of customer to update the record with status
* On success it returns true other wise false
*/

function fnUpdatesSGstatus($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$strId = fnGetUsergAcc($strEmail);
		if($strId)
		{
			$base = $strAirtableBase;
			$table = 'salesuser';
			$strApiKey = $strAirtableApiKey;
			$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strId;

			$authorization = "Authorization: Bearer ".$strApiKey;
			//$arrFields['fields']['Account'] = array($strId)$strName;
			$arrFields['fields']['status'] = "expired";
			
			//print("<pre>");
			//print_r($arrFields);
			//return;
			
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
			
			if(!$response)
			{
				echo curl_error($curl);
			}
			curl_close($curl);
			$jsonResponse =  json_decode($response,true);
			//print("<pre>");
			//print_r($jsonResponse);
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
	else
	{
		return false;
	}
}


/*
* Function to connect airtabale and get access of customer's sales access record
* It takes parameter as customer email address
* On success it returns true other wise false
*/
function fnGetUsergAcc($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$base = $strAirtableBase;
		$table = 'salesuser';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= "?filterByFormula=(email='".$strEmail."')";
		$authorization = "Authorization: Bearer ".$strApiKey;
		//echo $url;exit; 
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
			return true;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			if(is_array($arrResponse) && (count($arrResponse)>0))
			{
				$arrRecords = $arrResponse['records'];
				if(is_array($arrRecords) && (count($arrRecords)>0))
				{
					return $arrRecords[0]['id'];
				}
				else
				{
					return false;
				}
			}
			else
			{
			  return true;	
			}
		}
	}
}

/*
Function to send notification mail about access token expiry and how to revoke the access
*/

function fnUpdateSalesAmidminForTokenExpiry($strEmail)
{
	global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;
	if($strEmail)
	{
		$to = $strEmail;
		$subject = "Salesforce Oauth Access Expired";
		$strFrom = $strFromEmailAddress;
		
		$message = "Hello There,".'<br/><br/>';
		$message .= 'The Access to your salesforce account has been expired. <br/><br/>';
		$message .= 'Please login at following URL to revoke the access: <a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/'.$strClientFolderName.'/loadcals.php">Revoke Access</a> <br/><br/><br/>';
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

		if (PEAR::isError($result)) 
		{
		  echo("Email not sent. " .$result->getMessage() ."\n");
		  
		  return false;
		} 
		else 
		{
		  echo("Email sent!"."\n");
		  return true;
		}
	}
	else
	{
		return false;
	}
}

?>