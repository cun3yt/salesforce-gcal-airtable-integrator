<?php
/*
*
* This file is basically responsible for processing meetings from airtable and fetching attendees respective contact from 
* salesforce and than mapping it within airtable base for easy lookup and reference.
*
*/
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
// we need to include config file so as to get set customer environment for processing attendees
require_once('config.php');
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
		$access_token = $arrSDetail['access_token']; // assigning access token to our global variable
		$instance_url = $arrSDetail['instance_url']; // assigning access URL to our global variable
		$strRecordId = $arrSalesUser[0]['id']; // airtable record id, pulled just in case if we are to update particular record
	}
}
//echo "--".$access_token;
//echo "--".$instance_url;
//echo "--".$strRecordId;
//exit;

/*
*
* Below you will see system fetching unprocessed attendees from customeer's meeting table 
* System will connect to meeting history table and fetch unprocessed attendees from there 
*/
$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;


/*
*
* If there are unprocessed attendees, script will iterate through it and connecte to salesforece and fetch the respective 
* contact that matches the attendee and than put pulled contact in attendee table in customer airtable base and also add an 
* entry in attendee history table in customer airtable base.
*
*/

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	// iteration will only be conducted if there are more than 0 unprocessed attendees fetched from customers airtable base.
	
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		
		// Iterating through attendee here
		
		//print("<pre>");
		//print_r($arrUser);
		//continue;
		$arrUpdatedIds = array();
		$intFrCnt++;
		$strARecId = $arrUser['id'];
		
		// ecah meeting record will have attendees detail for that meeting, we will access every attendees email for that meeting
		// there can be multiple attendees for a meeting, we explode and prepare array of attendees so that we can iterate and process each single attendee from the meet and try to fetch its respective contact detial from sfdc.
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);
		foreach($arrEmails as $strEm)
		{
			// Now we iterate through each attendee to check and get their respective contact details from sfdc
			
			$domain = substr(strrchr($strEm, "@"), 1); // we extract the domain part, so as to compare if it belongs to client domain or some external domain
			
			// comaparison we only process attendees those are external to client domains
			//if($domain != $strClientDomain)
			//if($domain == "gmail.com")
			if(strtolower($domain) != strtolower($strClientDomain))
			{
				$strEmailDomain = $domain;
				//$strEmailDomain = "gmail.com";
				//continue;
				
				$strEmail = $strEm;
				$arrAccountDetail = fnGetContactDetail($strEm); // check and get contact from airtable 
				//print("<pre>");
				//print_r($arrAccountDetail);
				//continue;
				if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
				{
					// if contact found in airtable than we connect to sf and fetch the latest modified contact from sf
					// check to see if the latest fetched record does have any updated values
					// if they are updated we add a record in the attendee history table
					// and than map it with meeting history table for look up
					
					
					//print("<pre>");
					//print_r($arrAccountDetail);
					
					// connecting and getting latest modified contact detail from sf
					$arrAccountDetailSF = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
					//print("<pre>");
					//print_r($arrAccountDetailSF);
					//continue;
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						//print("<pre>");
						//print_r($arrAccountDetailSF);
						
						// we now check if contact details fetched from sfdc, has some updated values or not
						// if yes than we add it into attendee history table otherwise we proceed to next attendee
						// system makes note of the attendee history record if it was created for mapping 
						$IsToBeInserted = fnCheckIfContactHistoryToBeInserted($arrAccountDetailSF['records']);
						//continue;
						if($IsToBeInserted)
						{
							if($IsToBeInserted == "1")
							{
								if($arrAccountDetail[0]['id'])
								{
									$isUpdatedAccountHistory = fnInsertContactHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
									$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the attendee history record created so as to mapp it with the meeting record for lookup
								}
							}
							else
							{
								$arrUpdatedIds[] = $IsToBeInserted; // noting the attendee history record created so as to mapp it with the meeting record for lookup
							}
						}
						else
						{
							$arrUpdatedIds[] = $IsToBeInserted; // noting the attendee history record created so as to mapp it with the meeting record for lookup
						}						
					}
					else
					{
						continue;
					}
				}
				else
				{
					// if attendee not present in airtable, we connect to sf and get the contact details from sf
					// create a attendee record in the attendee table
					// create a attendee history record in attendee history table and 
					// mapp attendee hostory record to meeting record 
					
					echo "---".$strEm;
					// fetching the latest modified attendee detail from sf
					$arrAccountDetailSF = fnGetContactDetailFromSf($instance_url, $access_token,$strEm);
					
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						
						// getting the account related information for a attendee from sfdc
						$arrAccDetail = fnGetAccountDetail($arrAccountDetailSF['records'][0]['AccountId']);
						//print("into insert <pre>");
						//print_r($arrAccDetail);
						//continue;
						
						// creating a attendee record in airtable by pushing the contact record along with account detail pulled from sf.
						$arrUpdatedAccountHistory = fnInsertContact($arrAccountDetailSF['records'],$arrAccDetail[0]['id']);
						
						//print("<pre>");
						//print_r($arrUpdatedAccountHistory);
						///continue;
						
						// createing attendee history record with help of pulled contact details from sf
						if(is_array($arrUpdatedAccountHistory) && (count($arrUpdatedAccountHistory)>0))
						{
							if($arrUpdatedAccountHistory['id'])
							{
								$isUpdatedAccountHistory = fnInsertContactHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
								$arrUpdatedIds[] = $isUpdatedAccountHistory['id']; // noting the attendee history record created so as to mapp it with the meeting record for lookup
							}
						}
					}
					else
					{
						//echo "No Account Present Other domain";
						continue;
					}
				}
				
				//exit;
			}
			else
			{
				continue;
			}
		}
		
		// All the noted attendee record are here mapped with meeting history table
		// if there are none we flag it as "NO SFDC Contact"
		if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0))
		{
			$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrUpdatedIds);
		}
		else
		{
			$boolUpdateNoContact = fnUpdateNoContact($strARecId);
		}
		
		//print("<pre>");
		//print_r($arrEmails);
		//$strName = fnGetUserName($strEmail);
		
		
		if($strName)
		{
			//echo "--".$boolNameUpdated = fnUpdateUserName($strName,$strARecId);
		}
		else
		{
			continue;
		}
	}
}

/*
* Function to connect to airtable base and get unprocessed attendees from meeting table in airtable
* Unproceed attendees are pulled from Unmapped Attendees view under meeting history table
* we process 5 records in 1 go
*/
function fnGetProcessAccounts()
{
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
* Function to check fetched contact details from sf and existing contact detail in attendee history table are same or diff
* If detail dont match, means there is update in contact and we return true, so as to make a new entry record in attendee 
* history table
* Other wise we return the existing attendee history record id for mapping 
*/

function fnCheckIfContactHistoryToBeInserted($arrAccountHistory = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		
		print("History check -<pre>");
		print_r($arrAccountHistory);
		
		$base = $strAirtableBase;
		$table = 'All%20Attendee%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=1&view=".rawurlencode("latestahisfirst");
		$url .= '&filterByFormula=('.rawurlencode("{Email}='".$arrAccountHistory[0]['Email']."'").')';

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
				$strTitle = $arrSUser[0]['fields']['SFDC Title'];
				$strMCity = $arrSUser[0]['fields']['SFDC Mailing City'];
				
				if($strTitle != $arrAccountHistory[0]['Title'])
				{
					return "1";
				}
				else
				{
					if($strMCity != $arrAccountHistory[0]['MailingCity'])
					{
						return "1";
					}
					else
					{
						return $arrSUser[0]['id'];
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
* Function to map flag attendee for meeting as no sfdc contact
* If none of the attendee details are found than meeting record get flagged
* This function accepts the meeting recordid which is to flagged
*/

function fnUpdateNoContact($strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint. $base . '/' . $table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['SFDC Contact'] = "No SFDC Contact";
		$arrFields['fields']['attendees_mapped'] = "yes";
		
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
* Function to map flag attendee details with meeting record
* it takes attendee history record id as parameter and maps it with meeting history record id for lookup
* it also flags the meeting record as mapped attendee- which represent completion of attendee mapping process
*/

function fnUpdateAccountRecord($strRecId,$strId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['A Attendee Emails'] = $strId;
		$arrFields['fields']['attendees_mapped'] = "yes";
		
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
* Function to create a attendee record in airtable from the pulled contact information from sf.
* It takes all the contact information along with account information and creates an entry into attendee table in airtable
*/

function fnInsertContact($arrAccountHistory = array(),$strId = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		//print("into insert <pre>");
		//print_r($arrAccountHistory);
		//return;
		
		$base = $strAirtableBase;
		$table = 'Attendees%20in%20SFDC';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint. $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrAccountHistory[0]['Id'])
		{
			$arrFields['fields']['Contact ID'] = $arrAccountHistory[0]['Id'];
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['Contact Name'] = $arrAccountHistory[0]['Name'];
		}
		
		if($arrAccountHistory[0]['Email'])
		{
			$arrFields['fields']['Email'] = $arrAccountHistory[0]['Email'];
		}
		
		
		
		if($arrAccountHistory[0]['AccountId'])
		{
			$arrFields['fields']['Account IDs'] = array($strId);
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
* Function to create a attendee history record in airtable from the pulled contact information from sf.
* It return an array of created record with its unique record it which can be used for mapping with meeting records
*/

function fnInsertContactHistory($arrAccountHistory = array(),$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = $strAirtableBase;
		$table = 'All%20Attendee%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($strRecId)
		{
			$arrFields['fields']['Contact ID'] = array($strRecId);
		}
		
		if($arrAccountHistory[0]['Email'])
		{
			$arrFields['fields']['Email'] = $arrAccountHistory[0]['Email'];
		}
		
		if($arrAccountHistory[0]['Title'])
		{
			$arrFields['fields']['SFDC Title'] = $arrAccountHistory[0]['Title'];
		}
		
		if($arrAccountHistory[0]['MailingCity'])
		{
			$arrFields['fields']['SFDC Mailing City'] = $arrAccountHistory[0]['MailingCity'];
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
* Function to connect to sf and pull the contact detail from sf
* It accepts email as parameter and queries the contact object in sf to pull details
* It returns pulled contact detail from sf other wise false
*/

function fnGetContactDetailFromSf($instance_url, $access_token,$strEmail = "")
{
	if($strEmail)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		$query = "SELECT Name, Id, Email, Title, MailingCity, AccountId from Contact WHERE Email = '".$strEmail."' ORDER BY lastmodifieddate DESC LIMIT 1";
		 
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
* Function to check contact detail in attendee table of airtable
* It is useful to avoid unnecessary calls to sf
* It takes input as email and searches the attendee table for the email and return the complete record if found otherwise
* returns false
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
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("{Account ID}='".$strAccId."'").')';	
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
		echo "---".$result = curl_exec($ch);
		if(!$result)
		{
			//echo "hi";exit;
			//echo 'error:' . curl_error($ch);
			return false;
		}
		else
		{
			//echo "Bi";exit;
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