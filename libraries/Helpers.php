<?
require_once("../global-config.php");

use DataModels\DataModels\CustomerQuery as CustomerQuery;
use DataModels\DataModels\Customer as Customer;
use DataModels\DataModels\CustomerContactIntegrationQuery as CustomerContactIntegrationQuery;

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

    static function getIntegrations(Customer $customer, $integrationType = 'gcal') {
        $contacts = $customer->getCustomerContacts();
        $integrations = array();

        $q = new CustomerContactIntegrationQuery();

        foreach($contacts as $contact) {
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

}
