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

    static function getIntegrations(Customer $customer,
                                    $integrationType = CustomerContactIntegration::GCAL) {
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

    /**
     * Creating a new GCal Account
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
     * @param Customer $customer
     * @param $emailAddress
     * @return bool
     */
    static function isGCalAccountPresent(Customer $customer, $emailAddress) {
        $contactQ = new CustomerContactQuery();
        $contactSet = $contactQ->filterByEmail($emailAddress)->filterByCustomer($customer);

        if($contactSet->count() <= 0) {
            return false;
        }

        $contact = $contactSet[0];

        $integrationQ = new CustomerContactIntegrationQuery();
        $integrationSet = $integrationQ->filterByCustomerContact($contact)->filterByType(CustomerContactIntegration::GCAL);

        return $integrationSet->count() >= 1;
    }

    /**
     * Re-writing user's token data for GCal
     *
     * This function is replacing "fnUpdateUserTokenData()"
     */
    static function updateGCalAccountUserToken(Customer $customer, $emailAddress, $token) {
//        $jsonData = json_decode($integration->getData());
//        $jsonData->

        

        throw new Exception("NOT IMPLEMENTED YET!");
    }

    static function fnUpdateUserTokenData($strRecId,$arrRecord = array()) {
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


    /**
     * Returning if Google Cal Account Exists in AirTable
     *
     * @deprecated Use isGCalAccountPresent() instead of this fn
     * @param string $strEmail
     * @return bool
     */
    static function fnCheckGcalAccountAlreadyPresent($strEmail = "") {
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


    /**
     * Save Google Calendar Account to Airtable
     *
     * @deprecated Use createGCalAccount() instead of this fn.
     * @param array $arrRecord
     * @return bool
     */
    static function fnSaveGcalAccount($arrRecord = array()) {
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

}
