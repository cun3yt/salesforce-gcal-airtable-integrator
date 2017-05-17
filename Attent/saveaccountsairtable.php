<?
/**
 * This file is responsible for processing meetings and fetching accounts and its respective account
 * from SFDC and than mapping it within DB for easy lookup and reference.
 *
 * @todo change the name of the file and crontab entry
 *
 * @todo delete processcalendar.php entry on crontab
 */
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();

require_once 'config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

$access_token = "";
$instance_url = "";
$strRecordId = "";

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;
use DataModels\DataModels\Base\Customer as Customer;

/**
 * @var $customer Customer
 */
list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);
$sfdcIntegrations = Helpers::getIntegrations($customer, CustomerContactIntegration::SFDC);

if(count($sfdcIntegrations) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_ERROR);
    die;
}

$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
if (is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail) > 0)) {
    $arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'], true);
    $access_token = $arrSDetail['access_token'];
    $instance_url = $arrSDetail['instance_url'];
    $strRecordId = $arrSalesUser[0]['id'];
}

$arrGcalUser = fnGetProcessAccounts();

/**
 * If there are unprocessed accounts, script will iterate through it and connecte to salesforece and fetch the respective
 * account that matches the account info and than put pulled account in account table in customer airtable base and also add an
 * entry in account history table in customer airtable base.
 */
if (is_array($arrGcalUser) && (count($arrGcalUser) > 0)) {
    $intFrCnt = 0;
    foreach ($arrGcalUser as $arrUser) {
        /**
         * foreach meeting record, system will get hold of attendee email, extract the doamin part from the email
         * address check to see if account with that domain present in airtable accounte table
         * if yes than connect sf to get the latest modified account detail from sf.
         * check if the the there is update in the account info pulled from sf, if yes than make
         * an account detail entry in account history table and return back the created history record id
         * for mapping to meeting history table if no update in the account details than get
         * the exiting account history record id and map it with meeting history record.
         * If account detail not present in account airtable than use domain part to pull
         * the latest modified account and its detail, create account record from the pulled info,
         * create a account history record from the pulled account detail info and use the account history
         * record id to map it with meeting history record id.
         * In case where account details are not found in sf through domain than we find the contact in sf
         * with help of attendee email and get account info from the contact detail and process further
         * as described above once we get hold of account.
         */
        $arrUpdatedIds = array();
        $arrAccDomains = array();
        $arrProcessIds = array();
        $strAccName = "";
        $arrId = array();
        $intFrCnt++;
        $intExterNameEmails = 0;
        $strARecId = $arrUser['id'];
        $strMeetingName = $arrUser['fields']['Meeting Name'];
        $arrUser['fields']['accountno'];
        $arrEmails = explode(",", $arrUser['fields']['Attendee Email(s)']);
        $arrIds = explode(",", $arrUser['fields']['accountno']);

        foreach ($arrEmails as $strEm) {
            $domain = substr(strrchr($strEm, "@"), 1);

            if( !(strtolower($domain) != strtolower($customer->getEmailDomain())) ){
                continue;
            }

            if( in_array(strtolower($domain), Helpers::getBannedDomains()) ){
                continue;
            }

            $intExterNameEmails++;
            $arrDomainInfo = explode(".", $domain);
            $strEmailDomain = $arrDomainInfo[0];
            $strEmail = $strEm;
            $arrAccountDetail = fnGetAccountDetail($strEmailDomain);

            if (is_array($arrAccountDetail) && (count($arrAccountDetail) > 0)) {
                $arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
                if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                    if (in_array($arrAccountDetailSF['records'][0]['Name'], $arrAccDomains)) {
                        continue;
                    } else {
                        $IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSF['records']);
                        if ($IsToBeInserted) {
                            if ($IsToBeInserted == "1") {
                                $isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'], $arrAccountDetail[0]['id']);
                                $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                                $arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
                            } else {
                                $arrUpdatedIds[] = $IsToBeInserted;
                                $arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
                            }
                        } else {
                            $arrUpdatedIds[] = $IsToBeInserted;
                            $arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
                        }
                    }
                } else {
                    $arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);

                    if (is_array($arrAccountDetailN) && (count($arrAccountDetailN) > 0)) {
                        $arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);

                        if (is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records']) > 0)) {

                            if (in_array($arrAccountDetailSFId['records'][0]['Name'], $arrAccDomains)) {
                                continue;
                            } else {
                                $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                $IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);

                                if ($IsToBeInserted) {
                                    if ($IsToBeInserted == "1") {
                                        $isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'], $arrUpdatedAccountHistoryId['id']);
                                        $arrUpdatedIds[] = $isUpdatedAccountHistoryId['id'];
                                        $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                        $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                    } else {
                                        $arrUpdatedIds[] = $IsToBeInserted;
                                        $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                        $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                    }
                                } else {
                                    $arrUpdatedIds[] = $IsToBeInserted;
                                    $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                    $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                }
                            }
                        } else {
                            $arrProcessIds[] = $strARecId;
                        }
                    } else {
                        $arrProcessIds[] = $strARecId;
                    }
                }
            } else {
                $arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
                print("into insert <pre>");
                print_r($arrAccountDetailSF);
                if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                    $arrUpdatedAccountHistory = fnInsertAccount($arrAccountDetailSF['records'], $strEmailDomain);
                    $isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
                    $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                    $arrId[] = $arrUpdatedAccountHistory['fields']['AccountNumber'];
                    $arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
                } else {
                    $arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
                    if (is_array($arrAccountDetailN) && (count($arrAccountDetailN) > 0)) {
                        $arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
                        if (is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records']) > 0)) {
                            if (in_array($arrAccountDetailSFId['records'][0]['Name'], $arrAccDomains)) {
                                continue;
                            } else {
                                $arrAccountByNameDetail = fnGetAccountDetailByName($arrAccountDetailSFId['records'][0]['Name']);
                                if (is_array($arrAccountByNameDetail) && (count($arrAccountByNameDetail) > 0)) {
                                    continue;
                                } else {
                                    $arrUpdatedAccountHistoryId = fnInsertAccount($arrAccountDetailSFId['records'], $strEmailDomain);
                                    $IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
                                    if ($IsToBeInserted) {
                                        if ($IsToBeInserted == "1") {
                                            $isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'], $arrUpdatedAccountHistoryId['id']);
                                            $arrUpdatedIds[] = $isUpdatedAccountHistoryId['id'];
                                            $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                            $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                        } else {
                                            $arrUpdatedIds[] = $IsToBeInserted;
                                            $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                            $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                        }
                                    } else {
                                        $arrUpdatedIds[] = $IsToBeInserted;
                                        $arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
                                        $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                    }
                                }
                            }
                        } else {
                            $arrProcessIds[] = $strARecId;
                        }
                    } else {
                        $arrProcessIds[] = $strARecId;
                    }
                }
            }
        }

        if (is_array($arrProcessIds) && (count($arrProcessIds) == $intExterNameEmails)) {
            $boolUpdateAccount = fnUpdateAccountProcessedRecord($strARecId);
        } else {
            if (is_array($arrUpdatedIds) && (count($arrUpdatedIds) > 0)) {
                $boolUpdateAccount = fnUpdateAccountRecord($strARecId, $arrUpdatedIds, $arrId);
            }
        }
    }
}

/**
 * Function to connect to airtable base and get unprocessed accounts from meeting table in airtable
 * Unproceed accounts are pulled from account_not_processed view under meeting history table
 * we process 5 records in 1 go
 *
 * @return bool
 */
function fnGetProcessAccounts() {
    global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
    echo "--" . $strDate = strtotime(date("Y-m-d"));
    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint . $base . '/' . $table . "?maxRecords=5&view=" . rawurlencode("account_not_processed");
    $authorization = "Authorization: Bearer " . $strApiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    //execute post
    $result = curl_exec($ch);//exit;

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

function fnCheckIfAccountHistoryToBeInserted($arrAccountHistory = array()) {
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

function fnUpdateAccountProcessedRecord($strRecId) {
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

function fnUpdateAccountRecord($strRecId, $strId, $strAId) {
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

function fnGetContactDetailFromSf($instance_url, $access_token, $strEmail = "") {
    if(!$strEmail) {
        return false;
    }

    $query = "SELECT Name, Id, Email, Title, AccountId from Contact WHERE Email = '" . $strEmail . "' LIMIT 1";
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

function fnGetContactDetail($strEmail = "") {
    global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

    if(!$strEmail) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Attendees%20in%20SFDC';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint . $base . '/' . $table;
    $url .= '?filterByFormula=(' . rawurlencode("{Email}='" . $strEmail . "'") . ')';
    echo "--" . $url;
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
    if(isset($arrResponse['records']) && (count($arrResponse['records']) > 0)) {
        $arrSUser = $arrResponse['records'];
        return $arrSUser;
    }
    return false;
}

function fnInsertAccount($arrAccountHistory = array(), $strDomain = "") {
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

function fnInsertAccountHistory($arrAccountHistory = array(), $strRecId) {
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

function fnGetAccountDetailFromSf($instance_url, $access_token, $strAccDomain = "") {
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

function fnGetAccountDetailFromSfId($instance_url, $access_token, $strId = "") {
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

function fnGetAccountDetailByName($strAccName = "") {
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

function fnGetAccountDetail($strAccDomain = "") {
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
