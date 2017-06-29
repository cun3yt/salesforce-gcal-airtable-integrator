<?
require_once("${_SERVER['DOCUMENT_ROOT']}/salesforce/config.php");
require_once("${_SERVER['DOCUMENT_ROOT']}/Core/config.php");

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");
Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;

$client = Helpers::loadClientData($strClientDomainName);

$SFDCAuths = Helpers::getAuthentications($client, ClientCalendarUserOAuth::SFDC);

if( count($SFDCAuths) <= 0 ) {
    trigger_error("No SFDC authentication exists!");
    die;
}

/**
 * @var $auth ClientCalendarUserOAuth
 */
foreach($SFDCAuths as $auth) {
    $calendarUser = $auth->getClientCalendarUser();
    $data = json_decode($auth->getData());
    $tokenData = $data->tokendata;
    $emailAddress = $calendarUser->getEmail();

    $newTokenData = Helpers::sfdcRefreshToken($tokenData->refresh_token, $emailAddress);

    if( !$newTokenData ) {
        trigger_error("New access token cannot be taken from SFDC, moving to the next user authentication",
            E_USER_NOTICE);
        continue;
    }

    $tokenData = (object) array_merge((array)$tokenData, (array)$newTokenData);
    $data->tokendata = $tokenData;
    $data->accesstoken = $tokenData->access_token;

    $auth->setData(json_encode($data))
        ->save();

    echo "{$emailAddress} has renewed SFDC access token now.";
}
