<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

require_once('config.php');
require_once('../libraries/Helpers.php');

$arrCalData = $_SESSION['calendardata'];
$arrUData = $_SESSION['userdata'];
$_SESSION['currentclient'] = $strClient;
$_SESSION['currentclientfoldername'] = $strClientFolderName;

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

if(is_array($arrUData) && (count($arrUData)>0)) {

    foreach($arrUData as $strUValKey => $strUVal) {
		$isRecPresent = fnCheckGcalAccountAlreadyPresent($strUVal);
        $record['utoken'] = $strUValKey;
        $record['uemail'] = $strUVal;
        $record['status'] = "active";

        if(!$isRecPresent) {
			fnSaveGcalAccount($record);
		}

        fnUpdateUserTokenData($isRecPresent,$record);
	}
	
	unset($_SESSION['calendardata']);
	unset($_SESSION['userdata']);
}

$calendarIntegrations = Helpers::getIntegrations($customer);
$arrSalesUser = Helpers::fnGetSalesUser();

function fnUpdateUserTokenData($strRecId,$arrRecord = array()) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strRecId) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;

    $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

    $authorization = "Authorization: Bearer ".$strApiKey;
    $arrFields['fields']['user_token'] = $arrRecord['utoken'];
    $arrFields['fields']['status'] = $arrRecord['status'];

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

    if(!$response) {
        echo curl_error($curl);
    }

    curl_close($curl);
    $jsonResponse =  json_decode($response,true);

    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function fnSaveGcalAccount($arrRecord = array()) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if( !(is_array($arrRecord) && (count($arrRecord)>0)) ) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;

    $url = $strAirtableBaseEndpoint. $base . '/' . $table;

    $authorization = "Authorization: Bearer ".$strApiKey;
    if($arrRecord['uemail']) {
        $arrFields['fields']['user_email'] = $arrRecord['uemail'];
    }

    if($arrRecord['utoken']) {
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

    return (is_array($jsonResponse) && (count($jsonResponse)>0));
}

function fnCheckGcalAccountAlreadyPresent($strEmail = "") {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

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

    if(!$result){
        return true;
    }

    $arrResponse = json_decode($result,true);

    if(is_array($arrResponse) && (count($arrResponse)>0)) {
        $arrRecords = $arrResponse['records'];
        if(is_array($arrRecords) && (count($arrRecords)>0)) {
            return $arrRecords[0]['id'];
        }
    }

    return true;
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
			<? foreach($calendarIntegrations as $integration) { ?>
                <div class="box">
                    <div class="box-content">
                        <div class="title"><img src="img/callendor.png">Calendar</div>
                        <? $contact = $integration->getCustomerContact(); ?>
                        <p><? echo $contact->getName(); ?></p>
                        <p><? echo $contact->getSurname(); ?></p>
                        <p><? echo $contact->getEmail(); ?></p>
                        <? if($integration->getStatus() == "expired") { ?>
                            <a href="<?=Helpers::generateLink("gcal/testcalnews.php")?>"><button type="button" class="setting-btn">Activate</button></a>
                        <? } ?>
                    </div>
                </div>
            <? } ?>
			<div class="calendor-btn"><a href="<?=Helpers::generateLink("gcal/testcalnews.php")?>"><button type="button" class="add-button">Add Calendar</button></a></div>
		</div>
		
		<div class="heading"></div>
		<div class="content">
			<?
				if(is_array($arrSalesUser) && (count($arrSalesUser)>0)) {
					$intFrCnt = 0;
					foreach($arrSalesUser as $integration) {
						$strStatus = $integration['fields']['status'];
						$intFrCnt++;
						?>
							<div class="box" id="<? echo $intUser; ?>">
								<div class="box-content">
									<div class="title">Salesforce</div>
									<?
										if($strStatus == "expired")
										{
											?>
												<p>&nbsp;</p>
                                                <a href="<?=Helpers::generateLink("resttest/oauth.php")?>">
												    <button type="button" class="setting-btn">Reconnect</button>
                                                </a>
											<?
										}
										else
										{
											?>
												<p>Account Connected</p>
											<?
										}
									?>
								</div>
							</div>
						<?
					}
				} else {
					?>
						<div class="calendor-btn">
                            <a href="<?=Helpers::generateLink("resttest/oauth.php")?>">
                                <button type="button" class="add-button">Login with Salesforce </button>
                            </a>
                        </div>
					<?
				}
			?>
		</div>
	</div>
</body>
</html>