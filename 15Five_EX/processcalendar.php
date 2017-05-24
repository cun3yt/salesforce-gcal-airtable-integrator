<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
*
* This file is basically responsible to use calenderemail data from  the meeting record and change it into more meaning form * like Name for better understanding.
* System does not update the calendaremail just used and gets the formatted information and puts it other column in meeting 
* history airtable base.
*
*/
//error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
// we need to include config file so as to get set customer environment for processing attendees
require_once('config.php');
// we will inform script about the client domain, so that while processing system knows about the client domain and work accordingly.
$strClientDomain = $strClientDomainName;
// we fetch meeting records from the meeting history table in airtable where calendar was not processed
// so that we can iterate through them and proccess them for needed purpose.
$arrGcalUser = fnGetProcessCalendar();
//print("<pre>");
//print_r($arrGcalUser);


/*
*
* Now all the unprocessed calendar records, we have it in array
* It will be iterated one by one, respective name will be looked up in People table of airtable
* On match the name will be fetched from the people airtable base and than
* Respective airtable record will be updated with name for the calendaremail
*
*/


if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
{
	// system only proceeds if there are more than one calendar not processed record present in array
	
	
	//print("<pre>");
	//print_r($arrGcalUser);
	//exit;
	$intFrCnt = 0;
	foreach($arrGcalUser as $arrUser)
	{
		
		
		//print("<pre>");
		//print_r($arrUser);
		//continue;
		
		$intFrCnt++;
		$strARecId = $arrUser['id'];
		$strEmail = $arrUser['fields']['calendaremail'];
		
		// foreach calendaremail from a meeting, we get the username matching to the email from the people table in airtable 
		$strName = fnGetUserName($strEmail);
		
		// on fetching the username we update the meeting record othwerise we dont update it
		if($strName)
		{
			echo "--".$boolNameUpdated = fnUpdateUserName($strName,$strARecId);
		}
		else
		{
			
			echo "--".$boolNameUpdated = fnUpdateUserName("",$strARecId);
			//continue;
		}
	}
}

/*
* Function to connect to airtable base and get calenderaemail information from calendar_not_processed view of airtable on 
* meeting history table
* System process such 50 records at one go
* it return record list on mactch other wise return false
*/

function fnGetProcessCalendar()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;

	$base = $strAirtableBase;
	$table = 'Meeting%20History';
	$strApiKey = $strAirtableApiKey;
	$url = $strAirtableBaseEndpoint.$base.'/'.$table."?maxRecords=50&view=".rawurlencode("calendar_not_processed");
	//$url .= '?filterByFormula=('.rawurlencode("calendar_processed=''").')';
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
* Function to connect to airtable base and looks up for the calendraemail in people table
* On match found it will return the macthed record and user name detail for that email address other wise return false
* It takes calendaremail as parameter 
*/

function fnGetUserName($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strEmail)
	{

		$base = $strAirtableBase;
		$table = 'People';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table;
		$url .= '?filterByFormula=('.rawurlencode("Email ='".$strEmail."'").")";
		//echo "---".$url; exit;
		
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
				//echo "---".$strEmail;
				
				$arrSUser = $arrResponse['records'];
				//print("<pre>");
				//print_r($arrSUser);
				//exit;
				
				$strName = $arrSUser[0]['fields']['Name'];
				//exit;
				return $strName;
				
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
* Function to connect to airtable base and update meeting history table with name
* It will take name which will be updated
* It will take meeting history table record id as another table where it will be updated
*/

function fnUpdateUserName($strName,$strRecId)
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	if($strRecId)
	{
		$base = $strAirtableBase;
		$table = 'Meeting%20History';
		$strApiKey = $strAirtableApiKey;
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		$arrFields['fields']['Calendar'] = $strName;
		$arrFields['fields']['calendar_processed'] = "processed";
		
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
		
?>