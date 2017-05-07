<?
error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();

require_once('config.php');
require_once('../libraries/Helpers.php');

$userDataArray = $_SESSION['userdata'];
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
        <link rel="stylesheet" href="/static/main.css">
	</head>
    <body>
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
                                <a href="<?=Helpers::generateLink("gcal/add_new_gcal.php")?>"><button type="button" class="setting-btn">Activate</button></a>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
                <div class="calendor-btn">
                    <a href="<?=Helpers::generateLink("gcal/add_new_gcal.php")?>">
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
                <? } else { ?>
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