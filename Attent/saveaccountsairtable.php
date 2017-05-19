<?
/**
 * This file is responsible for processing meetings and fetching accounts and its respective account
 * from SFDC and than mapping it within DB for easy lookup and reference.
 *
 * @todo change the name of the file and crontab entry
 *
 * @todo delete processcalendar.php entry on crontab
 */
error_reporting(E_ALL);

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/SessionSingleton.php");
SessionSingleton::start();

require_once 'config.php';
require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/Helpers.php");

Helpers::setDebugParam($isDebugActive);

$access_token = "";
$instance_url = "";
$strRecordId = "";

use DataModels\DataModels\CustomerContactIntegration as CustomerContactIntegration;
use DataModels\DataModels\Customer as Customer;

/**
 * @var $customer Customer
 */
list($customer, $contacts) = Helpers::loadCustomerData($strClientDomainName);
$SFDCIntegrations = Helpers::getIntegrations($customer, CustomerContactIntegration::SFDC);

if(count($SFDCIntegrations) <= 0) {
    trigger_error("No SFDC integration is found in ".__FILE__, E_USER_ERROR);
    die;
}

$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
if (is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail) > 0)) {
    $arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'], true);
    $access_token = $arrSDetail['access_token'];
    $instance_url = $arrSDetail['instance_url'];
    $strRecordId = $arrSalesUser[0]['id'];
}

$arrGcalUser = Helpers::fnGetProcessAccounts("account_not_processed");

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
            $arrAccountDetail = Helpers::fnGetAccountDetailByAccountDomain($strEmailDomain);

            if (is_array($arrAccountDetail) && (count($arrAccountDetail) > 0)) {
                $arrAccountDetailSF = Helpers::fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
                if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                    if (in_array($arrAccountDetailSF['records'][0]['Name'], $arrAccDomains)) {
                        continue;
                    } else {
                        $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSF['records']);
                        if ($IsToBeInserted) {
                            if ($IsToBeInserted == "1") {
                                $isUpdatedAccountHistory = Helpers::fnInsertAccountHistory($arrAccountDetailSF['records'], $arrAccountDetail[0]['id']);
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
                    $arrAccountDetailN = Helpers::fnGetContactDetailFromSf($instance_url, $access_token, $strEm, false);

                    if (is_array($arrAccountDetailN) && (count($arrAccountDetailN) > 0)) {
                        $arrAccountDetailSFId = Helpers::fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);

                        if (is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records']) > 0)) {

                            if (in_array($arrAccountDetailSFId['records'][0]['Name'], $arrAccDomains)) {
                                continue;
                            } else {
                                $arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
                                $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);

                                if ($IsToBeInserted) {
                                    if ($IsToBeInserted == "1") {
                                        $isUpdatedAccountHistoryId = Helpers::fnInsertAccountHistory($arrAccountDetailSFId['records'], $arrUpdatedAccountHistoryId['id']);
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
                $arrAccountDetailSF = Helpers::fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
                print("into insert <pre>");
                print_r($arrAccountDetailSF);
                if (is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records']) > 0)) {
                    $arrUpdatedAccountHistory = Helpers::fnInsertAccount($arrAccountDetailSF['records'], $strEmailDomain);
                    $isUpdatedAccountHistory = Helpers::fnInsertAccountHistory($arrAccountDetailSF['records'], $arrUpdatedAccountHistory['id']);
                    $arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
                    $arrId[] = $arrUpdatedAccountHistory['fields']['AccountNumber'];
                    $arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
                } else {
                    $arrAccountDetailN = Helpers::fnGetContactDetailFromSf($instance_url, $access_token, $strEm, false);
                    if (is_array($arrAccountDetailN) && (count($arrAccountDetailN) > 0)) {
                        $arrAccountDetailSFId = Helpers::fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
                        if (is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records']) > 0)) {
                            if (in_array($arrAccountDetailSFId['records'][0]['Name'], $arrAccDomains)) {
                                continue;
                            } else {
                                $arrAccountByNameDetail = Helpers::fnGetAccountDetailByName($arrAccountDetailSFId['records'][0]['Name']);
                                if (is_array($arrAccountByNameDetail) && (count($arrAccountByNameDetail) > 0)) {
                                    continue;
                                } else {
                                    $arrUpdatedAccountHistoryId = Helpers::fnInsertAccount($arrAccountDetailSFId['records'], $strEmailDomain);
                                    $IsToBeInserted = Helpers::fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
                                    if ($IsToBeInserted) {
                                        if ($IsToBeInserted == "1") {
                                            $isUpdatedAccountHistoryId = Helpers::fnInsertAccountHistory($arrAccountDetailSFId['records'], $arrUpdatedAccountHistoryId['id']);
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
            $boolUpdateAccount = Helpers::fnUpdateAccountProcessedRecord($strARecId);
        } else {
            if (is_array($arrUpdatedIds) && (count($arrUpdatedIds) > 0)) {
                $boolUpdateAccount = Helpers::fnUpdateAccountRecordForSFDC($strARecId, $arrUpdatedIds, $arrId);
            }
        }
    }
}
