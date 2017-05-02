<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();

require_once('config.php');
require_once('../libraries/Helpers.php');

$access_token = "";
$instance_url = "";
$strRecordId = "";
$arrSalesUser = Helpers::fnGetSalesUser();
if (is_array($arrSalesUser) && (count($arrSalesUser) > 0)) {
    $arrSalesTokenDetail = $arrSalesUser[0]['fields'];
    if (is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail) > 0)) {
        $arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'], true);
        $access_token = $arrSDetail['access_token'];
        $instance_url = $arrSDetail['instance_url'];
        $strRecordId = $arrSalesUser[0]['id'];
    }
}
$strClientDomain = $strClientDomainName;
$arrGcalUser = fnGetProcessAccounts();

if (is_array($arrGcalUser) && (count($arrGcalUser) > 0)) {
    $intFrCnt = 0;
    foreach ($arrGcalUser as $arrUser) {
        $arrUpdatedIds = array();
        $arrProcessIds = array();
        $intFrCnt++;
        $intAccCnts = 0;
        $strARecId = $arrUser['id'];
        $arrEmails = explode(",", $arrUser['fields']['Attendee Email(s)']);
        $strEmail = $strEm;
        $arrAccountDetailold = $arrUser['fields']['Account'];
        $arrAccountDetail = $arrUser['fields']['acoount_id'];

        if (is_array($arrAccountDetail) && (count($arrAccountDetail) > 0)) {
            foreach ($arrAccountDetail as $arrAccount) {
                if (!$arrAccount) {
                    continue;
                }

                $intAccCnts++;
                $arrAccDetail = fnGetAccountDetail($arrAccount);
                $arrOpportunityDetail = fnGetOpportunityDetail($arrAccDetail['fields']['Account']);
                if (is_array($arrOpportunityDetail) && (count($arrOpportunityDetail) > 0)) {
                    $arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
                    if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                        $arrOppHIds = $arrOpportunityDetail[0]['fields']['Opportunity History'];
                        echo "---" . $IsToBeInserted = fnCheckIfOppHistoryToBeInserted($arrAccountDetailSF['records']);
                        if ($IsToBeInserted) {
                            if ($IsToBeInserted == "1") {
                                $isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'], $$arrOpportunityDetail[0]['id']);
                                if ($isUpdatedAccountHistory['id']) {
                                    $arrOppHIds[] = $isUpdatedAccountHistory['id'];
                                    $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                                }
                            } else {
                                $arrOppHIds[] = $IsToBeInserted;
                                $arrUpdatedIds[] = $IsToBeInserted;
                            }
                        } else {
                            $arrOppHIds[] = $IsToBeInserted;
                            $arrUpdatedIds[] = $IsToBeInserted;
                        }
                        $boolUpdateAccount = fnUpdateAccountRecord($arrOpportunityDetail[0]['id'], $arrOppHIds);
                    }
                } else {
                    $arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
                    if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                        $arrUpdatedAccountHistory = fnInsertOpportunity($arrAccountDetailSF['records'], $arrAccDetail['id']);
                        $isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
                        $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                        $boolUpdateAccount = fnUpdateAccountRecord($arrUpdatedAccountHistory['id'], array($isUpdatedAccountHistory['id']));
                    } else {
                        $arrProcessIds[] = $strARecId;
                    }
                }
            }
            if (is_array($arrProcessIds) && (count($arrProcessIds) == $intAccCnts)) {
                $boolUpdateAccount = fnUpdateOppProcessedRecord($strARecId);
            } else if(is_array($arrUpdatedIds) && (count($arrUpdatedIds) > 0)) {
                $arrUpdatedIds = array_unique($arrUpdatedIds);
                $boolUpdateAccount = fnUpdateMeetingRecord($strARecId, $arrUpdatedIds);
            }
        }
    }
}

function fnGetProcessAccounts() {
    global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;
    echo "--" . $strDate = strtotime(date("Y-m-d"));
    $base = $strAirtableBase;
    $table = 'Meeting%20History';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint . $base . '/' . $table . "?maxRecords=5&view=" . rawurlencode("opportunity_not_processed");
    $url .= '&filterByFormula=(' . rawurlencode("{meetingprocesstime}<='" . $strDate . "'") . ')';
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

function fnUpdateOppProcessedRecord($strRecId) {
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

function fnCheckIfOppHistoryToBeInserted($arrAccountHistory = array()) {
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

function fnUpdateMeetingRecord($strRecId, $strId) {
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

function fnUpdateAccountRecord($strRecId, $strId) {
    global $strAirtableBase, $strAirtableApiKey, $strAirtableBaseEndpoint;

    if(!$strRecId) {
        return false;
    }

    $base = $strAirtableBase;
    $table = 'Opportunities';
    $strApiKey = $strAirtableApiKey;
    $airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
    $url = $strAirtableBaseEndpoint . $base . '/' . $table . '/' . $strRecId;
    $authorization = "Authorization: Bearer " . $strApiKey;
    //$arrFields['fields']['Account'] = array($strId)$strName;
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

function fnInsertOpportunity($arrAccountHistory = array(), $strId = "") {
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

function fnInsertOppHistory($arrAccountHistory = array(), $strRecId) {
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

function fnGetOpportunityDetailFromSf($instance_url, $access_token, $strAccId = "") {
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

function fnGetOpportunityDetail($strAccId = "") {
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

function fnGetAccountDetail($strAccId = "") {
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
