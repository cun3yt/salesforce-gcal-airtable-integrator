<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once '../config.php';
$strCurrentClient = $_SESSION['currentclient'];
$strCurrentClientFolderName = $_SESSION['currentclientfoldername'];
//echo "HI";exit;
if(is_array($_SESSION['arraccess']) && (count($_SESSION['arraccess'])>0))
{
	$arrTokendData = $_SESSION['arraccess'];
	$access_token = $arrTokendData['access_token'];
	$instance_url = $arrTokendData['instance_url'];
	
	$arrSales['tokendata'] = json_encode($_SESSION['arraccess']);
	$arrSales['accesstoken'] = $arrTokendData['access_token'];
	$arrSales['instanceurl'] = $arrTokendData['instance_url'];
	$strUserUrl = $arrTokendData['id'];
	$arrUserUrlDetail = explode("/",$strUserUrl);
	$arrUDetail = fnGetUserDetailFromSF($strUserUrl,$arrSales['accesstoken']);
	//print("<pre>");
	//print_r($arrUDetail);
	//exit;
	if(is_array($arrUDetail) && (count($arrUDetail)>0))
	{
		$arrSales['email'] = $arrUDetail['email'];
	}
	
	//$arrSales['userid'] = substr($arrUserUrlDetail[count($arrUserUrlDetail)-1],0,-3);
	$arrSales['userid'] = $arrUserUrlDetail[count($arrUserUrlDetail)-1];
	
	$arrUserDetail = fnCheckAlreadySavedSalesUser($arrSales['email']);
	if(is_array($arrUserDetail) && (count($arrUserDetail)>0))
	{
		$isUpdated = fnUpdateSalesUser($arrUserDetail[0]['id'],$arrSales);
		if($isUpdated)
		{
			header('Location: http://ec2-34-208-42-244.us-west-2.compute.amazonaws.com/'.$strCurrentClientFolderName.'/loadcals.php');
		}
		else
		{
			echo "Something went wrong please try again";
		}
	}
	else
	{
		$IsSaved = fnSaveSalesUserToAT($arrSales);
		if($IsSaved)
		{
			header('Location: http://ec2-34-208-42-244.us-west-2.compute.amazonaws.com/'.$strCurrentClientFolderName.'/loadcals.php');
		}
		else
		{
			echo "Something went wrong please try again";
		}
	}
	
	
}

function fnCheckAlreadySavedSalesUser($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$base = $strAirtableBase;
		$table = 'salesuser';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{email}='".$strEmail."'").')';	
		//echo "--".$url; 
		//continue;
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
			//print("<pre>");
			//print_r($arrResponse);
			//exit;
			
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
	else
	{
		return false;
	}
}

function fnGetUserDetailFromSF($strUserUrl, $access_token)
{
	if($strUserUrl)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		//$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue from Account WHERE Website LIKE '%".$strAccDomain."' LIMIT 1";
		 
		 $url = $strUserUrl;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: OAuth $access_token"));

		$json_response = curl_exec($curl);
		if(!$json_response)
		{
			echo "--error---".curl_error($curl);
		}
		curl_close($curl);

		
		$response = json_decode($json_response, true);
		
		
		
		return $response;
	}
	else
	{
		return false;
	}
}

function fnSaveSalesUserToAT($arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		$base = $strAirtableBase;
		$table = 'salesuser';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrRecord['accesstoken'])
		{
			$arrFields['fields']['user_token'] = $arrRecord['accesstoken'];
		}
		
		if($arrRecord['tokendata'])
		{
			$arrFields['fields']['salesuseraccesstoken'] = $arrRecord['tokendata'];
		}
		$arrFields['fields']['status'] = "active";
		if($arrRecord['userid'])
		{
			$arrFields['fields']['userid'] = $arrRecord['userid'];
		}
		
		if($arrRecord['email'])
		{
			$arrFields['fields']['email'] = $arrRecord['email'];
		}
		$srtF = json_encode($arrFields);
		$curl = curl_init($url);
		// Accept any server (peer) certificate on dev envs
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
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

function fnUpdateSalesUser($strRecId,$arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		//echo "--".$strRecId
		//print("<pre>");
		//print_r($arrSalesUser);
		
		$base = $strAirtableBase;
		$table = 'salesuser';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrRecord['accesstoken'])
		{
			$arrFields['fields']['user_token'] = $arrRecord['accesstoken'];
		}
		
		if($arrRecord['tokendata'])
		{
			$arrFields['fields']['salesuseraccesstoken'] = $arrRecord['tokendata'];
		}
		$arrFields['fields']['status'] = "active";
		if($arrRecord['userid'])
		{
			$arrFields['fields']['userid'] = $arrRecord['userid'];
		}
		
		if($arrRecord['email'])
		{
			$arrFields['fields']['email'] = $arrRecord['email'];
		}
		
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

?>