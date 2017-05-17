<?
require_once '../../salesforce/config.php';

require_once '../config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

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
    $emailAddress = $contact->getEmail();
    $newAccessToken = Helpers::sfdcRefreshToken($data->refresh_token, $emailAddress);

    if( !$newAccessToken ) {
        trigger_error("New access token cannot be taken from SFDC, moving to the next contact integration", E_NOTICE);
        continue;
    }

    $data = array_merge($data, $newAccessToken);
    $integration->setData($data)
        ->save();

    echo "{$emailAddress} has renewed SFDC access token now.";
}
