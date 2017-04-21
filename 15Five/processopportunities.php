<?php
/*
*
* This file is basically responsible for processing meetings from airtable and fetching opportunities and its respective
* opportunity detail from salesforce and than mapping it within meeting history airtable base for easy lookup and reference.
*
*/
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
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
	// if we get salesforce OAuth access data we iterate and use the access data and assign it out global variables declared.
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
* Below you will see system fetching unprocessed opportunities from customeer's meeting table 
* System will connect to meeting history table and fetch unprocessed opportunities from there 
*/

$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;



/*
*
* If there are unprocessed opportunities, script will iterate through it and connecte to salesforece and fetch the respective 
* opportunities that matches the opportunity and than put pulled opportunity in opportunity table in customer airtable base 
* and also add an entry in opportunity history table in customer airtable base.
*
*/

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	
	// iteration will only be conducted if there are more than 0 unprocessed opportunity fetched from customers airtable base.
	// they system will extract the mapped account from the meeting record
	// system will use the extracted account record id to pull the complete account detail from the airtable
	// System will than use the account id from the pulled account detail and connect to salesforce to fetch the latest modified opportunity for that account.
	// On pulling the opportunity from salesforce for account, system check if it there is any change in opportunity, if yes than system enters a new opportunity detail record in opportunity history table other wise use the existing one and map it with meeting record.
	// When this is done system marks the opportunity_processed flag in airtable as yes.
	// If there is no account id mapped for the meeting history record than on such instance system marks the meeting record as procssed
	
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	
	
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		//print("<pre>");
		//print_r($arrUser);
		//continue;
		$arrUpdatedIds = array();
		$arrProcessIds = array();
		$intFrCnt++;
		$intAccCnts = 0;
		$strARecId = $arrUser['id'];
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);
		$strEmail = $strEm;
		$arrAccountDetailold = $arrUser['fields']['Account'];
		// we pull the account information ref id from the meeting record
		// every meeting record might have more than 1 account, so we pull it in array format, so that it is easy to iterate and do further processing.
		$arrAccountDetail = $arrUser['fields']['acoount_id'];
		
		
		//print("<pre>");
		//print_r($arrAccountfls);
		//continue;
		//print("<pre>");
		//print_r($arrAccountDetail);
		//continue;
		if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
		{
			// We only iterate thorugh if there are more than 0 accounts mapped with a meeting record.
			
			foreach($arrAccountDetail as $arrAccount)
			{
				// Now we iterate through each account to check and get their respective account details from airtable(for mapped accounts the account detail will always be present in airtable)
				// Check if account pulled data is not empty. we process the records only for non empty account records
				if($arrAccount)
				{
					//echo "--".$arrAccount;
					//continue;
					$intAccCnts++;
					// we use the account record id to pull the account details from that account table in airtable
					$arrAccDetail = fnGetAccountDetail($arrAccount);
					//print("Account details <pre>");
					//print_r($arrAccDetail);
					//continue;
					//exit;
					
					
					// From the pulled account detail, we connect check if opportunity is already created and present in airtable
					$arrOpportunityDetail = fnGetOpportunityDetail($arrAccDetail['fields']['Account']);
					//print("<pre>");
					//print_r($arrOpportunityDetail);
					//continue;
					if(is_array($arrOpportunityDetail) && (count($arrOpportunityDetail)>0))
					{
						//print("<pre>");
						//print_r($arrAccountDetail);
						
						// considering system found the opportunity detail from airtable, system than connects to sfdc to pull the latest modified opportunity detail information
						$arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
						//print("<pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							//print("<pre>");
							//print_r($arrAccountDetailSF);
							
							
							 
							
							$arrOppHIds = $arrOpportunityDetail[0]['fields']['Opportunity History'];
							
							//we now check if opportunity details fetched from sfdc, has some updated values or not
							// if yes than we add it into opportunity history table otherwise we proceed to next opportunity
							// system makes note of the opportunity history record if it was created for mapping 
							echo "---".$IsToBeInserted = fnCheckIfOppHistoryToBeInserted($arrAccountDetailSF['records']);
							if($IsToBeInserted)
							{
								if($IsToBeInserted == "1")
								{
									
									$isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'],$$arrOpportunityDetail[0]['id']);
									if($isUpdatedAccountHistory['id'])
									{
										$arrOppHIds[] = $isUpdatedAccountHistory['id']; // noting the opportunity history record created so as to mapp it with the opportunity record for lookup
										$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the opportunity history record created so as to mapp it with the meeting record for lookup
									}
								}
								else
								{
									$arrOppHIds[] = $IsToBeInserted; // noting the opportunity history record created so as to mapp it with the opportunity record for lookup
									$arrUpdatedIds[] = $IsToBeInserted; // noting the opportunity history record created so as to mapp it with the meeting record for lookup
								}
							}
							else
							{
								$arrOppHIds[] = $IsToBeInserted; // noting the opportunity history record created so as to mapp it with the opportunity record for lookup
								$arrUpdatedIds[] = $IsToBeInserted; // noting the opportunity history record created so as to mapp it with the meeting record for lookup
							}
							
							
							// mapping opportunity history to opportunity table in airtable base
							$boolUpdateAccount = fnUpdateAccountRecord($arrOpportunityDetail[0]['id'],$arrOppHIds);
						}
						else
						{
							//echo "No Account Present";
							continue;
						}
					}
					else
					{
						// if opportunity not present in airtable, we connect to sf and get the opportunity details from sf
						// create a opportunity record in the opportunity table
						// create a opportunity history record in opportunity history table and 
						// mapp opportunity history record to meeting record 
						
						
						//echo "hello";
						//continue;
						//print("<pre>");
						//print_r($arrAccDetail);
						//continue;
						//echo "--".$arrAccDetail['fields']['Account ID'];
						//continue;
						
						
						// pulling opportunity record from sf for the account
						$arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token,$arrAccDetail['fields']['Account ID']);
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							
							//print("into insert <pre>");
							//print_r($arrAccountDetailSF);
							//continue; fnInsertContact
							
							// on finding the opportunity record from sf we created a opportunity record in opprtunity table of airtable base
							$arrUpdatedAccountHistory = fnInsertOpportunity($arrAccountDetailSF['records'],$arrAccDetail['id']);
							
							//continue;
							//print("<pre>");
							//print_r($arrUpdatedAccountHistory);
							//print("<pre>");
							//print_r($arrAccountDetailSF);
							
							
							// Also entry is made in opportunity history record table from fetched opportunity and its detail
							$isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
							//echo "---".$isUpdatedAccountHistory['id'];
							//echo "---".$arrAccountDetailSF['records'][0]['Name'];
							//echo "---".$strARecId;
							$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the opportunity history record created so as to mapp it with the meeting record for lookup
							
							// mapping opportunity history to opportunity table in airtable base
							$boolUpdateAccount = fnUpdateAccountRecord($arrUpdatedAccountHistory['id'],array($isUpdatedAccountHistory['id']));
							
							
						}
						else
						{
							//echo "No Account Present Other domain";
							//continue;
							
							// noting the meeting records where opportunity was not mapped but processed 
							$arrProcessIds[] = $strARecId;
						}
					}
				}
				else
				{
					continue;
				}
			}
			
			if(is_array($arrProcessIds) && (count($arrProcessIds)==$intAccCnts))
			{
				//echo "Tello";
				// updating meeting record and setting the opportunity_porcessed flag to processed
				$boolUpdateAccount = fnUpdateOppProcessedRecord($strARecId);
			}
			else
			{
				//echo "Hello";
				// updating meeting record and setting the opportunity_porcessed flag to yes for successful processing and mapping
				if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0))
				{
					$arrUpdatedIds = array_unique($arrUpdatedIds);
					$boolUpdateAccount = fnUpdateMeetingRecord($strARecId,$arrUpdatedIds);
				}
			}
			
		}
	}
}


/*
Function to connect to airtable base and get customers salesforce OAuth access
*/
function fnGetSalesUser()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	$base = $strAirtableBase;
	$table = 'salesuser';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint. $base . '/' . $table;
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
* Function to connect to airtable base and get unprocessed opportunities from meeting table in airtable and also check if
* processing time matches the current time
* Unproceed attendees are pulled from opportunity_not_processed view under meeting history table
* we process 5 records in 1 go
*/

function fnGetProcessAccounts()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	echo "--".$strDate = strtotime(date("Y-m-d"));
	$base = $strAirtableBase;
	$table = 'Meeting%20History';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=5&view=".rawurlencode("opportunity_not_processed");
	$url .= '&filterByFormula=('.rawurlencode("{meetingprocesstime}<='".$strDate."'").')';
		
	echo "--".$url; 
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


/*
* Function to flag meeting record as processed under opportunity_processed column in meeting history airtable
* Please not the flagg here is proccess and not yes, it gets sets only when for a given record there is no account or  
* opprotunities detail found.
* It takes the meeting record id as a parameter and update the status on that record id of airtable 
* return true or false
*/

function fnUpdateOppProcessedRecord($strRecId)
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
		$arrFields['fields']['oppurtunity_processed'] = "processed";
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
* Function to check if the current oppprtunity history detail is different from the fetched opportunity details
* If found to be different system returns true otherwise returns existing record id for mapping.
*/

function fnCheckIfOppHistoryToBeInserted($arrAccountHistory = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		
		//print("History check -<pre>");
		//print_r($arrAccountHistory);

		$base = $strAirtableBase;
		$table = 'Opportunity%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("latestoppfirst");
		$url .= '&filterByFormula=('.rawurlencode("{Opportunity Name}='".$arrAccountHistory[0]['Name']."'").')';

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
		$response = curl_exec($ch);
		if(!$response)
		{
			echo curl_error($ch);
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
				$strOwner = $arrSUser[0]['fields']['Owner'];
				$strStage = $arrSUser[0]['fields']['Stage'];
				$strAmount = $arrSUser[0]['fields']['Amount'];
				$strBuyerStage = $arrSUser[0]['fields']['Buyer Stage'];
				$strCloseDate = strtotime($arrSUser[0]['fields']['Close Date']);
				
				if($strOwner != $arrAccountHistory[0]['Owner']['Name'])
				{
					return "1";
				}
				else
				{
					if($strStage != $arrAccountHistory[0]['StageName'])
					{
						return "1";
					}
					else
					{
						if($strAmount != $arrAccountHistory[0]['Amount'])
						{
							return "1";
						}
						else
						{
							if($strBuyerStage != $arrAccountHistory[0]['Buyer_Stage__c'])
							{
								return "1";
							}
							else
							{
								if($strCloseDate != strtotime($arrAccountHistory[0]['CloseDate']))
								{
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
* Function that mapps the opportunity history record id to the meeting history record id
* It takes input parameter as meeting record id where mapping is to be set
* Opportunity history record id which is to be mapped in meeting history table.
*/

function fnUpdateMeetingRecord($strRecId,$strId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	//echo "--".$strRecId;
	//echo "--".$strId;
	//exit;
	if($strRecId)
	{
		
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['Opportunity History'] = $strId;
		$arrFields['fields']['oppurtunity_processed'] = "yes";
		
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
		echo "----".$response = curl_exec($curl);
		
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
* Function that mapps the opportunity history record id to the opportunity in opprtunity airtable
* It takes input parameter as opportunity record id where mapping is to be set
* Opportunity history record id which is to be mapped in opportunity table.
*/

function fnUpdateAccountRecord($strRecId,$strId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		
		$base = $strAirtableBase;
		$table = 'Opportunities';
		$strApiKey = $strAirtableApiKey;
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['Opportunity History'] = $strId;
		
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
* Function that create an entry of opportunity detail in opportunity table
* It will take latestmodified opportunity detail as 1 parameter and other opprtunity table
* Also the account record id as param so as to mapp account to the opportunity 
*/

function fnInsertOpportunity($arrAccountHistory = array(),$strId = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		//echo "Acc id -- ".$strId;
		
		//print("into insert <pre>");
		//print_r($arrAccountHistory);
		
		
		//return;
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = $strAirtableBase;
		$table = 'Opportunities';
		$strApiKey = $strAirtableApiKey;
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrAccountHistory[0]['Id'])
		{
			$arrFields['fields']['Opportunity ID'] = $arrAccountHistory[0]['Id'];
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['Opportunity Name'] = $arrAccountHistory[0]['Name'];
		}
		
		
		
		if($strId)
		{
			$arrFields['fields']['Acct ID'] = array($strId);
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
		echo "--".$response = curl_exec($curl);
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
* Function that create an entry of opportunity detail in opportunity history table
* It will take latestmodified opportunity detail as 1 parameter and other opprtunity table
* Also the opportunity record id as param so as to mapp opportunity to the opportunity history
* On success it return the created record other wise it return false
*/

function fnInsertOppHistory($arrAccountHistory = array(),$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		//echo "hello--";
		
		$base = $strAirtableBase;
		$table = 'Opportunity%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($strRecId)
		{
			$arrFields['fields']['Opportunity ID'] = array($strRecId);
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['Opportunity Name'] = $arrAccountHistory[0]['Name'];
		}
		
		if($arrAccountHistory[0]['StageName'])
		{
			$arrFields['fields']['Stage'] = $arrAccountHistory[0]['StageName'];
		}
		
		if($arrAccountHistory[0]['Amount'])
		{
			$arrFields['fields']['Amount'] = $arrAccountHistory[0]['Amount'];
		}
		
		if(is_array($arrAccountHistory[0]['Owner']))
		{
			$arrFields['fields']['Owner'] = $arrAccountHistory[0]['Owner']['Name'];
		}
		
		if($arrAccountHistory[0]['Buyer_Stage__c'])
		{
			$arrFields['fields']['Buyer Stage'] = $arrAccountHistory[0]['Buyer_Stage__c'];
		}
		
		if($arrAccountHistory[0]['CloseDate'])
		{
			$arrFields['fields']['Close Date'] = date('m/d/Y',strtotime($arrAccountHistory[0]['CloseDate']));
		}
		
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
* Function that connects to sf and pulls the opprotunity details from SF
* it takes input parameter as salesforce access token, salesforce instanceurl and account id for which opprotunity details is 
* pulled.
* It return the salesforce oppourutniyt record other wise false
*/

function fnGetOpportunityDetailFromSf($instance_url, $access_token,$strAccId = "")
{
	if($strAccId)
	{
		 //echo "--".$strAccId;
		 //return;
		 //exit;
		 
		 
		$query = "SELECT Name, Id, Owner.Name, StageName, Amount, Buyer_Stage__c, CloseDate from Opportunity WHERE AccountId = '".$strAccId."' ORDER BY lastmodifieddate DESC LIMIT 1";
		
		//return;
		 
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
* Function that checks for the opportunity details in airtable
* It takes input parameter as account id
* If found returns the record otherwise false
*/

function fnGetOpportunityDetail($strAccId = "")
{
	//echo "hello";
	//echo "--".$strAccId;
	//exit;
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strAccId)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = $strAirtableBase;
		$table = 'Opportunities';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{acc_name}='".$strAccId."'").')';	
		//echo "--".$url; 
		//exit;
		$authorization = "Authorization: Bearer ".$strApiKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);

		//execute post
		echo "--".$result = curl_exec($ch);
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
* Function to check Account detail in account table of airtable
* It takes input as account id as unique identfier form the contact detail
* If found returns the complete record other wise returns false
*/

function fnGetAccountDetail($strAccId = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strAccId)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = $strAirtableBase;
		$table = 'Accounts';
		$strApiKey = $strAirtableApiKey;
		//$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."/".$strAccId;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table."/".$strAccId;
		//$url .= '?filterByFormula=('.rawurlencode("{id}='".$strAccId."'").')';	
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
			
			if(isset($arrResponse) && (count($arrResponse)>0))
			{
				$arrSUser = $arrResponse;
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