<?php
error_reporting(~E_WARNING && ~E_NOTICE);
session_start();
require_once 'config.php';

$access_token = "00D6F000001OQqQ!AQsAQKXjpj129BZRKF20J3kwtaoVoVDyyc23h2VFVQ7cpbMWNDKewzo49q0ZOdzptkGz4VPHFd_qvVfIl7txfXAOfj67q1bi";
$instance_url = "https://ap4.salesforce.com";
if($access_token)
{
	$strNewAccessToken = show_accounts($instance_url, $access_token);
	
}
function show_accounts($instance_url, $access_token) {
    $query = "SELECT Name, Id from Account LIMIT 100";
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);
	
	if(!$json_response)
	{
		//echo "HI";exit;
		echo 'error:' . curl_error($curl);
		
		//return false;
	}

    $response = json_decode($json_response, true);

    $total_size = $response['totalSize'];

    echo "$total_size record(s) returned<br/><br/>";
    foreach ((array) $response['records'] as $record) {
        echo $record['Id'] . ", " . $record['Name'] . "<br/>";
    }
    echo "<br/>";
}

?>