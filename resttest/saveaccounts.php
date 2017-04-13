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
		$strNewAccessToken = save_accounts($instance_url, $access_token);
		
	}
}

function save_accounts($instance_url, $access_token) {
    $query = "SELECT Name, Id from Account LIMIT 5";
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    print("<pre>");
	print_r($response);
	
    /*foreach ((array) $response['records'] as $record) 
	{
        
		$strAccountSaveCheckQuery = "SELECT * FROM accounts WHERE acount_s_id = '".$record['Id']."' AND account_name = '".$record['Name']."'";
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
		}
    }*/
}

?>