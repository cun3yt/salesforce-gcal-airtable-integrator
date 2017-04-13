<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once 'config.php';
$arrSalesUser = fnGetSalesUser();
//print("<pre>");
//print_r($arrSalesUser);exit;
$access_token = "";
$instance_url = "";
$strRecordId = "";
if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
{
	$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
	if(is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail)>0))
	{
		$arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'],true);
		$access_token = $arrSDetail['access_token'];
		$instance_url = $arrSDetail['instance_url'];
		$strRecordId = $arrSalesUser[0]['id'];
	}
}

//echo "--".$access_token;
//echo "--".$instance_url;
//echo "--".$strRecordId;
//exit;
function fnGetSalesUser()
{
	$api_key = 'keyOhmYh5N0z83L5F';
	$base = 'appTUmuDLBrSfWRrZ';
	$table = 'salesuser';
	$strApiKey = "keyOhmYh5N0z83L5F";
	$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
	$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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

if($access_token)
{
	$strNewAccessToken = save_opportunities($instance_url, $access_token);
	
}

function save_opportunities($instance_url, $access_token) {
    $query = "SELECT Name, Id, Amount, CloseDate, StageName, Account.Id, Account.Name from Opportunity LIMIT 5";
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);
	
	//print("<pre>");
	//print_r($response);
	//exit;

    
    foreach ((array) $response['records'] as $record) 
	{
        //print("<pre>");
		//print_r($record);
		
		$isRecPresent = fnCheckAccountOppAlreadyPresent($record);
		if(!$isRecPresent)
		{
			$isRecSaved = fnSaveAirtableAccount($record);
		}
		
		
		/*$strAccountSaveCheckQuery = "SELECT * FROM accounts WHERE acount_s_id = '".$record['Id']."' AND account_name = '".$record['Name']."'";
		$strAccountSaveCheckQueryExe = mysql_query($strAccountSaveCheckQuery);
		$strAccountSaveCheckQueryExeRows = mysql_num_rows($strAccountSaveCheckQueryExe);
		
		if($strAccountSaveCheckQueryExeRows)
		{
			continue;
		}
		else
		{
			$strAccountSaveQuery = "INSERT INTO accounts(acount_s_id,account_name) VALUES('".$record['Id']."','".$record['Name']."')";
			$strAccountSaveQueryExe = mysql_query($strAccountSaveQuery);
		}*/
    }
}

function fnSaveAirtableAccount($arrRecord = array())
{
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		
		//print("<pre>");
		//print_r($arrRecord);
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'opportunities';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrRecord['Id'])
		{
			$arrFields['fields']['Opportunity ID'] = $arrRecord['Id'];
		}
		
		if($arrRecord['Name'])
		{
			$arrFields['fields']['Opportunity Name'] = $arrRecord['Name'];
		}
		if($arrRecord['Account']['Id'])
		{
			$arrFields['fields']['Acct ID'] = $arrRecord['Account']['Id'];
		}
		
		if($arrRecord['Amount'])
		{
			$arrFields['fields']['amount'] = $arrRecord['Amount'];
		}
		
		if($arrRecord['StageName'])
		{
			$arrFields['fields']['stage'] = $arrRecord['StageName'];
		}
		
		if($arrRecord['CloseDate'])
		{
			$arrFields['fields']['close_date'] = $arrRecord['CloseDate'];
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


function fnCheckAccountOppAlreadyPresent($arrRecord = array())
{
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		//echo 
		
		//print("<pre>");
		//print_r($arrRecord);
		$strId = $arrRecord['Id'];
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'opportunities';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url .= "?filterByFormula=";
		$url .= rawurlencode("({Opportunity ID}='".$strId."')");
		$authorization = "Authorization: Bearer ".$strApiKey;
		//echo "--".$url;
		//exit; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);

		//execute post
		echo "---".$result = curl_exec($ch);
		if(!$result)
		{
			echo 'error:' . curl_error($ch);
			
			return true;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			
			print("<pre>");
			print_r($arrResponse);
			
			if(is_array($arrResponse) && (count($arrResponse)>0))
			{
				$arrRecords = $arrResponse['records'];
				if(is_array($arrRecords) && (count($arrRecords)>0))
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
			  return true;	
			}
		}
	}
}

?>