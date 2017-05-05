<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

require_once('config.php');
require_once('../libraries/Helpers.php');

$userDataArray = $_SESSION['userdata'];
$_SESSION['currentclient'] = $strClient;
$_SESSION['currentclientfoldername'] = $strClientFolderName;

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

if(is_array($userDataArray) && (count($userDataArray)>0)) {
    foreach($userDataArray as $token => $emailAddress) {
        $gCalAccount = Helpers::getGCalAccountIfPresent($customer, $emailAddress);

        $record['utoken'] = $token;
        $record['uemail'] = $emailAddress;
        $record['status'] = "active";

        if(!$gCalAccount) {
            Helpers::createGCalAccount($customer, $emailAddress, $record);
		} else {
            Helpers::updateGCalAccountUserToken($gCalAccount, $token);
        }
	}
	
	unset($_SESSION['calendardata']);
	unset($_SESSION['userdata']);
}

$calendarIntegrations = Helpers::getIntegrations($customer);

$arrSalesUser = array();
$SFDCIntegrations = Helpers::getIntegrations($customer, \DataModels\DataModels\CustomerContactIntegration::SFDC);

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
			<div class="calendor-btn">
                <a href="<?=Helpers::generateLink("gcal/testcalnews.php")?>">
                    <button type="button" class="add-button">Add Calendar</button>
                </a>
            </div>
		</div>
		
		<div class="heading"></div>
		<div class="content">

            <? if(count($SFDCIntegrations) > 0) { ?>
                <? foreach($SFDCIntegrations as $integration) { ?>
                    <div class="box">
                        <div class="box-content">
                            <div class="title">Salesforce</div>
                            <? if($integration->getStatus() == "expired") { ?>
                                <p>&nbsp;</p>
                                <a href="<?=Helpers::generateLink("salesforce/oauth.php")?>">
                                    <button type="button" class="setting-btn">Reconnect</button>
                                </a>
                            <? } else { ?>
                                <p>Account Connected</p>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
            <? } else {?>
                <div class="calendor-btn">
                    <a href="<?=Helpers::generateLink("salesforce/oauth.php")?>">
                        <button type="button" class="add-button">Connect Your SFDC Account</button>
                    </a>
                </div>
            <? } ?>
		</div>
	</div>
</body>
</html>