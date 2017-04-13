<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey("AIzaSyBP1HnG9Ga9CxCvBR_UEHpbUNm3JFzuL6Y");

$service = new Google_Service_Books($client);
$optParams = array('filter' => 'free-ebooks');
$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

//print("<pre>");
//print_r($results);
//exit;

foreach ($results as $item) {
  echo $item['volumeInfo']['title'], "<br /> \n";
}
?>