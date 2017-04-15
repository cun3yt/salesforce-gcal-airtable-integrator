<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once('config.php');
$access_token = "";
$instance_url = "";
$strRecordId = "";
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

$strClientDomain = $strClientDomainName;
$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;

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

if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
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
		$intFrCnt++;
		$strARecId = $arrUser['id'];
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);
		foreach($arrEmails as $strEm)
		{
			
			$domain = substr(strrchr($strEm, "@"), 1);
			//if($domain != $strClientDomain)
			//if($domain == "gmail.com")
			if(strtolower($domain) != strtolower($strClientDomain))
			{
				$strEmailDomain = $domain;
				//$strEmailDomain = "gmail.com";
				//continue;
				
				$strEmail = $strEm;
				$arrAccountDetail = fnGetContactDetail($strEm);
				//print("<pre>");
				//print_r($arrAccountDetail);
				//continue;
				if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
				{
					//print("<pre>");
					//print_r($arrAccountDetail);
					$arrAccountDetailSF = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
					//print("<pre>");
					//print_r($arrAccountDetailSF);
					//continue;
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						//print("<pre>");
						//print_r($arrAccountDetailSF);
						$IsToBeInserted = fnCheckIfContactHistoryToBeInserted($arrAccountDetailSF['records']);
						//continue;
						if($IsToBeInserted)
						{
							if($IsToBeInserted == "1")
							{
								if($arrAccountDetail[0]['id'])
								{
									$isUpdatedAccountHistory = fnInsertContactHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
									$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
								}
								//$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrAccountDetailSF['records'][0]['Name'],$isUpdatedAccountHistory['id']);
							}
							else
							{
								//$arrOppHIds[] = $IsToBeInserted;
								$arrUpdatedIds[] = $IsToBeInserted;
								//$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrAccountDetailSF['records'][0]['Name'],$IsToBeInserted);
							}
						}
						else
						{
							$arrUpdatedIds[] = $IsToBeInserted;
						}						
					}
					else
					{
						//echo "No Account Present";
						continue;
					}
				}
				else
				{
					echo "---".$strEm;
					$arrAccountDetailSF = fnGetContactDetailFromSf($instance_url, $access_token,$strEm);
					
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						$arrAccDetail = fnGetAccountDetail($arrAccountDetailSF['records'][0]['AccountId']);
						//print("into insert <pre>");
						//print_r($arrAccDetail);
						//continue;
						$arrUpdatedAccountHistory = fnInsertContact($arrAccountDetailSF['records'],$arrAccDetail[0]['id']);
						
						//print("<pre>");
						//print_r($arrUpdatedAccountHistory);
						///continue;
						if(is_array($arrUpdatedAccountHistory) && (count($arrUpdatedAccountHistory)>0))
						{
							if($arrUpdatedAccountHistory['id'])
							{
								$isUpdatedAccountHistory = fnInsertContactHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
								$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
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