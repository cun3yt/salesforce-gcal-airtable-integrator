<?php
/*
*
* This file is basically responsible for processing meetings from airtable and fetching accounts and its respective account
* from salesforce and than mapping it within airtable base for easy lookup and reference.
*
*/

error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
// we need to include config file so as to get set customer environment for processing attendees
require_once 'config.php';
// we will inform script about the client domain, so that while processing system knows about the client domain and work accordingly.
$strClientDomain = $strClientDomainName;

// We declare some global salesforce access token variables that will be needed to fetching attendees contact data.
$access_token = "";
$instance_url = "";
$strRecordId = "";

// Get the registered salesforce oAuth access entry from customer's airtable base, as it will be needed for reference to fetch contact data.
$arrSalesUser = fnGetSalesUser();
//print("<pre>");
//print_r($arrSalesUser);exit;

if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
{
	$arrSalesTokenDetail = $arrSalesUser[0]['fields'];
	if(is_array($arrSalesTokenDetail) && (count($arrSalesTokenDetail)>0))
	{
		$arrSDetail = json_decode($arrSalesTokenDetail['salesuseraccesstoken'],true);
		$access_token = $arrSDetail['access_token'];
		$instance_url = $arrSDetail['instance_url'];
		$strRecordId = $arrSalesUser[0]['id'];
	}
}
//echo "--".$access_token;
//echo "--".$instance_url;
//echo "--".$strRecordId;
//exit;

/*
*
* Below you will see system fetching unprocessed accounts from customeer's meeting table 
* System will connect to meeting history table and fetch unprocessed accounts from there 
*/
$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;

/*
*
* If there are unprocessed accounts, script will iterate through it and connecte to salesforece and fetch the respective 
* account that matches the account info and than put pulled account in account table in customer airtable base and also add an 
* entry in account history table in customer airtable base.
*
*/

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	// iteration will only be conducted if there are more than 0 unprocessed accounts fetched from customers airtable base.
	
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		// foreach meeting record, system will get hold of attendee email, extract the doamin part from the email address
		// check to see if account with that domain present in airtable accounte table
		// if yes than connect sf to get the latest modified account detail from sf.
		// check if the the there is update in the account info pulled from sf, if yes than make an account detail entry in
		// account history table and return back the created history record id for mapping to meeting history table 
		// if no update in the account details than get the exiting accunt history record id and map it with meeting history record
		// If account detail not present in account airtable than use domain part to pull the latest modified account and its detail, create account record from the pulled info, create a account history record from the pulled account detail info
        // and use the account history record id to map it with meeting history record id.
		// Incase where account details are not found in sf through domain than we find the contact in sf with help of attendee email and get account info from the contact detail and process further as described above once we get hold of account. 
		
		
		//print("<pre>");
		//print_r($arrUser);
		//continue;
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
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']); // we do expload here in-order to get hold of domain name 
		$arrIds = explode(",",$arrUser['fields']['accountno']);
		foreach($arrEmails as $strEm)
		{
			$domain = substr(strrchr($strEm, "@"), 1); // extract the domain part here
			//if($domain != $strClientDomain)
			//if($domain == "mesosphere.io")
				
			//  we only process the domain which are different from customer domain
			if(strtolower($domain) != strtolower($strClientDomain)) 
			{
				// we ignore junk or banned domain, metioned in config file
				if(!in_array(strtolower($domain),$arrBannedDomains)) 
				{
					$intExterNameEmails++;
					$arrDomainInfo = explode(".",$domain);
					$strEmailDomain = $arrDomainInfo[0];  // getting the domain excluding .com
					//echo "--".$strEmailDomain = $domain;
					//$strEmailDomain = "gmail.com";
					//continue;
					
					$strEmail = $strEm;
					$arrAccountDetail = fnGetAccountDetail($strEmailDomain); // check to see if account exists in airtable
					//print("<pre>");
					//print_r($arrAccountDetail);
					//continue;
					if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
					{
						//if account exists than we fetch the latest account modified details from sf
						
						
						//echo "--".$strEmailDomain;
						//echo "--".$arrAccountDetail[0]['AccountNumber'];
						/*else
						{*/
							$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain); // fetching account details from sf.
							
							//print("into insert <pre>");
							//print_r($arrAccountDetailSF);
							//continue;
							if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
							{
								
								// for current record we do not process the same account again
								// this happens when there are more than 1 attendees from same external domain
//system will try to process the domain again but it should not since it has been processed already once, hecnce we make note of proccess domains while process every meeting record							
								if(in_array($arrAccountDetailSF['records'][0]['Name'],$arrAccDomains))
								{
									continue;
								}
								else
								{
									// checking if the pulled account information is different from the exting information
									// if different we get true and we insert into account history
									// if account info is not diff than we use exting account history record for mapping with meeting history table
									$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSF['records']);
									//continue;
									if($IsToBeInserted)
									{
										if($IsToBeInserted == "1")
										{
											
											// here we have ture and we make an entry in account history table
											$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
											//echo "---".$isUpdatedAccountHistory['id'];
											//echo "---".$arrAccountDetailSF['records'][0]['Name'];
											//echo "---".$strARecId;
											
											$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the account history record created so as to mapp it with the meeting record for lookup
											$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
										}
										else
										{
											
											$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
											$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];  // noting the proccessed domain so as to not to process them again
										}
									}
									else
									{
										$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
										$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
									}
									
									
									
								}
							}
							else
							{
								// if account detail could not be found with domain
								// we now try to find it with through attendee email and respective sf contacts
								// we are now pulling contact from sfdc so as to get account info from attendee email
								
								$arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
								//print("<pre>");
								//print_r($arrAccountDetailN);
								//continue;
								
								if(is_array($arrAccountDetailN) && (count($arrAccountDetailN)>0))
								{
									// on getting contact detail we use the account id from contact info and than pull account info from sfdc matching to account id
									
									$arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
									//print("<pre>");
									//print_r($arrAccountDetailSFId);
									//continue;
									
									if(is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records'])>0))
									{
										//print("<pre>");
										//print_r($arrAccountDetailSF);
										
										// for current record we do not process the same account again
								// this happens when there are more than 1 attendees from same external domain
//system will try to process the domain again but it should not since it has been processed already once, hecnce we make note of proccess domains while process every meeting record
										if(in_array($arrAccountDetailSFId['records'][0]['Name'],$arrAccDomains))
										{
											continue;
										}
										else
										{
											// checking if the pulled account information is different from the exting information
											// if different we get true and we insert into account history
											// if account info is not diff than we use exting account history record for mapping with meeting history table
											$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
											$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
											//continue;
											if($IsToBeInserted)
											{
												if($IsToBeInserted == "1")
												{
													// here we have ture and we make an entry in account history table
													
													$isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'],$arrUpdatedAccountHistoryId['id']);
													//$arrUpdatedIds[] = $arrUpdatedAccountHistoryId['fields']['Account ID'];
													$arrUpdatedIds[] = $isUpdatedAccountHistoryId['id']; // noting the account history record created so as to mapp it with the meeting record for lookup
													$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
													$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];  // noting the proccessed domain so as to not to process them again
												}
												else
												{
													$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
													$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
													$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];  // noting the proccessed domain so as to not to process them again
												}
												
											}
											else
											{
												$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
												$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
												$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
											}
										}
									}
									else
									{
										// incase we dont find account with attendee email as well than we note such meeting record id and mark it as processed but not mapped
										// mapped is only marked when account mapping is done
										$arrProcessIds[] = $strARecId;
									}
								}
								else
								{
									// incase we dont find account with attendee email as well than we note such meeting record id and mark it as processed but not mapped
									// mapped is only marked when account mapping is done
									
									$arrProcessIds[] = $strARecId;
								}
							}
						//}
					}
					else
					{
						// if account detail not present in airtable than we have to get the account detail from sf
						
						/*if(in_array($strAccName,$arrAccDomains))
						{
							continue;
						}*/
						/*else
						{*/
							//echo "--".$strEmailDomain;
							//echo "SF ACCS";
							
							// getting updated account detail from sf as airtable is not holding any such account
							$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token,$strEmailDomain);
							print("into insert <pre>");
							print_r($arrAccountDetailSF);
							//continue;
							if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
							{
								
								//print("into insert <pre>");
								//print_r($arrAccountDetailSF);
								//continue;
								
								
								// inserting account info in airtable account table
								$arrUpdatedAccountHistory = fnInsertAccount($arrAccountDetailSF['records'],$strEmailDomain);
								//print("<pre>");
								//print_r($arrUpdatedAccountHistoryId);
								
								// inserting account details in account history table
								// system know airtable is not holding any account so here there is no need to check if account history is holding updated info and whether it needs to be inserted or ignored for insertion
								$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
								
								//$arrUpdatedIds[] = $arrUpdatedAccountHistory['fields']['Account ID'];
								$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the account history record created so as to mapp it with the meeting record for lookup
								$arrId[] = $arrUpdatedAccountHistory['fields']['AccountNumber'];
								$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
							}
							else
							{
								// if account detail could not be found with domain
								// we now try to find it with through attendee email and respective sf contacts
								// we are now pulling contact from sfdc so as to get account info from attendee email
								
								$arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
								//print("<pre>");
								//print_r($arrAccountDetailN);
								//continue;
								
								if(is_array($arrAccountDetailN) && (count($arrAccountDetailN)>0))
								{
									// on getting contact detail we use the account id from contact info and than pull account info from sfdc matching to account id
									
									$arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
									if(is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records'])>0))
									{
										//print("<pre>");
										//print_r($arrAccountDetailSF);
										
										// for current record we do not process the same account again
								// this happens when there are more than 1 attendees from same external domain
//system will try to process the domain again but it should not since it has been processed already once, hecnce we make note of proccess domains while process every meeting record
										
										if(in_array($arrAccountDetailSFId['records'][0]['Name'],$arrAccDomains))
										{
											continue;
										}
										else
										{
											$arrAccountByNameDetail = fnGetAccountDetailByName($arrAccountDetailSFId['records'][0]['Name']);
											if(is_array($arrAccountByNameDetail) && (count($arrAccountByNameDetail)>0))
											{
												continue;
											}
											else
											{
												
												
												$arrUpdatedAccountHistoryId = fnInsertAccount($arrAccountDetailSFId['records'],$strEmailDomain);
												
												// checking if the pulled account information is different from the exting information
												// if different we get true and we insert into account history
												// if account info is not diff than we use exting account history record for mapping with meeting history table
												
												$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
												//continue;
												if($IsToBeInserted)
												{
													if($IsToBeInserted == "1")
													{
														// here we have ture and we make an entry in account history table
														
														$isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'],$arrUpdatedAccountHistoryId['id']);
														//$arrUpdatedIds[] = $arrUpdatedAccountHistoryId['fields']['Account ID'];
														$arrUpdatedIds[] = $isUpdatedAccountHistoryId['id']; // noting the account history record created so as to mapp it with the meeting record for lookup
														$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
														$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
													}
													else
													{
														$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
														$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
														$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
													}
													
												}
												else
												{
													$arrUpdatedIds[] = $IsToBeInserted; // noting the account history record created so as to mapp it with the meeting record for lookup
													$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
													$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name']; // noting the proccessed domain so as to not to process them again
												}
												
											}
										}
									}
									else
									{
										$arrProcessIds[] = $strARecId; // noting meeting record that needs to be flagged as processed
									}
								}
								else
								{
									$arrProcessIds[] = $strARecId; // noting meeting record that needs to be flagged as processed
								}
								//echo "No Account Present Other domain";
								//continue;
							}
						//}
					}
				}				
				//exit;
			}
			else
			{
				continue;
			}
		}
		
		if(is_array($arrProcessIds) && (count($arrProcessIds)==$intExterNameEmails))
		{
			$boolUpdateAccount = fnUpdateAccountProcessedRecord($strARecId); // updating meeting record and marking it as processed 
		}
		else
		{
			if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0))
			{
				$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrUpdatedIds,$arrId); // updating meeting record and marking it as mapped 
			}
		}
	}
}

/*
* Function to connect to airtable base and get unprocessed accounts from meeting table in airtable
* Unproceed accounts are pulled from account_not_processed view under meeting history table
* we process 5 records in 1 go
*/


function fnGetProcessAccounts()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	echo "--".$strDate = strtotime(date("Y-m-d"));
	$base = $strAirtableBase;
	$table = 'Meeting%20History';
	$strApiKey = $strAirtableApiKey;
	//$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=50&view=".rawurlencode("account_not_processed");
	$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=5&view=".rawurlencode("account_not_processed");
	//$url .= '&filterByFormula=('.rawurlencode("{meetingprocesstime}<='".$strDate."'").')';	
	
	//echo $url;exit; 
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
	if(!$result)
	{
		echo 'error:' . curl_error($ch);
		return false;
	}
	else
	{
		$arrResponse = json_decode($result,true);
		//print("<pre>");
		//print_r($arrResponse);
		//exit;
		
		if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
		{
			$arrSUser = $arrResponse['records'];
			return $arrSUser;
			
		}
		else
		{
			return false;
		}
	}
}

/*
Function to connect to airtable base and get customers salesforce OAuth access
*/

function fnGetSalesUser()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	$base =  $strAirtableBase;
	$table = 'salesuser';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table;
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
	if(!$result)
	{
		//echo "HI";exit;
		echo 'error:' . curl_error($ch);
		
		return false;
	}
	else
	{
		$arrResponse = json_decode($result,true);
		if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
		{
			$arrSUser = $arrResponse['records'];
			return $arrSUser;
			
		}
		else
		{
			return false;
		}
	}
}

/*
* Function to check fetched account details from sf and existing account detail in attendee history table are same or diff
* If detail dont match, means there is update in account detail info and we return true, so as to make a new entry record in attendee 
* history table
* Other wise we return the existing attendee history record id for mapping 
*/

function fnCheckIfAccountHistoryToBeInserted($arrAccountHistory = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		
		//print("History check -<pre>");
		//print_r($arrAccountHistory);
		

		$base = $strAirtableBase;
		$table = 'Account%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("latestfirst");
		echo "---".$url .= '&filterByFormula=('.rawurlencode("{Account Name}='".$arrAccountHistory[0]['Name']."'").')';

		$authorization = "Authorization: Bearer ".$strApiKey;
		$srtF = json_encode($arrFields);
		//exit;
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		echo "---".$response = curl_exec($ch);
		if(!$response)
		{
			echo curl_error($ch);
			curl_close($ch);
			return "1";
		}
		else
		{
			curl_close($ch);
			$arrResponse = json_decode($response,true);
			print("db history - <pre>");
			print_r($arrResponse);
			//exit;
			
			if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
			{
				$arrSUser = $arrResponse['records'];
				$strAccountStatus = $arrSUser[0]['fields']['Account Status'];
				$strEmployees = $arrSUser[0]['fields']['# Employees'];
				$strArr = $arrSUser[0]['fields']['ARR'];
				$strBCycle = $arrSUser[0]['fields']['Billing Cycle'];
				$strRenewalDate = strtotime($arrSUser[0]['fields']['Renewal Date']);
				$strBcity = $arrSUser[0]['fields']['Billing City'];
				
				if($strAccountStatus != $arrAccountHistory[0]['Account_Status__c'])
				{
					//echo "AAAA";	
					return "1";
				}
				else
				{
					if($strEmployees != $arrAccountHistory[0]['NumberOfEmployees'])
					{
						//echo "BBBBB";
						return "1";
					}
					else
					{
						if($strArr != $arrAccountHistory[0]['ARR__c'])
						{
							//echo "CCC";
							
							return "1";
						}
						else
						{
							if($strBCycle != $arrAccountHistory[0]['Billing_Cycle__c'])
							{
								//echo "DDDD";
								
								return "1";
							}
							else
							{
								if($strRenewalDate != strtotime($arrAccountHistory[0]['Subscription_End_Date__c']))
								{
									///echo "EEEE";
									return "1";
								}
								else
								{
									if($strBcity != $arrAccountHistory[0]['BillingCity'])
									{
										//echo "FFFF";
										return "1";
									}
									else
									{
										return $arrSUser[0]['id'];
									}
								}
							}
						}
					}
				}
			}
			else
			{
				return "1";
			}
		}
		
	}
	else
	{
		return "1";
	}
}

/*
* Function mark meeting record as processed not as mapped
* It takes input as meeting record id
* Meeting record is not marked as mapped if system does not find and account info in sfdc, meaining system processed the
* record but could not mapp it in absence of details
* On completion of opertation it return true or false
*/

function fnUpdateAccountProcessedRecord($strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		
		
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['account_processed'] = "processed";
		if(is_array($strAId) && (count($strAId)>0))
		{
			$arrFields['fields']['accountno'] = implode(",",$strAId);
		}
		
		//print("<pre>");
		//print_r($arrFields);
		//return;
		
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
		
		if(!$response)
		{
			echo curl_error($curl);
		}
		curl_close($curl);
		$jsonResponse =  json_decode($response,true);
		//print("<pre>");
		//print_r($jsonResponse);
		if(is_array($jsonResponse) && (count($jsonResponse)>0))
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	else
	{
		return false;
	}

}

/*
* Function to mark meeting record as mapped
* It takes input as meeting record id and account history record it that is to be mapped with
* On completion of opertation it return true or false
*/

function fnUpdateAccountRecord($strRecId,$strId,$strAId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['Account'] = $strId;
		$arrFields['fields']['account_processed'] = "mapped";
		if(is_array($strAId) && (count($strAId)>0))
		{
			$arrFields['fields']['accountno'] = implode(",",$strAId);
		}
		
		//print("<pre>");
		//print_r($arrFields);
		//return;
		
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
		
		if(!$response)
		{
			echo curl_error($curl);
		}
		curl_close($curl);
		$jsonResponse =  json_decode($response,true);
		//print("<pre>");
		//print_r($jsonResponse);
		if(is_array($jsonResponse) && (count($jsonResponse)>0))
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	else
	{
		return false;
	}

}

/*
* Function to connect sf and fetch contact details
* It takes attendee email as input parameter and return the sf contact record as out put parameter
*/

function fnGetContactDetailFromSf($instance_url, $access_token,$strEmail = "")
{
	if($strEmail)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		$query = "SELECT Name, Id, Email, Title, AccountId from Contact WHERE Email = '".$strEmail."' LIMIT 1";
		 
		 $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: OAuth $access_token"));

		$json_response = curl_exec($curl);
		if(!$json_response)
		{
			echo "--error---".curl_error($curl);
		}
		curl_close($curl);

		
		$response = json_decode($json_response, true);
		
		
		
		return $response;
	}
	else
	{
		return false;
	}
}

/*
* Function look for contact detail in airtable
* It takes attendee email as input parameter and return the airtable attendee record as out put parameter
*/

function fnGetContactDetail($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{
		$base = $strAirtableBase;
		$table = 'Attendees%20in%20SFDC';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{Email}='".$strEmail."'").')';	
		echo "--".$url; 
		//continue;
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
		if(!$result)
		{
			echo 'error:' . curl_error($ch);
			return false;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			//print("<pre>");
			//print_r($arrResponse);
			//exit;
			
			if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
			{
				$arrSUser = $arrResponse['records'];
				return $arrSUser;
				
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}

/*
* Function to insert account info in account airtable
* It takes account info record as input parameter
*/

function fnInsertAccount($arrAccountHistory = array(),$strDomain = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		//print("into insert <pre>");
		//print_r($arrAccountHistory);
		
		$base = $strAirtableBase;
		$table = 'Accounts';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrAccountHistory[0]['Id'])
		{
			$arrFields['fields']['Account ID'] = $arrAccountHistory[0]['Id'];
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['Account'] = $arrAccountHistory[0]['Name'];
		}

		if($strDomain)
		{
			$arrFields['fields']['Account Domain'] = $strDomain;
		}
		
		
		//print("<pre>");
		//print_r($arrFields);
		//return;
		
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
		//print("<pre>");
		//print_r($jsonResponse);
		if(is_array($jsonResponse) && (count($jsonResponse)>0))
		{
			return $jsonResponse;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

/*
* Function to insert account detail info in account history airtable
* It takes account detail record as input parameter and account record id so as to mapp account history to account 
*/

function fnInsertAccountHistory($arrAccountHistory = array(),$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		$base = $strAirtableBase;
		$table = 'Account%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($strRecId)
		{
			$arrFields['fields']['Account ID'] = array($strRecId);
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['Account Name'] = $arrAccountHistory[0]['Name'];
		}
		
		if($arrAccountHistory[0]['NumberOfEmployees'])
		{
			$arrFields['fields']['# Employees'] = $arrAccountHistory[0]['NumberOfEmployees'];
		}
		
		if($arrAccountHistory[0]['BillingCity'])
		{
			$arrFields['fields']['Billing City'] = $arrAccountHistory[0]['BillingCity'];
		}
		
		if($arrAccountHistory[0]['ARR__c'])
		{
			$arrFields['fields']['ARR'] = $arrAccountHistory[0]['ARR__c'];
		}
		
		if($arrAccountHistory[0]['Billing_Cycle__c'])
		{
			$arrFields['fields']['Billing Cycle'] = $arrAccountHistory[0]['Billing_Cycle__c'];
		}
		
		if($arrAccountHistory[0]['Account_Status__c'])
		{
			$arrFields['fields']['Account Status'] = $arrAccountHistory[0]['Account_Status__c'];
		}
		
		if($arrAccountHistory[0]['Subscription_End_Date__c'])
		{
			$arrFields['fields']['Renewal Date'] = date("m/d/Y",strtotime($arrAccountHistory[0]['Subscription_End_Date__c']));
		}
		
		
		
		
		//print("<pre>");
		//print_r($arrFields);
		
		
		$srtF = json_encode($arrFields);
		//exit;
		
		
		$curl = curl_init($url);
		// Accept any server (peer) certificate on dev envs
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $srtF);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json",$authorization));
		$info = curl_getinfo($curl);
		echo "---".$response = curl_exec($curl);
		if(!$response)
		{
			echo curl_error($curl);
			return false;
		}
		else
		{
			curl_close($curl);
			$jsonResponse =  json_decode($response,true);
			//print("<pre>");
			//print_r($jsonResponse);
			if(is_array($jsonResponse) && (count($jsonResponse)>0))
			{
				return $jsonResponse;
			}
			else
			{
				return false;
			}
		}
		
	}
	else
	{
		return false;
	}
}


/*
* Function to fetch account detail from sf
* It takes input parameter as domain name and matches it website field under account object in account table
* it return the account record on finding the matct otherwise false
*/

function fnGetAccountDetailFromSf($instance_url, $access_token,$strAccDomain = "")
{
	if($strAccDomain)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue, Account_Status__c, Billing_Cycle__c, Subscription_End_Date__c, ARR__c from Account WHERE Website LIKE '%".$strAccDomain."%' ORDER BY lastmodifieddate DESC LIMIT 1";
		
		//$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue from Account WHERE Website LIKE '%".$strAccDomain."' LIMIT 1";
		 
		 $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: OAuth $access_token"));

		$json_response = curl_exec($curl);
		if(!$json_response)
		{
			echo "--error---".curl_error($curl);
		}
		curl_close($curl);

		
		$response = json_decode($json_response, true);
		
		
		
		return $response;
	}
	else
	{
		return false;
	}
}

/*
* Function to fetch account detail from sf based on account id
* It takes input parameter as account id and matches it id field under account object in account table
* it return the account record on finding the matct otherwise false
*/

function fnGetAccountDetailFromSfId($instance_url, $access_token,$strId = "")
{
	if($strId)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue from Account WHERE Id = '".$strId."' LIMIT 1";
		 
		 $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: OAuth $access_token"));

		$json_response = curl_exec($curl);
		if(!$json_response)
		{
			echo "--error---".curl_error($curl);
		}
		curl_close($curl);

		
		$response = json_decode($json_response, true);
		
		
		
		return $response;
	}
	else
	{
		return false;
	}
}

/*
* Function to fetch account detail from airtable
* It takes input parameter as account name and matches it name field under account table in airtable
* it return the account record on finding the matct otherwise false
*/

function fnGetAccountDetailByName($strAccName = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strAccName)
	{
		$base = $strAirtableBase;
		$table = 'Accounts';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{Account}='".$strAccName."'").')';	
		//echo "--".$url; 
		//continue;
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
		if(!$result)
		{
			echo 'error:' . curl_error($ch);
			return false;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			//print("<pre>");
			//print_r($arrResponse);
			//exit;
			
			if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
			{
				$arrSUser = $arrResponse['records'];
				return $arrSUser;
				
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


/*
* Function to fetch account detail from airtable
* It takes input parameter as domain name and matches it domain name field under account table in airtable
* it return the account record on finding the match otherwise false
*/

function fnGetAccountDetail($strAccDomain = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strAccDomain)
	{
		$base = $strAirtableBase;
		$table = 'Accounts';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{Account Domain}='".$strAccDomain."'").')';	
		//echo "--".$url; 
		//continue;
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
		if(!$result)
		{
			echo 'error:' . curl_error($ch);
			return false;
		}
		else
		{
			$arrResponse = json_decode($result,true);
			//print("<pre>");
			//print_r($arrResponse);
			//exit;
			
			if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
			{
				$arrSUser = $arrResponse['records'];
				return $arrSUser;
				
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}
?>