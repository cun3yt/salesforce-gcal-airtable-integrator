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
	$api_key = 'keyOhmYh5N0z83L5F';
	$base = 'appTUmuDLBrSfWRrZ';
	$table = 'meetingsorg';
	$strApiKey = "keyOhmYh5N0z83L5F";
	$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
	$url = 'https://api.airtable.com/v0/' . $base . '/' . $table."?maxRecords=50&view=".rawurlencode("account_not_processed");
		
	
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
			//if($domain == "mesosphere.io")
			if($domain != $strClientDomain)
			{
				$arrDomainInfo = explode(".",$domain);
				echo "--".$strEmailDomain = $arrDomainInfo[0]; 
				//echo "--".$strEmailDomain = $domain;
				//$strEmailDomain = "gmail.com";
				//continue;
				
				$strEmail = $strEm;
				$arrAccountDetail = fnGetAccountDetail($strEmailDomain);
				print("<pre>");
				print_r($arrAccountDetail);
				//continue;
				if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
				{
					
					echo "--".$strEmailDomain;
					$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token, $strEmailDomain);
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrAccountDetail[0]['id']);
						echo "---".$isUpdatedAccountHistory['id'];
						echo "---".$arrAccountDetailSF['records'][0]['Name'];
						echo "---".$strARecId;
						$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
						//$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrAccountDetailSF['records'][0]['Name'],$isUpdatedAccountHistory['id']);
					}
					else
					{
						//echo "No Account Present";
						//continue;
					}
				}
				else
				{
					//echo "--".$strEmailDomain;
					echo "SF ACCS";
					$arrAccountDetailSF = fnGetAccountDetailFromSf($instance_url, $access_token,$strEmailDomain);
					print("into insert <pre>");
					print_r($arrAccountDetailSF);
					continue;
					if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
					{
						
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						
						$arrUpdatedAccountHistory = fnInsertAccount($arrAccountDetailSF['records'],$strEmailDomain);
						//print("<pre>");
						//print_r($arrUpdatedAccountHistory);
						
						$isUpdatedAccountHistory = fnInsertAccountHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
						echo "---".$isUpdatedAccountHistory['id'];
						echo "---".$arrAccountDetailSF['records'][0]['Name'];
						echo "---".$strARecId;
						$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
						
						//$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrAccountDetailSF['records'][0]['Name'],$isUpdatedAccountHistory['id']);
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
								
								$arrUpdatedAccountHistoryId = fnInsertAccount($arrAccountDetailSFId['records'],$strEmailDomain);
								//print("<pre>");
								//print_r($arrUpdatedAccountHistory);
								
								$isUpdatedAccountHistoryId = fnInsertAccountHistory($arrAccountDetailSFId['records'],$arrUpdatedAccountHistoryId['id']);
								
								$arrUpdatedIds[] = $isUpdatedAccountHistoryId['id'];
								
								//$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrAccountDetailSFId['records'][0]['Name'],$isUpdatedAccountHistoryId['id']); 
							}
						}
						
						//echo "No Account Present Other domain";
						//continue;
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
			
			$arrUpdatedIds = array_unique($arrUpdatedIds);
			$boolUpdateAccount = fnUpdateAccountRecord($strARecId,$arrUpdatedIds);
		}
	}
}

function fnUpdateAccountRecord($strRecId,$strId)
{
	if($strRecId)
	{
		
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'meetingsorg';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		//$arrFields['fields']['Account'] = array($strId)$strName;
		$arrFields['fields']['Account'] = $strId;
		$arrFields['fields']['account_processed'] = "mapped";
		
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
		$table = 'accounts';
		$strApiKey = "keyOhmYh5N0z83L5F";
		$airtable_url = 'https://api.airtable.com/v0/' . $base . '/' . $table;
		$url = 'https://api.airtable.com/v0/' . $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrAccountHistory[0]['Id'])
		{
			$arrFields['fields']['acount_s_id'] = $arrAccountHistory[0]['Id'];
		}
		
		if($arrAccountHistory[0]['Name'])
		{
			$arrFields['fields']['account_name'] = $arrAccountHistory[0]['Name'];
		}
		
		
		
		if($strDomain)
		{
			$arrFields['fields']['Account Domain'] = $strDomain;
		}
		
		/*if($arrRecord['NumberOfEmployees'])
		{
			$arrFields['fields']['number_of_employees'] = $arrRecord['NumberOfEmployees'];
		}
		
		if($arrRecord['BillingCity'])
		{
			$arrFields['fields']['billing_city'] = $arrRecord['BillingCity'];
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
		
		if($arrAccountHistory[0]['AnnualRevenue'])
		{
			$arrFields['fields']['ARR'] = $arrAccountHistory[0]['AnnualRevenue'];
		}
		
		/*if($arrAccountHistory['__Account_Status__c'])
		{
			$arrFields['fields']['Account Status'] = $arrAccountHistory['__Account_Status__c'];
		}
		
		if($arrAccountHistory['__Billing_Cycle__c'])
		{
			$arrFields['fields']['Billing Cycle'] = $arrAccountHistory['__Billing_Cycle__c'];
		}*/
		
		
		
		
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
		 
		 
		$query = "SELECT Name, Id, NumberOfEmployees, BillingCity, AnnualRevenue from Account WHERE Website LIKE '%".$strAccDomain."%' LIMIT 1";
		
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

function fnGetAccountDetail($strAccDomain = "")
{
	if($strAccDomain)
	{
		$api_key = 'keyOhmYh5N0z83L5F';
		$base = 'appTUmuDLBrSfWRrZ';
		$table = 'accounts';
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