<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
require_once('config.php');
$arrCalData = $_SESSION['calendardata'];
$arrUData = $_SESSION['userdata'];
$_SESSION['currentclient'] = $strClient;
$_SESSION['currentclientfoldername'] = $strClientFolderName;
//unset($_SESSION['calendardata']);
//print("<pre>");
//print_r($_SESSION);
//exit;
if(is_array($arrUData) && (count($arrUData)>0))
{
	foreach($arrUData as $strUValKey => $strUVal)
	{
		$isRecPresent = fnCheckGcalAccountAlreadyPresent($strUVal);
		if(!$isRecPresent)
		{
			$record['utoken'] = $strUValKey;
			$record['uemail'] = $strUVal;
			$record['status'] = "active";
			$isRecSaved = fnSaveGcalAccount($record);
		}
		else
		{
			$record['utoken'] = $strUValKey;
			$record['uemail'] = $strUVal;
			$record['status'] = "active";
			fnUpdateUserTokenData($isRecPresent,$record);
		}
	}
	
	unset($_SESSION['calendardata']);
	unset($_SESSION['userdata']);
}

function fnUpdateUserTokenData($strRecId,$arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if($strRecId)
	{
		//$base = 'appTUmuDLBrSfWRrZ';
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['user_token'] = $arrRecord['utoken'];
		$arrFields['fields']['status'] = $arrRecord['status'];
		
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

function fnSaveGcalAccount($arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		
		$url = $strAirtableBaseEndpoint. $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrRecord['uemail'])
		{
			$arrFields['fields']['user_email'] = $arrRecord['uemail'];
		}
		
		if($arrRecord['utoken'])
		{
			$arrFields['fields']['user_token'] = $arrRecord['utoken'];
		}

		$arrFields['fields']['status'] = "active";
		$arrFields['fields']['sync_data'] = "1";
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


function fnCheckGcalAccountAlreadyPresent($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if($strEmail)
	{
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= "?filterByFormula=(user_email='".$strEmail."')";
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

$arrGcalUser = fnGetGcalUser();
//print("<pre>");
//print_r($arrGcalUser);


$arrSalesUser = fnGetSalesUser();

function fnGetGcalUser()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	$base = $strAirtableBase;
	$table = 'gaccounts';
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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
	</head>
	<style>
	.main {
		width:98%;
		margin:0 auto;
	}
	.main .heading {
		width:100%;
		border-bottom:1px solid #dae1e3;
		float:left;
		height: 40px;
		background-color:#f5f7f7;
	}
	.main .content {
		width:100%;
		float:left;
	}
	.content .box {
		width:23%;
		margin:1%;
		float:left;
	}
	.content .box .box-content {
		border: 1px solid #dae1e3;
		border-radius: 4px;
		width:100%;
		text-align:center;
		padding-bottom: 40px;
	}
	.content .box .box-content .title {
		background-color:#f5f7f7;
		text-align:center;
		color:#399ce2;
		text-transform:uppercase;
		padding: 26px 5px;
	}
	.content .box .box-content .title img {
		width:15px;
		margin-right:10px;
	}
	.content .box .box-content p {
		 padding: 10px;
		 color:#6b747b;
	}
	.content .calendor-btn {
		width:23%;
		margin:1%;
		float:left;
	}
	.content .calendor-btn .add-button {
		background-color: #f5f7f7;
		border: 1px solid #dae1e3;
		border-radius: 4px;
		font-size: 16px;
		padding: 10px 15px;
		cursor:pointer;
		color:#399ce2;
		margin-top:170px;
	}
	.content .calendor-btn .add-button:hover {
		background-color:#ededed;
		color:#399ce2;
	}
	.disable-btn {
		background-color:#f84e4e;
		border: 1px solid #f84e4e;
		color:#fff;
		border-radius:0px;
		padding:4px 8px;
		cursor:pointer;
	}
	.setting-btn {
		background-color:transparent;
		border: 1px solid #dae1e3;
		color:#6b747b;
		border-radius:0px;
		padding:4px 8px;
		cursor:pointer;
	}
	.disable-btn:hover {
		background-color:#f5f7f7;
		color:#6b747b;
	}
	.setting-btn:hover {
		background-color:#f5f7f7;
	}
	</style>
<body>
<iframe id="logoutframe" src="https://accounts.google.com/logout" style="display: none"></iframe>
<div class="main">
		<div class="heading"></div>
		<div class="content">
			<?php
				if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
				{
					//print("<pre>");
					//print_r($arrGcalUser);
					$intFrCnt = 0;
					foreach($arrGcalUser as $arrUser)
					{
						$arrUserDet = $arrUser['fields'];
						$intFrCnt++;
						$strUserName = "User".$intUser;
						$strUserEmail = $arrUserDet['user_email'];
						$strStatus = $arrUserDet['status'];
						$arrUserDetail = explode("@",$strUserEmail);
						?>
							<div class="box" id="<?php echo $intUser; ?>">
								<div class="box-content">
									<div class="title"><img src="img/callendor.png" >Calendar</div>
									<p><?php echo $arrUserDetail[0]; ?></p>
									<?php
										if($strStatus == "expired")
										{
											?>
												<a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/Attent/testcalnews.php"><button type="button" class="setting-btn">Activate</button></a>
											<?php
										}
									?>
									<!--<button type="button" class="disable-btn">Disable</button>
									<button type="button" class="setting-btn">Setting</button>-->
								</div>
							</div>
						<?php
					}
				}
			?>		
			<div class="calendor-btn"><a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/Attent/testcalnews.php"><button type="button" class="add-button">Add Calender</button></a></div>
		</div>
		
		<div class="heading"></div>
		<div class="content">
			<?php
				if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
				{
					//print("<pre>");
					//print_r($arrSalesUser);
					$intFrCnt = 0;
					foreach($arrSalesUser as $arrUser)
					{
						$strStatus = $arrUser['fields']['status'];
						$intFrCnt++;
						
						
						?>
							<div class="box" id="<?php echo $intUser; ?>">
								<div class="box-content">
									<div class="title">Salesforce</div>
									<?php
										if($strStatus == "expired")
										{
											?>
												<p>&nbsp;</p>
												<a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/resttest/oauth.php"><button type="button" class="setting-btn">Reconnect</button></a>
											<?php
										}
										else
										{
											?>
												<p>Account Connected</p>
											<?php
										}
									?>
									
									
									<!--<button type="button" class="disable-btn">Disable</button>
									<button type="button" class="setting-btn">Setting</button>-->
								</div>
							</div>
						<?php
					}
				}
				else
				{
					?>
						<div class="calendor-btn"><a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/resttest/oauth.php"><button type="button" class="add-button">Login with Salesforce </button></a></div>
					<?php
				}
			?>
		</div>
	</div>
</body>
</html>