<?
require_once("../global-config.php");

use DataModels\DataModels\CustomerQuery as CustomerQuery;
use DataModels\DataModels\Customer as Customer;
use DataModels\DataModels\CustomerContactIntegrationQuery as CustomerContactIntegrationQuery;
use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;
use DataModels\DataModels\CustomerContactQuery as CustomerContactQuery;
use DataModels\DataModels\CustomerContact as CustomerContact;

class Helpers {
    /**
     * @param $emailDomain
     * @return array
     * @throws Exception
     */
    static function loadCustomerData($emailDomain) {
        $q = new CustomerQuery();
        $customerSet = $q->findByEmailDomain($emailDomain);

        if($customerSet->count() <= 0) {
            throw new Exception("Customer Data is not found for {$emailDomain}");
        }

        $customer = $customerSet[0];
        $contacts = $customer->getCustomerContacts();

        return [$customer, $contacts];
    }

    /**
     * Replaces "fnGetSalesUser"
     *
     * @param Customer $customer
     * @param string $integrationType
     * @return array
     */
    static function getIntegrations(Customer $customer,
                                    $integrationType = CustomerContactIntegration::GCAL) {
        $contacts = $customer->getCustomerContacts();
        $integrations = array();

        foreach($contacts as $contact) {
            $q = new CustomerContactIntegrationQuery();
            $collection = $q->filterByCustomerContact($contact)->filterByType($integrationType)->find();
            $integrations = array_merge($integrations, $collection->getArrayCopy());
        }
        return $integrations;
    }

    /**
     * @param $path
     * @return string
     */
    static function generateLink($path) {
        return BASE_URL . "{$path}";
    }

    /**
     * Creating a new GCal Account
     *
     * This replaces "fnSaveGcalAccount"
     *
     * @param Customer $customer
     * @param $recordArray
     * @return CustomerContactIntegration
     */
    static function createGCalAccount(Customer $customer, $emailAddress, $recordArray) {
        $contactQ = new CustomerContactQuery();
        $contactSet = $contactQ->filterByEmail($emailAddress)->filterByCustomer($customer);

        // If contact doesn't exist, create it
        if($contactSet->count() <= 0) {
            $contact = new CustomerContact();
            $contact->setCustomer($customer)
                ->setEmail($emailAddress)
                ->save();
        } else {
            $contact = $contactSet[0];
        }

        $account = new CustomerContactIntegration();

        $account->setType(CustomerContactIntegration::GCAL)
            ->setStatus(CustomerContactIntegration::STATUS_ACTIVE)
            ->setCustomerContact($contact)
            ->setData($recordArray['utoken'])
            ->save();

        return $account;
    }

    /**
     * Check if email address and associated GCal are present under the given customer
     *
     * This replaces "fnCheckGcalAccountAlreadyPresent"
     *
     * @param Customer $customer
     * @param $emailAddress
     * @return null | CustomerContactIntegration
     */
    static function getGCalAccountIfPresent(Customer $customer, $emailAddress) {
        $contactQ = new CustomerContactQuery();
        $contactSet = $contactQ->filterByEmail($emailAddress)->filterByCustomer($customer)->find();

        if($contactSet->count() <= 0) {
            return NULL;
        }

        $contact = $contactSet[0];

        $integrationQ = new CustomerContactIntegrationQuery();
        $integrationSet = $integrationQ->filterByCustomerContact($contact)
            ->filterByType(CustomerContactIntegration::GCAL)->find();

        $integration = NULL;

        if($integrationSet->count() <= 0) { return NULL; }

        if($integrationSet->count() >= 2) {
            trigger_error("getGCalAccountIfPresent fetches more than 1 integration", E_USER_WARNING);
        }

        return $integrationSet[0];
    }

    /**
     * Re-writing user's token data for GCal
     *
     * This function is replacing "fnUpdateUserTokenData()"
     *
     * @param CustomerContactIntegration $gCalIntegration
     * @param $token
     * @return CustomerContactIntegration
     */
    static function updateGCalAccountUserToken(CustomerContactIntegration $gCalIntegration, $token) {
        $gCalIntegration
            ->setStatus(CustomerContactIntegration::STATUS_ACTIVE)
            ->setData($token)
            ->save();

        return $gCalIntegration;
    }

    /**
     * @deprecated Use Helpers::getIntegrations() instead of this function!
     *
     * @return bool
     */
    static function fnGetSalesUser() {
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
        curl_setopt($ch,CURLOPT_URL, $url);

        $result = curl_exec($ch);

        if(!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result,true);

        if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
            $arrSUser = $arrResponse['records'];
            return $arrSUser;
        }

        return false;
    }

    /**
     * @param string $url
     */
    static function redirect($url) {
        ob_start();
        header("Location: {$url}");
        ob_end_flush();
        die();
    }

    static function setDebugParam($active = true) {
        if( isset($_GET['XDEBUG_SESSION_START']) ) {
            $_SESSION['XDEBUG_SESSION_START'] = $_GET['XDEBUG_SESSION_START'];
        } else if( isset($_SESSION['XDEBUG_SESSION_START']) && $_SESSION['XDEBUG_SESSION_START'] ) {
            $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

            $redirectUrl = "";

            if( strpos($url, '?') === false ) {
                $redirectUrl = "{$url}?XDEBUG_SESSION_START={$_SESSION['XDEBUG_SESSION_START']}";
            } else {
                $redirectUrl = "{$url}&XDEBUG_SESSION_START={$_SESSION['XDEBUG_SESSION_START']}";
            }

            self::redirect($redirectUrl);
        }
    }
}
