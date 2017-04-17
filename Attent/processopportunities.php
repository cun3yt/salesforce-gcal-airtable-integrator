<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

$strClientDomain = $strClientDomainName;
$arrGcalUser = fnGetProcessAccounts();
//print("<pre>");
//print_r($arrGcalUser);
//exit;

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
		$arrProcessIds = array();
		$intFrCnt++;
		$intAccCnts = 0;
		$strARecId = $arrUser['id'];
		$arrEmails = explode(",",$arrUser['fields']['Attendee Email(s)']);
		$strEmail = $strEm;
		$arrAccountDetailold = $arrUser['fields']['Account'];
		$arrAccountDetail = $arrUser['fields']['acoount_id'];
		//print("<pre>");
		//print_r($arrAccountfls);
		//continue;
		
		//print("<pre>");
		//print_r($arrAccountDetail);
		//continue;
		if(is_array($arrAccountDetail) && (count($arrAccountDetail)>0))
		{
			foreach($arrAccountDetail as $arrAccount)
			{
				if($arrAccount)
				{
					//echo "--".$arrAccount;
					//continue;
					$intAccCnts++;
					$arrAccDetail = fnGetAccountDetail($arrAccount);
					//print("Account details <pre>");
					//print_r($arrAccDetail);
					//continue;
					//exit;
					
					$arrOpportunityDetail = fnGetOpportunityDetail($arrAccDetail['fields']['Account']);
					//print("<pre>");
					//print_r($arrOpportunityDetail);
					//continue;
					if(is_array($arrOpportunityDetail) && (count($arrOpportunityDetail)>0))
					{
						//print("<pre>");
						//print_r($arrAccountDetail);
						$arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token, $arrAccDetail['fields']['Account ID']);
						//print("<pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							//print("<pre>");
							//print_r($arrAccountDetailSF);
							$arrOppHIds = $arrOpportunityDetail[0]['fields']['Opportunity History'];
							echo "---".$IsToBeInserted = fnCheckIfOppHistoryToBeInserted($arrAccountDetailSF['records']);
							if($IsToBeInserted)
							{
								if($IsToBeInserted == "1")
								{
									$isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'],$$arrOpportunityDetail[0]['id']);
									if($isUpdatedAccountHistory['id'])
									{
										$arrOppHIds[] = $isUpdatedAccountHistory['id'];
										$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
									}
								}
								else
								{
									$arrOppHIds[] = $IsToBeInserted;
									$arrUpdatedIds[] = $IsToBeInserted;
								}
							}
							else
							{
								$arrOppHIds[] = $IsToBeInserted;
								$arrUpdatedIds[] = $IsToBeInserted;
							}
							
							
							
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
						//echo "hello";
						//continue;
						//print("<pre>");
						//print_r($arrAccDetail);
						//continue;
						//echo "--".$arrAccDetail['fields']['Account ID'];
						//continue;
						
						$arrAccountDetailSF = fnGetOpportunityDetailFromSf($instance_url, $access_token,$arrAccDetail['fields']['Account ID']);
						//print("into insert <pre>");
						//print_r($arrAccountDetailSF);
						//continue;
						if(is_array($arrAccountDetailSF['records']) && (count($arrAccountDetailSF['records'])>0))
						{
							
							//print("into insert <pre>");
							//print_r($arrAccountDetailSF);
							//continue; fnInsertContact
							
							$arrUpdatedAccountHistory = fnInsertOpportunity($arrAccountDetailSF['records'],$arrAccDetail['id']);
							
							//continue;
							//print("<pre>");
							//print_r($arrUpdatedAccountHistory);
							
							//print("<pre>");
							//print_r($arrAccountDetailSF);
							
							$isUpdatedAccountHistory = fnInsertOppHistory($arrAccountDetailSF['records'],$arrUpdatedAccountHistory['id']);
							//echo "---".$isUpdatedAccountHistory['id'];
							//echo "---".$arrAccountDetailSF['records'][0]['Name'];
							//echo "---".$strARecId;
							$arrUpdatedIds[] = $isUpdatedAccountHistory['id'];
							$boolUpdateAccount = fnUpdateAccountRecord($arrUpdatedAccountHistory['id'],array($isUpdatedAccountHistory['id']));
							
							
						}
						else
						{
							//echo "No Account Present Other domain";
							//continue;
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
				
				$boolUpdateAccount = fnUpdateOppProcessedRecord($strARecId);
			}
			else
			{
				//echo "Hello";
				if(is_array($arrUpdatedIds) && (count($arrUpdatedIds)>0))
				{
					$arrUpdatedIds = array_unique($arrUpdatedIds);
					$boolUpdateAccount = fnUpdateMeetingRecord($strARecId,$arrUpdatedIds);
				}
			}
			
		}
	}
}

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