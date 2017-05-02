<?
/**
 * Global Constants
 */
$baseUrl = "http://ec2-34-210-36-40.us-west-2.compute.amazonaws.com/";

/**
 * If override file exists for local development include this file.
 */

$overrideFile = "${_SERVER['DOCUMENT_ROOT']}/global-config-override.php";

if(is_file($overrideFile)) {
    include($overrideFile);
}

define(BASE_URL, $baseUrl);
