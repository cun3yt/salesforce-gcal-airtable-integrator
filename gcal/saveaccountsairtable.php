<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once 'config.php';
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
	$api_key = 'keyOhmYh5N0z83L5F';
	$base = 'appTUmuDLBrSfWRrZ';
	$table = 'salesuser';
	$strApiKey = "keyOhmYh5N0z83L5F";
	$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
	$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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

$strClientDomain = "15five.com";
$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;

function fnGetProcessAccounts()
{
	echo "--".$strDate = strtotime(date("Y-m-d"));
	$api_key = 'keyOhmYh5N0z83L5F';
	$base = 'appTUmuDLBrSfWRrZ';
	$table = 'Meeting%20History';
	$strApiKey = "keyOhmYh5N0z83L5F";
	$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
	//$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=50&view=".rawurlencode("account_not_processed");
	$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=5&view=".rawurlencode("account_not_processed");
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
		$arrAccDomains = array();
		$arrProcessIds = array();
		$strAccName = "";
		$arrId = array();
		$intFrCnt++;
		$intExterNameEmails = 0;
		$strARecId = $arrUser['id'];
		$strMeetingName = $arrUser['fields']['Meeting Name'];
		$arrUser['fields']['accountno'];
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);
		$arrIds = explode(",",$arrUser['fields']['accountno']);
		foreach($arrEmails as $strEm)
		{
			$domain = substr(strrchr($strEm, "@"), 1);
			//if($domain != $strClientDomain)
			//if($domain == "mesosphere.io")
			if($domain != $strClientDomain)
			{
				$intExterNameEmails++;
				$arrDomainInfo = explode(".",$domain);
				$strEmailDomain = $arrDomainInfo[0]; 
				//echo "--".$strEmailDomain = $domain;
				//$strEmailDomain = "gmail.com";
				//continue;
				
				$strEmail = $strEm;
				$arrAccountDetail = fnGetAccountDetail($strEmailDomain);
				//print("<pre>");
				//print_r($arrAccountDetail);
				//continue;
				if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
				{
					
					//echo "--".$strEmailDomain;
					//echo "--".$arrAccountDetail[0]['AccountNumber'];
					
					
					/*else
					{*/
						$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							
							if(in_array($arrAccountDetailSF['records'][0]['Name'],$arrAccDomains))
							{
								continue;
							}
							else
							{
								$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSF['records']);
								//continue;
								if($IsToBeInserted)
								{
									if($IsToBeInserted == "1")
									{
										$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
										//echo "---".$isUpdatedAccountHistory['id'];
										//echo "---".$arrAccountDetailSF['records'][0]['Name'];
										//echo "---".$strARecId;
										
										$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
										$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
									}
									else
									{
										
										$arrUpdatedIds[] = $IsToBeInserted;
										$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
									}
								}
								else
								{
									$arrUpdatedIds[] = $IsToBeInserted;
									$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
								}
								
								
								
							}
							/*if(in_array($arrAccountDetail[0]['AccountNumber'],$arrIds))
							{
								
							}
							else
							{
								$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
								//$arrUpdatedIds[] = $arrAccountDetail[0]['id'];
								$arrId[] = $arrAccountDetail[0]['AccountNumber'];
							}*/
						}
						else
						{
							$arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
							//print("<pre>");
							//print_r($arrAccountDetailN);
							//continue;
							
							if(is_array($arrAccountDetailN) && (count($arrAccountDetailN)>0))
							{
								$arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
								//print("<pre>");
								//print_r($arrAccountDetailSFId);
								//continue;
								
								if(is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records'])>0))
								{
									//print("<pre>");
									//print_r($arrAccountDetailSF);
									
									if(in_array($arrAccountDetailSFId['records'][0]['Name'],$arrAccDomains))
									{
										continue;
									}
									else
									{
										$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
										$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
										//continue;
										if($IsToBeInserted)
										{
											if($IsToBeInserted == "1")
											{
												$isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'],$arrUpdatedAccountHistoryId['id']);
												//$arrUpdatedIds[] = $arrUpdatedAccountHistoryId['fields']['Account ID'];
												$arrUpdatedIds[] = $isUpdatedAccountHistoryId['id'];
												$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
												$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
											}
											else
											{
												$arrUpdatedIds[] = $IsToBeInserted;
												$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
												$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
											}
											
										}
										else
										{
											$arrUpdatedIds[] = $IsToBeInserted;
											$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
											$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
										}
									}
								}
								else
								{
									$arrProcessIds[] = $strARecId;
								}
							}
							else
							{
								$arrProcessIds[] = $strARecId;
							}
						}
					//}
				}
				else
				{
					/*if(in_array($strAccName,$arrAccDomains))
					{
						continue;
					}*/
					/*else
					{*/
						//echo "--".$strEmailDomain;
						//echo "SF ACCS";
						$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token,$strEmailDomain);
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							
							//print("into insert <pre>");
							//print_r($arrAccountDetailSF);
							//continue;
							
							$arrUpdatedAccountHistory = fnInsertAccount($arrAccountDetailSF['records'],$strEmailDomain);
							//print("<pre>");
							//print_r($arrUpdatedAccountHistoryId);
							$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
							
							//$arrUpdatedIds[] = $arrUpdatedAccountHistory['fields']['Account ID'];
							$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
							$arrId[] = $arrUpdatedAccountHistory['fields']['AccountNumber'];
							$arrAccDomains[] = $arrAccountDetailSF['records'][0]['Name'];
						}
						else
						{
							$arrAccountDetailN = fnGetContactDetailFromSf($instance_url, $access_token, $strEm);
							//print("<pre>");
							//print_r($arrAccountDetailN);
							//continue;
							
							if(is_array($arrAccountDetailN) && (count($arrAccountDetailN)>0))
							{
								$arrAccountDetailSFId = fnGetAccountDetailFromSfId($instance_url, $access_token, $arrAccountDetailN['records'][0]['AccountId']);
								if(is_array($arrAccountDetailSFId['records']) && (count($arrAccountDetailSFId['records'])>0))
								{
									//print("<pre>");
									//print_r($arrAccountDetailSF);
									
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
											
											$IsToBeInserted = fnCheckIfAccountHistoryToBeInserted($arrAccountDetailSFId['records']);
											//continue;
											if($IsToBeInserted)
											{
												if($IsToBeInserted == "1")
												{
													$isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'],$arrUpdatedAccountHistoryId['id']);
													//$arrUpdatedIds[] = $arrUpdatedAccountHistoryId['fields']['Account ID'];
													$arrUpdatedIds[] = $isUpdatedAccountHistoryId['id'];
													$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
													$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
												}
												else
												{
													$arrUpdatedIds[] = $IsToBeInserted;
													$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
													$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
												}
												
											}
											else
											{
												$arrUpdatedIds[] = $IsToBeInserted;
												$arrId[] = $arrUpdatedAccountHistoryId['fields']['AccountNumber'];
												$arrAccDomains[] = $arrAccountDetailSFId['records'][0]['Name'];
											}
											
										}
									}
								}
								else
								{
									$arrProcessIds[] = $strARecId;
								}
							}
							else
							{
								$arrProcessIds[] = $strARecId;
							}
							//echo "No Account Present Other domain";
							//continue;
						}
					//}
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
			$boolUpdateAccount = fnUpdateAccountProcessedRecord($strARecId);
		}
		else
		{
			if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0))
			{
				$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrUpdatedIds,$arrId);
			}
		}
	}
}

function fnCheckIfAccountHistoryToBeInserted($arrAccountHistory = array())
{
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		
		print("History check -<pre>");
		print_r($arrAccountHistory);
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Account%20History';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=1&view=".rawurlencode("latestfirst");
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

function fnUpdateAccountProcessedRecord($strRecId)
{
	if($strRecId)
	{
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Meeting%20History';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table.'/'.$strRecId;

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

function fnUpdateAccountRecord($strRecId,$strId,$strAId)
{
	if($strRecId)
	{
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Meeting%20History';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table.'/'.$strRecId;

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

function fnGetContactDetail($strEmail = "")
{
	if($strEmail)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Attendees%20in%20SFDC';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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

function fnInsertAccount($arrAccountHistory = array(),$strDomain = "")
{
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		//print("into insert <pre>");
		//print_r($arrAccountHistory);
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Accounts';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;

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
		
		/*if($arrRecord['NumberOfEmployees'])
		{
			$arrFields['fields']['# Employees'] = $arrRecord['NumberOfEmployees'];
		}
		
		if($arrRecord['BillingCity'])
		{
			$arrFields['fields']['billing_city'] = $arrRecord['BillingCity'];
		}
		
		if($arrRecord['Billing_Cycle__c'])
		{
			$arrFields['fields']['Billing Cycle'] = $arrRecord['Billing_Cycle__c'];
		}
		
		if($arrRecord['Account_Status__c'])
		{
			$arrFields['fields']['Account Status'] = $arrRecord['Account_Status__c'];
		}
		
		if($arrRecord['Subscription_End_Date__c'])
		{
			$arrFields['fields']['Renewal Date'] = date("m/d/Y",strtotime($arrRecord['Subscription_End_Date__c']));
		}
		
		if($arrRecord['AnnualRevenue'])
		{
			$arrFields['fields']['annual_revenue'] = $arrRecord['AnnualRevenue'];
		}*/
		
		
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

function fnInsertAccountHistory($arrAccountHistory = array(),$strRecId)
{
	if(is_array($arrAccountHistory) && (count($arrAccountHistory)>0))
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Account%20History';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;

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

function fnGetAccountDetailFromSf($instance_url, $access_token,$strAccDomain = "")
{
	if($strAccDomain)
	{
		 //echo "--".$strAccDomain;
		 //return;
		 //exit;
		 
		 
		$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue, Account_Status__c, Billing_Cycle__c, Subscription_End_Date__c, ARR__c from Account WHERE Website LIKE '%".$strAccDomain."%' LIMIT 1";
		
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

function fnGetAccountDetailByName($strAccName = "")
{
	if($strAccName)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Accounts';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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

function fnGetAccountDetail($strAccDomain = "")
{
	if($strAccDomain)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'Accounts';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
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