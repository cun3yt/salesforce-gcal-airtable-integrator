<?
require_once '../../salesforce/config.php';

require_once '../config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");
Helpers::setDebugParam($isDebugActive);

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;

list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);

$integrations = Helpers::getIntegrations($customer, CustomerContactIntegration::SFDC);

if( count($integrations) <= 0 ) {
    trigger_error("SFDC Integration doesn't exist!");
    die;
}

/**
 * @var $integration CustomerContactIntegration
 */
foreach($integrations as $integration) {
    $contact = $integration->getCustomerContact();
    $data = json_decode($integration->getData());
    $tokenData = json_decode($data->tokendata);
    $emailAddress = $contact->getEmail();
    $newTokenData = Helpers::sfdcRefreshToken($tokenData->refresh_token, $emailAddress);

    if( !$newTokenData ) {
        trigger_error("New access token cannot be taken from SFDC, moving to the next contact integration", E_NOTICE);
        continue;
    }

    $tokenData = (object) array_merge((array)$tokenData, (array)$newTokenData);
    $data->tokendata = $tokenData;
    $data->accesstoken = $tokenData->access_token;

    $integration->setData(json_encode($data))
        ->save();

    echo "{$emailAddress} has renewed SFDC access token now.";
}
