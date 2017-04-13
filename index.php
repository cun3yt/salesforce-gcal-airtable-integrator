<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo "hi";exit;
$to = "rajendrakumar.kandpal@gmail.com";
$subject = "Google Calendar Access Expired";

$message = "Hello There,".'<br/><br/>';
$message .= 'The Access to your calendar has been expired. <br/><br/>';
$message .= 'Please login at following URL to revoke the access: <a href="http://www.rothrsolutions.com/gcal/loadcals.php">Revoke Access</a> <br/><br/><br/>';
$message .= 'Thanks';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= 'From: johnrola36@gmail.com'."\r\n";							
echo "---".$retval = mail ($to,$subject,$message,$headers);
?>