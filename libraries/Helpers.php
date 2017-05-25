<?
require_once("${_SERVER['DOCUMENT_ROOT']}/global-config.php");

use DataModels\DataModels\ClientCalendarUserQuery as ClientCalendarUserQuery;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUser as ClientCalendarUser;
use DataModels\DataModels\MeetingQuery as MeetingQuery;
use DataModels\DataModels\Meeting as Meeting;
use DataModels\DataModels\ClientCalendarUserOAuthQuery as ClientCalendarUserOAuthQuery;
use DataModels\DataModels\ClientQuery as ClientQuery;
use Propel\Runtime\ActiveQuery\Criteria as Criteria;

class Helpers {
    /**
     * @param $emailDomain String
     * @return array
     * @throws Exception
     */
    static function loadClientData($emailDomain) {
        $q = new ClientQuery();
        $clientSet = $q->findByEmailDomain($emailDomain);

        if($clientSet->count() <= 0) {
            throw new Exception("Client Data is not found for {$emailDomain}");
        }

        $client = $clientSet[0];
        $calendarUsers = $client->getClientCalendarUsers();

        return [$client, $calendarUsers];
    }

    /**
     * Replaces "fnGetSalesUser" & "fnGetGcalUser" functions
     *
     * @param Client $client
     * @param string $authType
     * @return array
     */
    static function getAuthentications(Client $client,
                                    $authType = ClientCalendarUserOAuth::GCAL) {
        $calendarUsers = $client->getClientCalendarUsers();
        $integrations = array();

        /**
         * @var $calendarUser ClientCalendarUser
         */
        foreach($calendarUsers as $calendarUser) {
            $q = new ClientCalendarUserOAuthQuery();
            $collection = $q->filterByClientCalendarUser($calendarUser)->filterByType($authType)->find();
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
     * This replaces "fnSaveGcalAccount" & "fnUpdateSalesUser"
     *
     * @param Client $client
     * @param $emailAddress
     * @param String $data JSON Data
     * @param string $integrationType
     * @return ClientCalendarUserOAuth
     */
    static function createAuthAccount(Client $client, $emailAddress, $data,
                                      $integrationType = ClientCalendarUserOAuth::GCAL) {

        $calendarUser = ClientCalendarUser::findOrCreateByClientAndCalendar($client, $emailAddress);
        $auth = new ClientCalendarUserOAuth();

        $auth->setType($integrationType)
            ->setStatus(ClientCalendarUserOAuth::STATUS_ACTIVE)
            ->setClientCalendarUser($calendarUser)
            ->setData($data)
            ->save();

        return $auth;
    }

    /**
     * Check if email address and associated authentication are present under the given client
     *
     * This replaces "fnCheckGcalAccountAlreadyPresent"
     *
     * @param Client $client
     * @param String $emailAddress
     * @param string $authType
     * @return ClientCalendarUserOAuth|null
     */
    static function getOAuthIfPresent(Client $client, $emailAddress,
                                      $authType = ClientCalendarUserOAuth::GCAL) {
        $calendarUserQuery = new ClientCalendarUserQuery();
        $calendarUsers = $calendarUserQuery->filterByEmail($emailAddress)->filterByClient($client)->find();

        if($calendarUsers->count() <= 0) {
            return NULL;
        }

        $calendarUser = $calendarUsers[0];

        $authQuery = new ClientCalendarUserOAuthQuery();
        $authSet = $authQuery->filterByClientCalendarUser($calendarUser)
            ->filterByType($authType)->find();

        $integration = NULL;

        if($authSet->count() <= 0) { return NULL; }

        if($authSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 integration", E_USER_WARNING);
        }

        return $authSet[0];
    }

    /**
     * Re-writing user's token data for GCal
     *
     * This function is replacing "fnUpdateUserTokenData()"
     *
     * @param ClientCalendarUserOAuth $auth
     * @param Array $data
     * @return ClientCalendarUserOAuth
     */
    static function updateAuthenticationToken(ClientCalendarUserOAuth $auth, $data) {
        $auth
            ->setStatus(ClientCalendarUserOAuth::STATUS_ACTIVE)
            ->setData(json_encode($data))
            ->save();

        return $auth;
    }

    /**
     * @deprecated Use Helpers::getAuthentications() instead of this function!
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
     * Unprocessed attendees are pulled from Unmapped Attendees view under meeting history table
     * we process 5 records in 1 go
     *
     * @param string $view
     * @return array
     */
    static function fnGetProcessAccounts($view = "Unmapped Attendees") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint. $base . '/' . $table."?maxRecords=5&view=".rawurlencode($view);

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
            return array();
        }

        $arrResponse = json_decode($result,true);

        if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
            $arrSUser = $arrResponse['records'];
            return $arrSUser;
        }

        return array();
    }

    static function fnGetProcessAccountsForOppties() {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        $strDate = strtotime(date("Y-m-d"));
        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . "?maxRecords=5&view=" . rawurlencode("opportunity_not_processed");
        $url .= '&filterByFormula=(' . rawurlencode("{meetingprocesstime}<='" . $strDate . "'") . ')';

        $authorization = "Authorization: Bearer " . $strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        //execute post
        $result = curl_exec($ch);

        if(!$result) {
            echo 'error:' . curl_error($ch);
            return array();
        }

        $arrResponse = json_decode($result, true);

        if(isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
            $arrSUser = $arrResponse['records'];
            return $arrSUser;
        }

        return array();
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
    static function fnUpdateAccountRecord($strRecId, $strId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
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

    static function fnUpdateAccountRecordForOppties($strRecId, $strId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Opportunities';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;

        $authorization = "Authorization: Bearer " . $strApiKey;
        $arrFields['fields']['Opportunity History'] = $strId;
        $srtF = json_encode($arrFields);
        $curl = curl_init($url);

        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
        $info = curl_getinfo($curl);
        $response = curl_exec($curl);

        if (!$response) {
            echo curl_error($curl);
        }

        curl_close($curl);
        $jsonResponse = json_decode($response, true);

        return (is_array($jsonResponse) && (count($jsonResponse) > 0));
    }

    static function fnInsertOpportunity($arrAccountHistory = array(), $strId = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
        if (is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $api_key = 'keyOhmYh5N0z83L5F';
            $base = $strAirtableBase;
            $table = 'Opportunities';
            $strApiKey = $strAirtableApiKey;
            $airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table;
            $authorization = "Authorization: Bearer " . $strApiKey;

            if($arrAccountHistory[0]['Id']) {
                $arrFields['fields']['Opportunity ID'] = $arrAccountHistory[0]['Id'];
            }

            if($arrAccountHistory[0]['Name']) {
                $arrFields['fields']['Opportunity Name'] = $arrAccountHistory[0]['Name'];
            }

            if($strId) {
                $arrFields['fields']['Acct ID'] = array($strId);
            }

            $srtF = json_encode($arrFields);
            $curl = curl_init($url);
            // Accept any server (peer) certificate on dev envs
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
            $info = curl_getinfo($curl);
            echo "--" . $response = curl_exec($curl);
            curl_close($curl);
            $jsonResponse = json_decode($response, true);

            if(is_array($jsonResponse) && (count($jsonResponse) > 0)) {
                return $jsonResponse;
            }

            return false;
        }

        return false;
    }

    static function fnInsertOppHistory($arrAccountHistory = array(), $strRecId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if (is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $base = $strAirtableBase;
            $table = 'Opportunity%20History';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table;
            $authorization = "Authorization: Bearer " . $strApiKey;
            if ($strRecId) {
                $arrFields['fields']['Opportunity ID'] = array($strRecId);
            }
            if ($arrAccountHistory[0]['Name']) {
                $arrFields['fields']['Opportunity Name'] = $arrAccountHistory[0]['Name'];
            }
            if ($arrAccountHistory[0]['StageName']) {
                $arrFields['fields']['Stage'] = $arrAccountHistory[0]['StageName'];
            }
            if ($arrAccountHistory[0]['Amount']) {
                $arrFields['fields']['Amount'] = $arrAccountHistory[0]['Amount'];
            }
            if (is_array($arrAccountHistory[0]['Owner'])) {
                $arrFields['fields']['Owner'] = $arrAccountHistory[0]['Owner']['Name'];
            }
            if ($arrAccountHistory[0]['Buyer_Stage__c']) {
                $arrFields['fields']['Buyer Stage'] = $arrAccountHistory[0]['Buyer_Stage__c'];
            }
            if ($arrAccountHistory[0]['CloseDate']) {
                $arrFields['fields']['Close Date'] = date('m/d/Y', strtotime($arrAccountHistory[0]['CloseDate']));
            }
            $srtF = json_encode($arrFields);
            $curl = curl_init($url);
            // Accept any server (peer) certificate on dev envs
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
            $info = curl_getinfo($curl);
            echo "---" . $response = curl_exec($curl);
            if (!$response) {
                echo curl_error($curl);
                return false;
            }

            curl_close($curl);
            $jsonResponse = json_decode($response, true);
            if (is_array($jsonResponse) && (count($jsonResponse) > 0)) {
                return $jsonResponse;
            }
            return false;
        }

        return false;
    }

    static function fnGetOpportunityDetailFromSf($instance_url, $access_token, $strAccId = "") {
        if(!$strAccId) {
            return false;
        }

        $query = "SELECT Name, Id, Owner.Name, StageName, Amount, Buyer_Stage__c, CloseDate from Opportunity WHERE AccountId = '" . $strAccId . "' ORDER BY lastmodifieddate DESC LIMIT 1";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));
        $json_response = curl_exec($curl);
        if (!$json_response) {
            echo "--error---" . curl_error($curl);
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        return $response;
    }

    static function fnGetOpportunityDetail($strAccId = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strAccId) {
            return false;
        }

        $api_key = 'keyOhmYh5N0z83L5F';
        $base = $strAirtableBase;
        $table = 'Opportunities';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table;
        $url .= '?filterByFormula=(' . rawurlencode("{acc_name}='" . $strAccId . "'") . ')';
        $authorization = "Authorization: Bearer " . $strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        echo "--" . $result;
        if (!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result, true);

        if(isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
            $arrSUser = $arrResponse['records'];
            return $arrSUser;
        }
        return false;
    }

    static function fnGetAccountDetail($strAccId = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strAccId) {
            return false;
        }

        $api_key = 'keyOhmYh5N0z83L5F';
        $base = $strAirtableBase;
        $table = 'Accounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . "/" . $strAccId;
        $authorization = "Authorization: Bearer " . $strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        //execute post
        $result = curl_exec($ch);

        if (!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result, true);
        if (isset($arrResponse) && (count($arrResponse) > 0)) {
            $arrSUser = $arrResponse;
            return $arrSUser;
        }
        return false;
    }


    static function fnUpdateAccountRecordForSFDC($strRecId, $strId, $strAId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;
        $authorization = "Authorization: Bearer " . $strApiKey;
        $arrFields['fields']['Account'] = $strId;
        $arrFields['fields']['account_processed'] = "mapped";

        if(is_array($strAId) && (count($strAId) > 0)) {
            $arrFields['fields']['accountno'] = implode(",", $strAId);
        }

        $srtF = json_encode($arrFields);
        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
        $info = curl_getinfo($curl);
        $response = curl_exec($curl);

        if(!$response) {
            echo curl_error($curl);
        }

        curl_close($curl);
        $jsonResponse = json_decode($response, true);

        if(is_array($jsonResponse) && (count($jsonResponse) > 0)) {
            return true;
        }

        return false;
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
    static function fnGetContactDetailFromSf($instance_url, $access_token,$strEmail, $withMailingCity = true) {
        if(!$strEmail) {
            return false;
        }

        if($withMailingCity) {
            $query = "SELECT Name, Id, Email, Title, MailingCity, AccountId from Contact WHERE Email = '".$strEmail."' ORDER BY lastmodifieddate DESC LIMIT 1";
        } else {
            $query = "SELECT Name, Id, Email, Title, AccountId from Contact WHERE Email = '" . $strEmail . "' LIMIT 1";
        }
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

    static function fnInsertAccount($arrAccountHistory = array(), $strDomain = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if (is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $base = $strAirtableBase;
            $table = 'Accounts';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table;
            $authorization = "Authorization: Bearer " . $strApiKey;
            if ($arrAccountHistory[0]['Id']) {
                $arrFields['fields']['Account ID'] = $arrAccountHistory[0]['Id'];
            }
            if ($arrAccountHistory[0]['Name']) {
                $arrFields['fields']['Account'] = $arrAccountHistory[0]['Name'];
            }
            if ($strDomain) {
                $arrFields['fields']['Account Domain'] = $strDomain;
            }
            $srtF = json_encode($arrFields);
            $curl = curl_init($url);
            // Accept any server (peer) certificate on dev envs
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
            $info = curl_getinfo($curl);
            $response = curl_exec($curl);
            curl_close($curl);
            $jsonResponse = json_decode($response, true);

            if (is_array($jsonResponse) && (count($jsonResponse) > 0)) {
                return $jsonResponse;
            }
            return false;
        }

        return false;
    }

    static function fnInsertAccountHistory($arrAccountHistory = array(), $strRecId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
        if(is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $base = $strAirtableBase;
            $table = 'Account%20History';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table;
            $authorization = "Authorization: Bearer " . $strApiKey;
            if($strRecId) {
                $arrFields['fields']['Account ID'] = array($strRecId);
            }
            if($arrAccountHistory[0]['Name']) {
                $arrFields['fields']['Account Name'] = $arrAccountHistory[0]['Name'];
            }
            if($arrAccountHistory[0]['NumberOfEmployees']) {
                $arrFields['fields']['# Employees'] = $arrAccountHistory[0]['NumberOfEmployees'];
            }
            if($arrAccountHistory[0]['BillingCity']) {
                $arrFields['fields']['Billing City'] = $arrAccountHistory[0]['BillingCity'];
            }
            $srtF = json_encode($arrFields);
            $curl = curl_init($url);
            // Accept any server (peer) certificate on dev envs
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
            $info = curl_getinfo($curl);
            echo "---" . $response = curl_exec($curl);

            if(!$response) {
                echo curl_error($curl);
                return false;
            }

            curl_close($curl);
            $jsonResponse = json_decode($response, true);
            if(is_array($jsonResponse) && (count($jsonResponse) > 0)) {
                return $jsonResponse;
            }
            return false;
        }
        return false;
    }

    static function fnGetAccountDetailFromSf($instance_url, $access_token, $strAccDomain = "") {
        if(!$strAccDomain) {
            return false;
        }

        $query = "SELECT Name, Id, NumberOfEmployees, BillingCity from Account WHERE Website LIKE '%" . $strAccDomain . "%' ORDER BY lastmodifieddate DESC LIMIT 1";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));
        $json_response = curl_exec($curl);
        if (!$json_response) {
            echo "--error---" . curl_error($curl);
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        return $response;
    }

    static function fnGetAccountDetailFromSfId($instance_url, $access_token, $strId = "") {
        if(!$strId) {
            return false;
        }

        $query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue from Account WHERE Id = '" . $strId . "' LIMIT 1";
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));
        $json_response = curl_exec($curl);
        if (!$json_response) {
            echo "--error---" . curl_error($curl);
        }
        curl_close($curl);
        $response = json_decode($json_response, true);
        return $response;
    }

    /**
     * Function to flag meeting record as processed under opportunity_processed column in meeting history airtable
     * Please not the flagg here is proccess and not yes, it gets sets only when for a given record there is no account or
     * opprotunities detail found.
     * It takes the meeting record id as a parameter and update the status on that record id of airtable
     * return true or false
     *
     * @param $strRecId
     * @return bool
     */
    static function fnUpdateOppProcessedRecord($strRecId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;
        $authorization = "Authorization: Bearer " . $strApiKey;
        $arrFields['fields']['oppurtunity_processed'] = "processed";
        if (is_array($strAId) && (count($strAId) > 0)) {
            $arrFields['fields']['accountno'] = implode(",", $strAId);
        }
        $srtF = json_encode($arrFields);
        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
        $info = curl_getinfo($curl);
        $response = curl_exec($curl);
        if (!$response) {
            echo curl_error($curl);
        }
        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        if (is_array($jsonResponse) && (count($jsonResponse) > 0)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to check if the current oppprtunity history detail is different from the fetched opportunity details
     * If found to be different system returns true otherwise returns existing record id for mapping.
     *
     * @param array $arrAccountHistory
     * @return string
     */
    static function fnCheckIfOppHistoryToBeInserted($arrAccountHistory = array()) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
        if (is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $base = $strAirtableBase;
            $table = 'Opportunity%20History';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table . "?maxRecords=1&view=" . rawurlencode("latestoppfirst");
            $url .= '&filterByFormula=(' . rawurlencode("{Opportunity Name}='" . $arrAccountHistory[0]['Name'] . "'") . ')';
            $authorization = "Authorization: Bearer " . $strApiKey;
            $srtF = json_encode($arrFields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);

            if (!$response) {
                echo curl_error($ch);
                return "1";
            }

            curl_close($ch);
            $arrResponse = json_decode($response, true);
            print("db history - <pre>");
            print_r($arrResponse);
            if (isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
                $arrSUser = $arrResponse['records'];
                $strOwner = $arrSUser[0]['fields']['Owner'];
                $strStage = $arrSUser[0]['fields']['Stage'];
                $strAmount = $arrSUser[0]['fields']['Amount'];
                $strBuyerStage = $arrSUser[0]['fields']['Buyer Stage'];
                $strCloseDate = strtotime($arrSUser[0]['fields']['Close Date']);

                if(
                    ($strOwner != $arrAccountHistory[0]['Owner']['Name']) ||
                    ($strStage != $arrAccountHistory[0]['StageName']) ||
                    ($strAmount != $arrAccountHistory[0]['Amount']) ||
                    ($strBuyerStage != $arrAccountHistory[0]['Buyer_Stage__c']) ||
                    ($strCloseDate != strtotime($arrAccountHistory[0]['CloseDate']))
                ) {
                    return "1";
                }

                return $arrSUser[0]['id'];
            }
        }

        return "1";
    }

    static function fnUpdateMeetingRecord($strRecId, $strId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
        if ($strRecId) {
            $base = $strAirtableBase;
            $table = 'Meeting%20History';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;
            $authorization = "Authorization: Bearer " . $strApiKey;
            //$arrFields['fields']['Account'] = array($strId)$strName;
            $arrFields['fields']['Opportunity History'] = $strId;
            $arrFields['fields']['oppurtunity_processed'] = "yes";
            $srtF = json_encode($arrFields);
            $curl = curl_init($url);
            // Accept any server (peer) certificate on dev envs
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
            $info = curl_getinfo($curl);
            echo "----" . $response = curl_exec($curl);
            if (!$response) {
                echo curl_error($curl);
            }
            curl_close($curl);
            $jsonResponse = json_decode($response, true);
            if (is_array($jsonResponse) && (count($jsonResponse) > 0)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static function fnGetAccountDetailByName($strAccName = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strAccName) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Accounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table;
        $url .= '?filterByFormula=(' . rawurlencode("{Account}='" . $strAccName . "'") . ')';
        $authorization = "Authorization: Bearer " . $strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        //execute post
        $result = curl_exec($ch);

        if(!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result, true);

        if (isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
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
    static function fnGetAccountDetailForAttendees($strAccId = "") {
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

    static function fnGetAccountDetailByAccountDomain($strAccDomain = "") {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if(!$strAccDomain) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'Accounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table;
        $url .= '?filterByFormula=(' . rawurlencode("{Account Domain}='" . $strAccDomain . "'") . ')';
        $authorization = "Authorization: Bearer " . $strApiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        if (!$result) {
            echo 'error:' . curl_error($ch);
            return false;
        }

        $arrResponse = json_decode($result, true);
        if (isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
            $arrSUser = $arrResponse['records'];
            return $arrSUser;
        }

        return false;
    }

    /**
     * Function to connect to airtable base and get clients gcals OAuth acceess
     *
     * @deprecated Use getAuthentications() function instead
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
     * Use this function instead of fnGetLatestMeetsForUser() function
     *
     * @param Client $client
     * @param $emailAddress
     * @return Meeting|null
     */
    static function getLastMeetingInDBForEmailAddress(Client $client, $emailAddress) {
        if(!$emailAddress) {
            return NULL;
        }

        $calendarUser = ClientCalendarUser::findByClientAndCalendar($client, $emailAddress);

        if(!$calendarUser) {
            return NULL;
        }

        $q = new MeetingQuery();
        return $q->filterByClientCalendarUser($calendarUser)->orderByEventDatetime(Criteria::DESC)->findOne();
    }

    /**
     * Function to connect to airtable base latest record for the passed email address
     * System uses the Meetingsreverse view of airtable for this processing
     *
     * @deprecated use getLastMeetingInDBForEmailAddress() instead
     * @param $strEmail
     * @return bool
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
     * Replaced fnCheckMeetingAlreadyPresent()
     *
     * @param Google_Service_Calendar_Event $event
     * @return Meeting|null
     */
    static function getMeetingIfExists(Google_Service_Calendar_Event $event) {
        $mq = new MeetingQuery();
        $meetings = $mq->findByEventId($event->id);

        if($meetings->count() <= 0) {
            return NULL;
        }

        if($meetings->count() > 1) {
            trigger_error(__FUNCTION__ . " more than 1 meetings is found with event_id: {$event->id}", E_WARNING);
        }

        return $meetings[0];
    }

    /**
     * Function to check if meet already present in airtable meeting history table
     * It takes gcal eventid as parameter to check
     * Returns true if found false otherwise
     *
     * @param array $arrRecord
     * @return bool
     * @deprecated use getMeetingIfExists() function instead
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

        $result = curl_exec($ch);

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
     * Function to save google meeting into airtable meeting history table
     * It takes event record as input parameter and returns the created record as reposne or false otherwise
     *
     * @param array $arrRecord
     * @return bool
     * @deprecated use new Meeting instance instead (look at "synccaldata.php")
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

        if($arrRecord['createdByName']) {
            $arrFields['fields']['Created By'] = $arrRecord['createdByName'];
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
        $response = curl_exec($curl);
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
     * Setting access record as expired
     *
     * @deprecated use ClientCalendarUser::setStatus() function instead
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
    static function sendAccountExpirationMail($strEmail = "") {
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
     * Function to update meeting history table with (calendar) name via recordId.
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

    /**
     * Function to connect to airtable base and update google calendar access details
     * It take 1 parameter as the updated token and 1 as the record if where token is to be updated
     * On success it returns true otherwise false
     *
     * @param $strToken
     * @param $strRecId
     * @return bool
     * @deprecated use ClientCalendarUserOAuth instance functions instead!
     */
    function fnUpdateAccessTokenSalesUser($strToken,$strRecId) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strToken) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'gaccounts';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

        $authorization = "Authorization: Bearer ".$strApiKey;
        $arrFields['fields']['user_token'] = $strToken;

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

    /**
     * Returns user info from airtable if exists
     *
     * @deprecated use getOAuthIfPresent() instead
     * @param string $strEmail
     * @return bool|array
     */
    static function fnCheckAlreadySavedSalesUser($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if( !$strEmail ) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'salesuser';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= '?filterByFormula=('.rawurlencode("{email}='".$strEmail."'").')';
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

        if( !(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) ) {
            return false;
        }

        return $arrResponse['records'];
    }

    /**
     * Replaced "fnGetUserDetailFromSF()" function
     *
     * @param string $userUrl
     * @param string $access_token
     * @return null | mixed
     */
    static function getUserDetailFromSFDC($userUrl, $access_token) {
        if( !$userUrl ) {
            return NULL;
        }

        $url = $userUrl;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth {$access_token}"));

        $json_response = curl_exec($curl);

        if(!$json_response) {
            trigger_error("There is a curl_error: " . curl_error($curl), E_ERROR);
        }

        curl_close($curl);

        $response = json_decode($json_response, true);
        return $response;
    }

    static function fnSaveSalesUser($arrRecord = array()) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
        if(is_array($arrRecord) && (count($arrRecord)>0)) {
            $base = $strAirtableBase;
            $table = 'salesuser';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint.$base.'/'.$table;

            $authorization = "Authorization: Bearer ".$strApiKey;
            if($arrRecord['accesstoken']) {
                $arrFields['fields']['user_token'] = $arrRecord['accesstoken'];
            }

            if($arrRecord['tokendata']) {
                $arrFields['fields']['salesuseraccesstoken'] = $arrRecord['tokendata'];
            }
            $arrFields['fields']['status'] = "active";
            if($arrRecord['userid']) {
                $arrFields['fields']['userid'] = $arrRecord['userid'];
            }

            if($arrRecord['email']) {
                $arrFields['fields']['email'] = $arrRecord['email'];
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
            $response = curl_exec($curl);
            curl_close($curl);
            $jsonResponse =  json_decode($response,true);
            if(is_array($jsonResponse) && (count($jsonResponse)>0)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *
     * @deprecated this function is replaced by "createAuthAccount()" function
     * @param $strRecId
     * @param array $arrRecord
     * @return bool
     */
    static function fnUpdateSalesUser($strRecId,$arrRecord = array()) {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strRecId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'salesuser';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

        $authorization = "Authorization: Bearer ".$strApiKey;

        if($arrRecord['accesstoken']) {
            $arrFields['fields']['user_token'] = $arrRecord['accesstoken'];
        }

        if($arrRecord['tokendata']) {
            $arrFields['fields']['salesuseraccesstoken'] = $arrRecord['tokendata'];
        }

        $arrFields['fields']['status'] = "active";

        if($arrRecord['userid']) {
            $arrFields['fields']['userid'] = $arrRecord['userid'];
        }

        if($arrRecord['email']) {
            $arrFields['fields']['email'] = $arrRecord['email'];
        }

        $srtF = json_encode($arrFields);
        $curl = curl_init($url);

        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
        curl_getinfo($curl);

        $response = curl_exec($curl);

        if(!$response) {
            echo curl_error($curl);
        }

        curl_close($curl);
        $jsonResponse =  json_decode($response,true);

        return is_array($jsonResponse) && (count($jsonResponse)>0);
    }

    /**
     * @param $refresh_token
     * @param string $strEmail
     * @return bool|mixed
     */
    static function sfdcRefreshToken($refresh_token, $strEmail = "") {
        $url = "https://login.salesforce.com/services/oauth2/token";
        $strPostVariables = "grant_type=refresh_token&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&refresh_token=".$refresh_token;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPostVariables);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Accept: application/json"));

        $json_response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($json_response, true);

        if( !(is_array($response) && (count($response)>0)) ) {
            trigger_error("SFDC token refreshment request turned nothing", E_ERROR);
            return false;
        }

        if(isset($response['error']) &&
            $response['error_description'] == "expired access/refresh token") {

            Helpers::fnUpdatesSGstatus($strEmail);
            Helpers::fnSendEmailToSFDCAdminAboutTokenExpiry($strEmail);

            trigger_error("Admin for SFDC got an email for account token reset.", E_NOTICE);
            return false;
        }

        if($response['access_token']) {
            return $response;
        }

        trigger_error("SFDC access token refreshing is not completed.", E_ERROR);
        return false;
    }

    static function fnSendEmailToSFDCAdminAboutTokenExpiry($strEmail) {
        global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

        if(!$strEmail) {
            return false;
        }

        $to = $strEmail;
        $subject = "Salesforce Oauth Access Expired";
        $strFrom = $strFromEmailAddress;

        $message = "Hello There,".'<br/><br/>';
        $message .= 'The Access to your salesforce account has been expired. <br/><br/>';

        $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

        $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
        $message .= 'Thanks';

        /* $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: johnrola36@gmail.com'."\r\n";
        $retval = mail ($to,$subject,$message,$headers); */

        /* define('USERNAME','AKIAIBPZMF6PMB6XK2OA');
        define('PASSWORD','At6xulRB6J8VtWqlLWQZ5+NWas6G2GchiYVInzyeD2Xe');
        define('HOST', 'email-smtp.us-west-2.amazonaws.com');
        define('PORT', '587'); */

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

    static function fnUpdatesSGstatus($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $strId = self::fnGetUsergAccSFDC($strEmail);

        if(!$strId) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'salesuser';
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

    static function fnGetUsergAccSFDC($strEmail = "") {
        global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

        if(!$strEmail) {
            return false;
        }

        $base = $strAirtableBase;
        $table = 'salesuser';
        $strApiKey = $strAirtableApiKey;
        $url = $strAirtableBaseEndpoint.$base.'/'.$table;
        $url .= "?filterByFormula=(email='".$strEmail."')";
        $authorization = "Authorization: Bearer ".$strApiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch,CURLOPT_URL, $url);

        //execute post
        $result = curl_exec($ch);

        if(!$result) {
            return true;
        }

        $arrResponse = json_decode($result,true);

        if(is_array($arrResponse) && (count($arrResponse)>0)) {
            $arrRecords = $arrResponse['records'];

            if(is_array($arrRecords) && (count($arrRecords)>0)) {
                return $arrRecords[0]['id'];
            }

            return false;
        }

        return true;
    }

    static function fnCheckIfAccountHistoryToBeInserted($arrAccountHistory = array()) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if (is_array($arrAccountHistory) && (count($arrAccountHistory) > 0)) {
            $base = $strAirtableBase;
            $table = 'Account%20History';
            $strApiKey = $strAirtableApiKey;
            $url = $strAirtableBaseEndpoint . $base . '/' . $table . "?maxRecords=1&view=" . rawurlencode("latestfirst");
            echo "---" . $url .= '&filterByFormula=(' . rawurlencode("{Account Name}='" . $arrAccountHistory[0]['Name'] . "'") . ')';
            $authorization = "Authorization: Bearer " . $strApiKey;
            $srtF = json_encode($arrFields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_URL, $url);
            echo "---" . $response = curl_exec($ch);
            if (!$response) {
                echo curl_error($ch);
                curl_close($ch);
                return "1";
            }
            curl_close($ch);
            $arrResponse = json_decode($response, true);
            print("db history - <pre>");
            print_r($arrResponse);
            if (isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
                $arrSUser = $arrResponse['records'];
                $strEmployees = $arrSUser[0]['fields']['# Employees'];
                $strBcity = $arrSUser[0]['fields']['Billing City'];
                if ($strEmployees != $arrAccountHistory[0]['NumberOfEmployees']) {
                    return "1";
                } else if ($strBcity != $arrAccountHistory[0]['BillingCity']) {
                    return "1";
                } else {
                    return $arrSUser[0]['id'];
                }
            }
        }

        return "1";
    }

    static function fnUpdateAccountProcessedRecord($strRecId) {
        global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

        if (!$strRecId) {
            return false;
        }

        $api_key = 'keyOhmYh5N0z83L5F';
        $base = $strAirtableBase;
        $table = 'Meeting%20History';
        $strApiKey = $strAirtableApiKey;
        $airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
        $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;
        $authorization = "Authorization: Bearer " . $strApiKey;
        $arrFields['fields']['account_processed'] = "processed";
        if (is_array($strAId) && (count($strAId) > 0)) {
            $arrFields['fields']['accountno'] = implode(",", $strAId);
        }
        $srtF = json_encode($arrFields);
        $curl = curl_init($url);
        // Accept any server (peer) certificate on dev envs
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json", $authorization));
        $info = curl_getinfo($curl);
        $response = curl_exec($curl);

        if (!$response) {
            echo curl_error($curl);
        }

        curl_close($curl);
        $jsonResponse = json_decode($response, true);

        if (is_array($jsonResponse) && (count($jsonResponse) > 0)) {
            return true;
        }

        return false;
    }


    /**
     * @param $googleCalAPICredentialFile
     * @param bool $accessOffline
     * @return Google_Client
     */
    static function setupGoogleAPIClient($googleCalAPICredentialFile, $accessOffline = false) {
        require_once("${_SERVER['DOCUMENT_ROOT']}/gcal/vendor/autoload.php");
        $client = new Google_Client();
        $client->setAuthConfig($googleCalAPICredentialFile);
        $client->addScope(array(Google_Service_Calendar::CALENDAR));
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzleClient);

        if($accessOffline) { $client->setAccessType("offline"); }

        return $client;
    }

    /**
     * @return array
     */
    static function getPersonalEmailDomains() {
        return array("gmail.com", "yahoo.com", "yahoo.co.in", "aol.com", "att.net", "comcast.net",
            "facebook.com", "gmail.com", "gmx.com", "googlemail.com", "google.com", "hotmail.com", "hotmail.co.uk",
            "mac.com", "me.com", "mail.com", "msn.com", "live.com", "sbcglobal.net", "verizon.net", "yahoo.com",
            "yahoo.co.uk", "rediif.com");
    }

    /**
     * @return array
     */
    static function getBannedDomains() {
        return array(
            "resource.calendar.google.com",
            "ywsync.com"
        );
    }

    /**
     *  "cuneyt@attent.ai" => "attent.ai"
     *
     * @param $emailAddress
     * @return String
     */
    static function getEmailDomain($emailAddress) {
        list($id, $domain) = explode('@', $emailAddress);
        return $domain;
    }

    /**
     * "cuneyt@attent.ai" => ["attent", "ai"]
     */
    static function getEmailDomainSegments($emailAddress) {
        $domain = self::getEmailDomain($emailAddress);
        return explode('.', $domain);
    }
}
