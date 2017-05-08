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
            echo 'error: ' . curl_error($ch);
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

    /**
     * @param bool $active
     */
    static function setDebugParam($active = false) {
        if(!$active) {
            return;
        }

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

    /**
     * Function to connect to airtable base and get unprocessed attendees from meeting table in airtable
     * Unproceed attendees are pulled from Unmapped Attendees view under meeting history table
     * we process 5 records in 1 go
     *
     * @return bool
     */
    static function fnGetProcessAccounts() {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint. $base . '/' . $table."?maxRecords=5&view=".rawurlencode("Unmapped Attendees");

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
     * Function to check fetched contact details from sf and existing contact detail in attendee history table are same or diff
     * If detail dont match, means there is update in contact and we return true, so as to make a new entry record in attendee
     * history table
     * Other wise we return the existing attendee history record id for mapping
     *
     * @param array $arrAccountHistory
     * @return string
     */
    static function fnCheckIfContactHistoryToBeInserted($arrAccountHistory = array()) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !(is_array($arrAccountHistory) && (count($arrAccountHistory)>0)) ) {
            return "1";
        }

        $base = $strAirtableBase;
        $table = 'All%20Attendee%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("latestahisfirst");
        $url .= '&filterByFormula=('.rawurlencode("{Email}='".$arrAccountHistory[0]['Email']."'").')';

        $authorization = "Authorization: Bearer ".$strApiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        $response = curl_exec($ch);

        if(!$response) {
            echo curl_error($ch);
            return "1";
        }

        curl_close($ch);
        $arrResponse = json_decode($response,true);

        if( !(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) ) {
            return "1";
        }

        $arrSUser = $arrResponse['records'];
        $strTitle = $arrSUser[0]['fields']['SFDC Title'];
        $strMCity = $arrSUser[0]['fields']['SFDC Mailing City'];

        if($strTitle != $arrAccountHistory[0]['Title']) {
            return "1";
        } else if($strMCity != $arrAccountHistory[0]['MailingCity']) {
            return "1";
        }

        return $arrSUser[0]['id'];
    }

    /**
     * Function to map flag attendee for meeting as no sfdc contact
     * If none of the attendee details are found than meeting record get flagged
     * This function accepts the meeting recordid which is to flagged
     *
     * @param $strRecId
     * @return bool
     */
    static function fnUpdateNoContact($strRecId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint. $base . '/' . $table.'/'.$strRecId;

        $authorization = "Authorization: Bearer ".$strApiKey;
        $arrFields['fields']['SFDC Contact'] = "No SFDC Contact";
        $arrFields['fields']['attendees_mapped'] = "yes";

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
     * Function to map flag attendee details with meeting record
     * it takes attendee history record id as parameter and maps it with meeting history record id for lookup
     * it also flags the meeting record as mapped attendee- which represent completion of attendee mapping process
     *
     * @param $strRecId
     * @param $strId
     * @return bool
     */
    static function fnUpdateAccountRecord($strRecId,$strId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

        $authorization = "Authorization: Bearer ".$strApiKey;
        $arrFields['fields']['A Attendee Emails'] = $strId;
        $arrFields['fields']['attendees_mapped'] = "yes";

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
     * Function to create a attendee history record in airtable from the pulled contact information from sf.
     * It return an array of created record with its unique record it which can be used for mapping with meeting records
     *
     * @param array $arrAccountHistory
     * @param string $strId
     * @return bool|mixed
     */
    static function fnInsertContact($arrAccountHistory = array(),$strId = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !(is_array($arrAccountHistory) && (count($arrAccountHistory)>0)) ) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Attendees%20in%20SFDC';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint. $base . '/' . $table;

        $authorization = "Authorization: Bearer ".$strApiKey;
        if($arrAccountHistory[0]['Id']) {
            $arrFields['fields']['Contact ID'] = $arrAccountHistory[0]['Id'];
        }

        if($arrAccountHistory[0]['Name']) {
            $arrFields['fields']['Contact Name'] = $arrAccountHistory[0]['Name'];
        }

        if($arrAccountHistory[0]['Email']) {
            $arrFields['fields']['Email'] = $arrAccountHistory[0]['Email'];
        }

        if($arrAccountHistory[0]['AccountId']) {
            $arrFields['fields']['Account IDs'] = array($strId);
        }

        $srtF = json_encode($arrFields);
        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
        curl_getinfo($curl);
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonResponse =  json_decode($response,true);

        if(is_array($jsonResponse) && (count($jsonResponse)>0)) {
            return $jsonResponse;
        }

        return false;
    }

    /**
     * Function to create a attendee history record in airtable from the pulled contact information from sf.
     * It return an array of created record with its unique record it which can be used for mapping with meeting records
     *
     * @param array $arrAccountHistory
     * @param $strRecId
     * @return bool|mixed
     */
    static function fnInsertContactHistory($arrAccountHistory = array(),$strRecId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !(is_array($arrAccountHistory) && (count($arrAccountHistory)>0)) ) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'All%20Attendee%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;

        $authorization = "Authorization: Bearer ".$strApiKey;
        if($strRecId) {
            $arrFields['fields']['Contact ID'] = array($strRecId);
        }

        if($arrAccountHistory[0]['Email']) {
            $arrFields['fields']['Email'] = $arrAccountHistory[0]['Email'];
        }

        if($arrAccountHistory[0]['Title']) {
            $arrFields['fields']['SFDC Title'] = $arrAccountHistory[0]['Title'];
        }

        if($arrAccountHistory[0]['MailingCity']) {
            $arrFields['fields']['SFDC Mailing City'] = $arrAccountHistory[0]['MailingCity'];
        }

        $srtF = json_encode($arrFields);

        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
        curl_getinfo($curl);
        $response = curl_exec($curl);

        if(!$response) {
            echo curl_error($curl);
            return false;
        }

        curl_close($curl);
        $jsonResponse =  json_decode($response,true);

        if(is_array($jsonResponse) && (count($jsonResponse)>0)) {
            return $jsonResponse;
        }

        return false;
    }

    /**
     * Function to connect to sf and pull the contact detail from sf
     * It accepts email as parameter and queries the contact object in sf to pull details
     * It returns pulled contact detail from sf other wise false
     *
     * @param $instance_url
     * @param $access_token
     * @param string $strEmail
     * @return bool|mixed
     */
    static function fnGetContactDetailFromSf($instance_url, $access_token,$strEmail = "") {
        if(!$strEmail) {
            return false;
        }

        $query = "SELECT Name, Id, Email, Title, MailingCity, AccountId from Contact WHERE Email = '".$strEmail."' ORDER BY lastmodifieddate DESC LIMIT 1";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));

        $json_response = curl_exec($curl);
        if(!$json_response) {
            echo "error: ".curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($json_response, true);
        return $response;
    }

    /**
     * Function to check contact detail in attendee table of airtable
     * It is useful to avoid unnecessary calls to sf
     * It takes input as email and searches the attendee table for the email and return the complete record if found otherwise
     * returns false
     *
     * @param string $strEmail
     * @return bool
     */
    static function fnGetContactDetail($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Attendees%20in%20SFDC';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= '?filterByFormula=('.rawurlencode("{Email}='".$strEmail."'").')';
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
        if(!$result) {
            echo 'error: ' . curl_error($ch);
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
     * Function to check Account detail in account table of airtable
     * It takes input as account id as unique identfier form the contact detail
     * If found returns the complete record other wise returns false
     *
     * @param string $strAccId
     * @return bool
     */
    static function fnGetAccountDetail($strAccId = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strAccId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Accounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= '?filterByFormula=('.rawurlencode("{Account ID}='".$strAccId."'").')';
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
        if(!$result) {
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
     * Function to connect to airtable base and get customers gcals OAuth acceess
     *
     * @return bool
     */
    static function fnGetGcalUser() {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
        $base = $strAirtableBase;
        $table = 'gaccounts';
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
     * Function to connect to airtable base latest record for the passed email address
     * System uses the Meetingsreverse view of airtable for this processing
     */
    static function fnGetLatestMeetsForUser($strEmail) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("Meetingsreverse");
        $url .= '&filterByFormula=('.rawurlencode("{calendaremail}='".$strEmail."'").')';

        $authorization = "Authorization: Bearer ".$strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        //execute post
        $result = curl_exec($ch);//exit;

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
     * Function to check if meet already present in airtable meeting history table
     * It takes gcal eventid as parameter to check
     * Returns true if found false otherwise
     *
     * @param array $arrRecord
     * @return bool
     */
    static function fnCheckMeetingAlreadyPresent($arrRecord = array()) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !(is_array($arrRecord) && (count($arrRecord)>0)) ) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= "?filterByFormula=(gcal_mee_id='".$arrRecord['eventid']."')";
        $authorization = "Authorization: Bearer ".$strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        echo "--".$result = curl_exec($ch);

        if(!$result) {
            return true;
        }

        $arrResponse = json_decode($result,true);

        if(is_array($arrResponse) && (count($arrResponse)>0)) {
            $arrRecords = $arrResponse['records'];
            return (is_array($arrRecords) && (count($arrRecords)>0));
        }

        return true;
    }

    /**
     * Function to save google meets into airtable meeting history table
     * It takes event record as input parameter and returns the created record as reposne or false otherwise
     *
     * @param array $arrRecord
     * @return bool
     */
    static function fnSaveAirtableMeetings($arrRecord = array()) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !(is_array($arrRecord) && (count($arrRecord)>0)) ) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;

        $authorization = "Authorization: Bearer ".$strApiKey;

        if($arrRecord['eventsummary']) {
            $arrFields['fields']['Meeting Name'] = $arrRecord['eventsummary'];
        }

        if($arrRecord['startdate']) {
            $arrFields['fields']['Meeting Date'] = date("m/d/Y",strtotime($arrRecord['startdate']));
        }

        if($arrRecord['calendarid']) {
            $arrFields['fields']['calendaremail'] = $arrRecord['calendarid'];
        }

        if($arrRecord['creayedbyname']) {
            $arrFields['fields']['Created By'] = $arrRecord['creayedbyname'];
        }

        if($arrRecord['ceatedbyemail']) {
            $arrFields['fields']['created_by_email'] = $arrRecord['ceatedbyemail'];
        }

        if($arrRecord['eventdescription']) {
            $arrFields['fields']['Description'] = $arrRecord['eventdescription'];
        }

        if($arrRecord['attendeesemail']) {
            $arrFields['fields']['Attendee Email(s)'] = $arrRecord['attendeesemail'];
        }

        if($arrRecord['meetingtype']) {
            $arrFields['fields']['Meeting'] = $arrRecord['meetingtype'];
        }

        if($arrRecord['processtime']) {
            $arrFields['fields']['meetingprocesstime'] = $arrRecord['processtime'];
        }

        if($arrRecord['eventid']) {
            $arrFields['fields']['gcal_mee_id'] = $arrRecord['eventid'];
        }

        $srtF = json_encode($arrFields);
        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
        $info = curl_getinfo($curl);
        echo "---".$response = curl_exec($curl);//exit;
        curl_close($curl);
        $jsonResponse =  json_decode($response,true);
        return (is_array($jsonResponse) && (count($jsonResponse)>0));
    }

    /**
     * Function to set access record as expire when access token gets expired
     *
     * @param string $strEmail
     * @return bool
     */
    static function fnGetUsergAcc($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $api_key = 'keyOhmYh5N0z83L5F';
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

        if(!$result) {
            return true;
        }

        $arrResponse = json_decode($result,true);

        if( !(is_array($arrResponse) && (count($arrResponse)>0))) {
            return true;
        }

        $arrRecords = $arrResponse['records'];

        if(is_array($arrRecords) && (count($arrRecords)>0)) {
            return $arrRecords[0]['id'];
        }

        return false;
    }

    /**
     * Function to set access record as expire when access token gets expired
     *
     * @param string $strEmail
     * @return bool
     */
    static function fnUpdatesGstatus($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $strId = Helpers::fnGetUsergAcc($strEmail);

        if(!$strId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'gaccounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strId;

        $authorization = "Authorization: Bearer ".$strApiKey;
        $arrFields['fields']['status'] = "expired";

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
     * Function to send notification mail about access token expiry and how to revoke the access
     *
     * @param string $strEmail
     * @return bool
     */
    static function fnSendAccountExpirationMail($strEmail = "") {
        global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

        if(!$strEmail) {
            return false;
        }

        $to = $strEmail;
        $subject = "Google Calendar Access Expired";
        $strFrom = $strFromEmailAddress;

        $message = "Hello There,".'<br/><br/>';
        $message .= 'The Access to your calendar has been expired. <br/><br/>';

        $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

        $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
        $message .= 'Thanks';

        require_once 'Mail.php';

        $headers = array (
            'From' => $strFrom,
            'To' => $to,
            'Subject' => $subject);

        $smtpParams = array (
            'host' => $strSmtpHost,
            'port' => $strSmtpPPort,
            'auth' => true,
            'username' => $strSmtpUsername,
            'password' => $strSmtpPassword
        );

        // Create an SMTP client.
        $mail = Mail::factory('smtp', $smtpParams);

        // Send the email.
        $result = $mail->send($to, $headers, $message);

        if (PEAR::isError($result)) {
            echo("Email not sent. " .$result->getMessage() ."\n");
            return false;
        }

        echo("Email sent!"."\n");
        return true;
    }


    /**
     * Function to connect to airtable base and get calenderaemail information from calendar_not_processed view of airtable on
     * meeting history table
     * System process such 50 records at one go
     * it return record list on mactch other wise return false
     *
     * @return bool
     */
    static function fnGetProcessCalendar() {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=50&view=".rawurlencode("calendar_not_processed");
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
     * Function to connect to airtable base and looks up for the calendraemail in people table
     * On match found it will return the macthed record and user name detail for that email address other wise return false
     * It takes calendaremail as parameter
     *
     * @param string $strEmail
     * @return bool
     */
    static function fnGetUserName($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'People';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= '?filterByFormula=('.rawurlencode("Email ='".$strEmail."'").")";

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
        if(!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result,true);

        if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
            $arrSUser = $arrResponse['records'];
            $strName = $arrSUser[0]['fields']['Name'];
            return $strName;
        }

        return false;
    }

    /**
     * Function to connect to airtable base and update meeting history table with name
     * It will take name which will be updated
     * It will take meeting history table record id as another table where it will be updated
     *
     * @param $strName
     * @param $strRecId
     * @return bool
     */
    static function fnUpdateUserName($strName,$strRecId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

        $authorization = "Authorization: Bearer ".$strApiKey;
        $arrFields['fields']['Calendar'] = $strName;
        $arrFields['fields']['calendar_processed'] = "processed";

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
        curl_close($curl);
        $jsonResponse =  json_decode($response,true);
        return (is_array($jsonResponse) && (count($jsonResponse)>0));
    }
}
