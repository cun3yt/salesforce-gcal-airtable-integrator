<?php
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
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
		$arrCalData = $_SESSION['calendardata'];
		$arrUData = $_SESSION['userdata'];
		//unset($_SESSION['calendardata']);
		//print("<pre>");
		//print_r($_SESSION);
		//exit;
		if(is_array($arrUData) && (count($arrUData)>0))
		{
			foreach($arrUData as $strUValKey => $strUVal)
			{
				$strCheckUQuery = "SELECT * FROM user WHERE user_email = '".$strUVal."'";
				$strCheckUQueryExe = mysql_query($strCheckUQuery);
				
				$intURows = mysql_num_rows($strCheckUQueryExe);
				if($intURows)
				{
					continue;
				}
				else
				{
					$strCheckUInserQuery = "INSERT INTO user(user_email,user_token) VALUES('".$strUVal."','".$strUValKey."')";
					$strCheckInsUQueryExe = mysql_query($strCheckUInserQuery);
					if(!$strCheckInsUQueryExe)
					{
						//echo mysql_error();
					}
				}
			}
		}
		if(is_array($arrCalData) && (count($arrCalData)>0))
		{
			//print("<pre>");
			//print_r($arrCalData);
			
			foreach($arrCalData as $arrCalenders)
			{
				
				if(is_array($arrCalenders) && (count($arrCalenders)>0))
				{
					foreach($arrCalenders as $arrCalender)
					{
						$strMonth = $arrCalender['eventmonth'];
						$strDay = $arrCalender['eventdate'];
						$strSummary = $arrCalender['eventsummary'];
						$strStardDate = $arrCalender['startdate'];
						$intUId = $arrCalender['userid'];
						$intCald = $arrCalender['calendarid'];
						
						
						$strCheckQuery = "SELECT * FROM user_meetings WHERE user_id = '".$intUId."' AND calendar_id='".$intCald."' AND event_start_date='".$strStardDate."' AND event_summary='".addslashes($strSummary)."'";
						$strCheckQueryExe = mysql_query($strCheckQuery);
						$intRows = mysql_num_rows($strCheckQueryExe);
						if($intRows)
						{
							continue;
						}
						else
						{
							$strMysqlCalendarInserQuery = "INSERT INTO user_meetings(user_id,calendar_id,event_start_date,event_summary) VALUES('".$intUId."','".$intCald."','".$strStardDate."','".addslashes($strSummary)."')";
							$strMysqlCalendarInserQueryExe = mysql_query($strMysqlCalendarInserQuery);
							if(!$strMysqlCalendarInserQueryExe)
							{
								echo "--".$strMysqlCalendarInserQuery;
								echo "<br>".mysql_error();
								//exit;
							}
						}
						
					}
				}
			}
		}
		unset($_SESSION['calendardata']);
		unset($_SESSION['userdata']);
	}
}

$strGetUserQuery = "SELECT * FROM user";
$strGetUserQueryExe = mysql_query($strGetUserQuery);
$intRows = mysql_num_rows($strGetUserQueryExe);


$arrSalesUser = fnGetSalesUser();


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

/*$strGetSalesUserQuery = "SELECT * FROM salesuser";
$strGetSalesUserQueryExe = mysql_query($strGetSalesUserQuery);
$intSalesUserRow = mysql_num_rows($strGetSalesUserQueryExe);*/
		
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
				if($intRows > 0)
				{
					$intUser = 0;
					while($strGetUserQueryExeRow = mysql_fetch_array($strGetUserQueryExe))
					{
						$intUser++;
						$strUserId = $strGetUserQueryExeRow['user_id'];
						$strUserName = "User".$intUser;
						$strUserEmail = $strGetUserQueryExeRow['user_email'];
						$arrUserDetail = explode("@",$strUserEmail);
						?>
							<div class="box" id="<?php echo $intUser; ?>">
								<div class="box-content">
									<div class="title"><img src="img/callendor.png" >Calendar</div>
									<p><?php echo $arrUserDetail[0]; ?></p>
									<button type="button" class="disable-btn">Disable</button>
									<button type="button" class="setting-btn">Setting</button>
								</div>
							</div>
						<?php
					}
				}
			?>
			<div class="calendor-btn"><a href="http://www.rothrsolutions.com/gcal/testcalnews.php"><button type="button" class="add-button">Add Calender</button></a></div>
		</div>
		
		<div class="heading"></div>
		<div class="content">
			<?php
				if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
				{
					print("<pre>");
					print_r($arrSalesUser);
					$intFrCnt = 0;
					foreach($arrSalesUser as $arrUser)
					{
						$intFrCnt++;
						?>
							<div class="box" id="<?php echo $intUser; ?>">
								<div class="box-content">
									<div class="title">Salesforce</div>
									<p>Account Connected</p>
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
						<div class="calendor-btn"><a href="http://www.rothrsolutions.com/gcal/testcalnews.php"><button type="button" class="add-button">Login with Salesforce </button></a></div>
					<?php
				}
			?>
		</div>
	</div>
</body>
</html>