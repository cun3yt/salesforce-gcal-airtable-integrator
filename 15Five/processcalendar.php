<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once('config.php');
$strClientDomain = $strClientDomainName;
$arrGcalUser = fnGetProcessCalendar();
//print("<pre>");
//print_r($arrGcalUser);

function fnGetProcessCalendar()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

	$base = $strAirtableBase;
	$table = 'Meeting%20History';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=50&view=".rawurlencode("calendar_not_processed");
	//$url .= '?filterByFormula=('.rawurlencode("calendar_processed=''").')';
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

function fnGetUserName($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{

		$base = $strAirtableBase;
		$table = 'People';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("Email ='".$strEmail."'").")";
		//echo "---".$url; exit;
		
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
				//echo "---".$strEmail;
				
				$arrSUser = $arrResponse['records'];
				//print("<pre>");
				//print_r($arrSUser);
				//exit;
				
				$strName = $arrSUser[0]['fields']['Name'];
				//exit;
				return $strName;
				
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

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
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
		$strARecId = $arrUser['id'];
		$strEmail = $arrUser['fields']['calendaremail'];
		$strName = fnGetUserName($strEmail);
		
		
		if($strName)
		{
			echo "--".$boolNameUpdated = fnUpdateUserName($strName,$strARecId);
		}
		else
		{
			
			echo "--".$boolNameUpdated = fnUpdateUserName("",$strARecId);
			//continue;
		}
	}
}

function fnUpdateUserName($strName,$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
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