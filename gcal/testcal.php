<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey("AIzaSyBP1HnG9Ga9CxCvBR_UEHpbUNm3JFzuL6Y");

$service = new Google_Service_Calendar($client);
$calendarId = 'redorangetechnologies.com_i93nr46t0hh5cdenjidnc8a6bo@group.calendar.google.com';
$events = $service->events->listEvents('redorangetechnologies.com_i93nr46t0hh5cdenjidnc8a6bo@group.calendar.google.com');

print("<pre>");
print_r($events);
exit;

while(true) {
  foreach ($events->getItems() as $event) {
    //print("<pre>");
	//print_r($events);
	//echo $event->getSummary();
  }
  $pageToken = $events->getNextPageToken();
  if ($pageToken) {
    $optParams = array('pageToken' => $pageToken);
    $events = $service->events->listEvents('rajendra.kandpal@redorangetechnologies.com', $optParams);
  } else {
    break;
  }
}
?>