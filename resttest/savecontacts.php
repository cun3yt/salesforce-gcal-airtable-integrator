<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once 'config.php';
$strCon = mysql_connect("localhost","rothrres_attent","attent");
if(!$strCon)
{
	echo mysql_error();
	exit;
}
else
{
	$strDb = mysql_select_db("rothrres_attent",$strCon);
	if(!$strDb)
	{
		echo mysql_error();
		exit;
	}
	else
	{
		//echo "HI";exit;
		
		$strUpdateQuery = "SELECT * FROM salesuser";
		$strUpdateQueryExe = mysql_query($strUpdateQuery);
		$intRows = mysql_num_rows($strUpdateQueryExe);
		$arrRowsData = mysql_fetch_array($strUpdateQueryExe);
		//print("<pre>");
		//print_r($arrRowsData);
		//exit;
		if(is_array($arrRowsData) && (count($arrRowsData)>0))
		{
			$instance_url = $arrRowsData['email'];
			$arrCompleteToken = json_decode($arrRowsData['salesuseraccesstoken'],true);
			//print("<pre>");
			//print_r($arrCompleteToken);
			
			//echo "---".$arrAccessTok = json_decode($arrRowsData['salesuseraccesstoken'],true);
			$access_token = $arrRowsData['user_token'];
		}
	}
	
	if($access_token)
	{
		$strNewAccessToken = save_contacts($instance_url, $access_token);
		
	}
}

function save_contacts($instance_url, $access_token) {
    $query = "SELECT LastName, Id, FirstName,Account.Id, Account.Name from Contact LIMIT 2000";
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
	
	if(is_array($response) && (count($response)>0))
	{
		if($response['totalSize'] > 0)
		{
			foreach((array) $response['records'] as $record)
			{
				$strName = $record['FirstName']." ".$record['LastName'];
				if($record['LastName'])
				{
					$strName = $record['FirstName']." ".$record['LastName'];
				}
				$strAccountSaveCheckQuery = "SELECT * FROM contact WHERE account_id = '".$record['Account']['Id']."' AND contact_s_id = '".$record['Id']."' AND contact_name = '".$strName."'";
				
				$strAccountSaveCheckQueryExe = mysql_query($strAccountSaveCheckQuery);
				$strAccountSaveCheckQueryExeRows = mysql_num_rows($strAccountSaveCheckQueryExe);
				
				if($strAccountSaveCheckQueryExeRows)
				{
					continue;
				}
				else
				{
					//echo "--".$record['AccountId'];
					
					
					echo "--".$strAccountSaveQuery = "INSERT INTO contact(contact_s_id,contact_name,account_id,account_name) VALUES('".$record['Id']."','".$strName."','".$record['Account']['Id']."','".$record['Account']['Name']."')";
					
					
					$strAccountSaveQueryExe = mysql_query($strAccountSaveQuery);
				}
			}
		}
	}
}
?>