<?
/*
* This file work as a controller for the connected Google and Salesforce accounts
* It lists all the connected salesforce and google accounts
* This is the file through which customer register their salesforce and google account.
* Throughout this file(below) you will find unit of code achieving this purpose.
*/

error_reporting(~E_NOTICE && ~E_DEPRECATED);
session_start();
// we need to include config file so as to get set customer environment for processing
require_once('config.php');

/*
* Below 2 lines are important and we have used session to store current client details temporarily as a checkpoint.
* System looks up the current checkpoint in its temporary storage and identifies the correct place or callback URL with this * information for further processing,  .
* When system comes back with help of call back url through session variables, it process further and stores current clients * google access or salesforce access to their own airtable base.
*/
$_SESSION['currentclient'] = $strClient;
$_SESSION['currentclientfoldername'] = $strClientFolderName;

/*
* Below 2 lines are used to read the google calendar OAuth access data from session variables(systems temporary storage)
* Systems looks up this temporary storage for current client's google or salesforce access when they return back after
* doing OAuth and uses them to store or update their access details in their respective airtable base.It is needed so that 
* google OAuth access can be stored in database for future access.
*/
$arrCalData = $_SESSION['calendardata'];
$arrUData = $_SESSION['userdata'];

/*
* Below you will see system will just check temporary storage and iterate through it to updated customer's airtable base with access
* details present in temporary storage
*/

if(is_array($arrUData) && (count($arrUData)>0))
{
	// If system has google OAauth access detail, we will iterate through and save and update the access detail 
	
	foreach($arrUData as $strUValKey => $strUVal)
	{
		// iterating through google OAuth access details
		// checking if access detail already exists for the customer
		
		$isRecPresent = fnCheckGcalAccountAlreadyPresent($strUVal);
		if(!$isRecPresent)
		{
			// if not present system will save the google OAauth access detail to airtable base.
			
			$record['utoken'] = $strUValKey;
			$record['uemail'] = $strUVal;
			$record['status'] = "active";
			$isRecSaved = fnSaveGcalAccount($record);
		}
		else
		{
			// If present system will update the google OAuth access details in customer airtable base.
			$record['utoken'] = $strUValKey;
			$record['uemail'] = $strUVal;
			$record['status'] = "active";
			fnUpdateUserTokenData($isRecPresent,$record);
		}
	}
	// finally we clear systems temporary storage after customers google OAuth access are stored in their respective airtable base, So that 
	unset($_SESSION['calendardata']);
	unset($_SESSION['userdata']);
}


// Get the registerd google calendar oAuth access entry from customer's airtable base
$arrGcalUser = fnGetGcalUser();

// Get the registerd salesforce oAuth access entry from customer's airtable base
$arrSalesUser = fnGetSalesUser();

/*
Function to connect to airtable base and get customers gcals OAuth acceess
*/
function fnGetGcalUser()
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	$base = $strAirtableBase;
	$table = 'gaccounts';
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

    $arrResponse = json_decode($result,true);
    if(isset($arrResponse['records']) && (count($arrResponse['records'])>0)) {
        $arrSUser = $arrResponse['records'];
        return $arrSUser;
    }

    return false;
}

/*
Function to connect to airtable base and get customers salesforce OAuth acceess
*/
function fnGetSalesUser() {
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

    $arrResponse = json_decode($result,true);

	if(isset($arrResponse['records']) && (count($arrResponse['records'])>0))
    {
        $arrSUser = $arrResponse['records'];
        return $arrSUser;
    }

    return false;
}

/*
Function to connect to airtable base and update customers gcal OAuth access 
*/
function fnUpdateUserTokenData($strRecId,$arrRecord = array()) {
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if($strRecId)
	{
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		
		$url = $strAirtableBaseEndpoint.$base.'/'.$table.'/'.$strRecId;

		$authorization = "Authorization: Bearer ".$strApiKey;
		$arrFields['fields']['user_token'] = $arrRecord['utoken'];
		$arrFields['fields']['status'] = $arrRecord['status'];
		
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
Function to connect to airtable base and save customers gcal OAuth access 
*/
function fnSaveGcalAccount($arrRecord = array())
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if(is_array($arrRecord) && (count($arrRecord)>0))
	{
		$base = $strAirtableBase;
		$table = 'gaccounts';
		$strApiKey = $strAirtableApiKey;
		
		$url = $strAirtableBaseEndpoint. $base . '/' . $table;

		$authorization = "Authorization: Bearer ".$strApiKey;
		if($arrRecord['uemail'])
		{
			$arrFields['fields']['user_email'] = $arrRecord['uemail'];
		}
		
		if($arrRecord['utoken'])
		{
			$arrFields['fields']['user_token'] = $arrRecord['utoken'];
		}

		$arrFields['fields']['status'] = "active";
		$arrFields['fields']['sync_data'] = "1";
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
Function to connect to airtable base and check if customer's OAuth access exists 
*/
function fnCheckGcalAccountAlreadyPresent($strEmail = "")
{
	global $strAirtableBase,$strAirtableApiKey,$strAirtableBaseEndpoint;
	
	if(!$strEmail) {
        return true;
    }

    $base = $strAirtableBase;
    $table = 'gaccounts';
    $strApiKey = $strAirtableApiKey;
    $url = $strAirtableBaseEndpoint.$base.'/'.$table;
    $url .= "?filterByFormula=(user_email='".$strEmail."')";
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
    if(!$result) {
        return true;
    }
    $arrResponse = json_decode($result,true);
    if(is_array($arrResponse) && (count($arrResponse)>0)) {
        $arrRecords = $arrResponse['records'];
        if(is_array($arrRecords) && (count($arrRecords)>0)) {
            return $arrRecords[0]['id'];
        }

        return false;
    }
}
?>

<!--
Below is the code for interface
It either shows already saved google calendar or salesforce access OR
It shows the button which user can use to grant OAuth access to their google calendar and
Salesforce account.
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/static/main.css">
	</head>
    <body>
        <div class="main">
            <div class="heading"></div>
            <div class="content">
                <?
                    // We check first if customers airtable base has OAuth access details
                    // If they are present we iterate through the airtable record and print on interface
                    // here we are iterating the array vraible which supposed to hold google and salesforce access data and we are iterating through them to print it.
                    if(is_array($arrGcalUser) && (count($arrGcalUser)>0))
                    {
                        $intFrCnt = 0;
                        foreach($arrGcalUser as $arrUser)
                        {
                            $arrUserDet = $arrUser['fields'];
                            $intFrCnt++;
                            $strUserName = "User".$intUser;
                            $strUserEmail = $arrUserDet['user_email'];
                            $strStatus = $arrUserDet['status'];
                            $arrUserDetail = explode("@",$strUserEmail);
                            ?>
                                <div class="box" id="<?=$intUser?>">
                                    <div class="box-content">
                                        <div class="title"><img src="img/callendor.png" >Calendar</div>
                                        <p><?=$arrUserDetail[0]?></p>
                                        <?
                                            // for each access record we check the access status
                                            // if the access record says as expired than we show the Activate button
                                            // which will again do OAuth and setup the access again.
                                            if($strStatus == "expired") { ?>
                                                <a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/gcal/add_new_gcal.php"><button type="button" class="setting-btn">Activate</button></a>
                                            <? } ?>
                                    </div>
                                </div>
                            <?
                        }
                    }
                ?>
                <!--
                In case above iteration does not result in listing account than you will see the following code snippet
                which will show the Add calendar button which initiate OAuth and setup the access
                to google calendar account of user. This button will redirect to the specified from where OAuth is handeled.
                -->
                <div class="calendor-btn">
                    <a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/gcal/add_new_gcal.php">
                        <button type="button" class="add-button">Add Calender</button>
                    </a>
                </div>
            </div>

            <div class="heading"></div>
            <div class="content">
                <?
                    // On the similar lines we check customers airtable base for access to salesforces account
                    // If found we pull it into array variable and than iterate through the array to print and show the user the stored access to salesforce account.
                    // System is built to connect to only 1 salesforce account for a given customer.
                    if(is_array($arrSalesUser) && (count($arrSalesUser)>0))
                    {
                        $intFrCnt = 0;
                        foreach($arrSalesUser as $arrUser) {
                            // here we are iterating through stored salesforce access and checking the status and printing it on interface accordingly.
                            $strStatus = $arrUser['fields']['status'];
                            $intFrCnt++;
                            ?>
                                <div class="box" id="<?=$intUser?>">
                                    <div class="box-content">
                                        <div class="title">Salesforce</div>
                                        <? if($strStatus == "expired") { ?>
                                            <p>&nbsp;</p>
                                            <a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/salesforce/oauth.php"><button type="button" class="setting-btn">Reconnect</button></a>
                                        <? } else { ?>
                                            <p>Account Connected</p>
                                        <? } ?>
                                    </div>
                                </div>
                            <?
                        }
                    } else { ?>
                        <div class="calendor-btn"><a href="http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/salesforce/oauth.php"><button type="button" class="add-button">Login with Salesforce </button></a></div>
                    <? } ?>
            </div>
        </div>
    </body>
</html>