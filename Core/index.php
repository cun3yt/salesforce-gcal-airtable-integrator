<?
error_reporting(E_ALL);

require_once('config.php');

Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;

$userDataArray = isset($_SESSION['userdata']) ? $_SESSION['userdata'] : null;

/**
 * @var $client Client
 */
list($client, $calendarUsers) = Helpers::loadClientData($strClientDomainName);

if(is_array($userDataArray) && (count($userDataArray)>0)) {
    foreach($userDataArray as $token => $emailAddress) {
        $gCalAccount = Helpers::getOAuthIfPresent($client, $emailAddress);

        $record['utoken'] = $token;
        $record['uemail'] = $emailAddress;
        $record['status'] = "active";

        if(!$gCalAccount) {
            Helpers::createAuthAccount($client, $emailAddress, $record['utoken'],
                ClientCalendarUserOAuth::GCAL);
		} else {
            Helpers::updateAuthenticationToken($gCalAccount, $token);
        }
	}
	
	unset($_SESSION['calendardata']);
	unset($_SESSION['userdata']);
}

$calendarAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::GCAL);

$arrSalesUser = array();
$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

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
                <?
                /**
                 * @var $auth ClientCalendarUserOAuth
                 */
                foreach($calendarAuths as $auth) { ?>
                    <div class="box">
                        <div class="box-content">
                            <div class="title"><img src="img/calendar.png">Calendar</div>
                            <? $contact = $auth->getClientCalendarUser(); ?>
                            <p><? echo $contact->getName(); ?></p>
                            <p><? echo $contact->getSurname(); ?></p>
                            <p><? echo $contact->getEmail(); ?></p>
                            <? if($auth->getStatus() == ClientCalendarUserOAuth::STATUS_EXPIRED) { ?>
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
                <? if(count($SFDCAuths) > 0) { ?>
                    <?
                    /**
                     * @var $auth ClientCalendarUserOAuth
                     */
                    foreach($SFDCAuths as $auth) { ?>
                        <div class="box">
                            <div class="box-content">
                                <div class="title">Salesforce</div>
                                <? if($auth->getStatus() == ClientCalendarUserOAuth::STATUS_EXPIRED) { ?>
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