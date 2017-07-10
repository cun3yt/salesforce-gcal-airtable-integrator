<?
require_once("${_SERVER['DOCUMENT_ROOT']}/global-config.php");

use DataModels\DataModels\ClientCalendarUserQuery as ClientCalendarUserQuery;
use DataModels\DataModels\ClientCalendarUserOAuth as ClientCalendarUserOAuth;
use DataModels\DataModels\Client as Client;
use DataModels\DataModels\ClientCalendarUser as ClientCalendarUser;
use DataModels\DataModels\MeetingQuery as MeetingQuery;
use DataModels\DataModels\Meeting as Meeting;
use DataModels\DataModels\ClientCalendarUserOAuthQuery as ClientCalendarUserOAuthQuery;
use DataModels\DataModels\ClientQuery as ClientQuery;
use Propel\Runtime\ActiveQuery\Criteria as Criteria;

require_once("${_SERVER['DOCUMENT_ROOT']}/libraries/HardCodings.php");

class Helpers {
    // @todo Update this version change to all SFDC calls
    const SFDC_API_VERSION = 'v39.0';

    /**
     * @param string $emailDomain
     * @return Client
     * @throws Exception
     */
    static function loadClientData($emailDomain) {
        $q = new ClientQuery();
        $clientSet = $q->findByEmailDomain($emailDomain);

        if($clientSet->count() <= 0) {
            throw new Exception("Client Data is not found for {$emailDomain}");
        }

        $client = $clientSet[0];
        return $client;
    }

    /**
     * @param Client $client
     * @param string $authType
     * @return array
     */
    static function getAuthentications(Client $client,
                                    $authType = ClientCalendarUserOAuth::GCAL) {

        $q = ClientCalendarUserOAuthQuery::create();
        return $q->filterByType($authType)
            ->innerJoinClientCalendarUser()
            ->where('ClientCalendarUser.ClientId = ?', $client->getId())
            ->find();
    }

    /**
     * @param $path
     * @return string
     */
    static function generateLink($path) {
        return BASE_URL . "{$path}";
    }

    /**
     * Creating a new Auth Entry in `client_calendar_user_oauth` table
     *
     * @param Client $client
     * @param $emailAddress
     * @param String $data JSON Data
     * @param string $integrationType
     * @return ClientCalendarUserOAuth
     */
    static function createAuthAccount(Client $client, $emailAddress, $data,
                                      $integrationType = ClientCalendarUserOAuth::GCAL) {

        $calendarUser = ClientCalendarUser::findOrCreateByClientAndCalendar($client, $emailAddress);
        $auth = new ClientCalendarUserOAuth();

        $auth->setType($integrationType)
            ->setStatus(ClientCalendarUserOAuth::STATUS_ACTIVE)
            ->setClientCalendarUser($calendarUser)
            ->setData($data)
            ->save();

        return $auth;
    }

    /**
     * Check if email address and associated authentication are present under the given client
     *
     * This replaces "fnCheckGcalAccountAlreadyPresent"
     *
     * @param Client $client
     * @param String $emailAddress
     * @param string $authType
     * @return ClientCalendarUserOAuth|null
     */
    static function getOAuthIfPresent(Client $client, $emailAddress,
                                      $authType = ClientCalendarUserOAuth::GCAL) {
        $calendarUserQuery = new ClientCalendarUserQuery();
        $calendarUsers = $calendarUserQuery->filterByEmail($emailAddress)->filterByClient($client)->find();

        if($calendarUsers->count() <= 0) {
            return NULL;
        }

        $calendarUser = $calendarUsers[0];

        $authQuery = new ClientCalendarUserOAuthQuery();
        $authSet = $authQuery->filterByClientCalendarUser($calendarUser)
            ->filterByType($authType)->find();

        $integration = NULL;

        if($authSet->count() <= 0) { return NULL; }

        if($authSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 integration", E_USER_WARNING);
        }

        return $authSet[0];
    }

    /**
     * Re-writing user's token data for GCal
     *
     * This function is replacing "fnUpdateUserTokenData()"
     *
     * @param ClientCalendarUserOAuth $auth
     * @param Array $data
     * @return ClientCalendarUserOAuth
     */
    static function updateAuthenticationToken(ClientCalendarUserOAuth $auth, $data) {
        $auth
            ->setStatus(ClientCalendarUserOAuth::STATUS_ACTIVE)
            ->setData(json_encode($data))
            ->save();

        return $auth;
    }

    /**
     * @param string $url
     */
    static function redirect($url) {
        ob_start();
        header("Location: {$url}");
        ob_end_flush();
        die();
    }

    /**
     * @param bool $active
     */
    static function setDebugParam($active = false) {
        if(!$active) {
            return;
        }

        if( isset($_GET['XDEBUG_SESSION_START']) ) {
            $_SESSION['XDEBUG_SESSION_START'] = $_GET['XDEBUG_SESSION_START'];
        } else if( isset($_SESSION['XDEBUG_SESSION_START']) && $_SESSION['XDEBUG_SESSION_START'] ) {
            $url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

            $redirectUrl = "";

            if( strpos($url, '?') === false ) {
                $redirectUrl = "{$url}?XDEBUG_SESSION_START={$_SESSION['XDEBUG_SESSION_START']}";
            } else {
                $redirectUrl = "{$url}&XDEBUG_SESSION_START={$_SESSION['XDEBUG_SESSION_START']}";
            }

            self::redirect($redirectUrl);
        }
    }

    static function getOpptyDetailFromSFDC($instance_url, $access_token, $accountId) {
        if( !$accountId ) {
            trigger_error("Account ID cannot be empty!", E_USER_ERROR);
            return false;
        }

        $query = "
          SELECT 
              AccountId, Amount, CloseDate, CreatedById, Description, Id,
              LastModifiedById, LeadSource, Name, NextStep, OwnerId, StageName,
              SyncedQuoteId, Type, Probability, Pricebook2Id
          FROM
              Opportunity
          WHERE
              AccountId = '{$accountId}' 
          ORDER BY 
              lastmodifieddate DESC
          LIMIT 1";

        return self::sfdcExecuteGetQuery($instance_url, $access_token, $query);
    }

    /**
     * Function to connect to sf and pull the contact detail from sf
     * It accepts email as parameter and queries the contact object in sf to pull details
     * It returns pulled contact detail from SFDC
     *
     * @param $instance_url
     * @param $access_token
     * @param string $emailAddress
     * @return bool|mixed
     */
    static function getContactDetailsFromSFDC($instance_url, $access_token, $emailAddress, $withMailingCity = true) {
        if(!$emailAddress) {
            return false;
        }

        if($withMailingCity) {
            $query = "SELECT Name, Id, Email, Title, MailingCity, AccountId from Contact WHERE Email = '".$emailAddress."' ORDER BY lastmodifieddate DESC LIMIT 1";
        } else {
            $query = "SELECT Name, Id, Email, Title, AccountId from Contact WHERE Email = '" . $emailAddress . "' LIMIT 1";
        }
        $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));

        $json_response = curl_exec($curl);
        if(!$json_response) {
            echo "error: ".curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($json_response, true);
        return $response;
    }

    /**
     * @param string $instance_url
     * @param string $access_token
     * @param string $query
     * @return mixed
     */
    static function sfdcExecuteGetQuery($instance_url, $access_token, $query) {
        $apiVersion = self::SFDC_API_VERSION;

        $url = "$instance_url/services/data/{$apiVersion}/query?q=" . urlencode($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth $access_token"));
        $json_response = curl_exec($curl);

        if( !$json_response ) {
            trigger_error(
                "There is no response from the API: " . curl_error($curl),
                E_USER_ERROR
            );
        }

        curl_close($curl);
        $response = json_decode($json_response, true);
        return $response;
    }

    static function SFDCGetAccountHistoryLatest($instance_url, $access_token, $accountId) {
        $query = "SELECT CreatedDate FROM AccountHistory WHERE AccountId = '{$accountId}' ORDER BY CreatedDate DESC NULLS LAST LIMIT 1";
        return self::sfdcExecuteGetQuery($instance_url, $access_token, $query);
    }

    static function SFDCGetOpptyHistoryLatest($instance_url, $access_token, $sfdcOpptyId) {
        $query = "SELECT CreatedDate FROM OpportunityHistory WHERE OpportunityId = '{$sfdcOpptyId}' ORDER BY CreatedDate DESC NULLS LAST LIMIT 1";
        return self::sfdcExecuteGetQuery($instance_url, $access_token, $query);
    }

    static function getAccountDetailFromSFDC($instance_url, $access_token, $strAccDomain = "", Client $client = NULL) {
        if(!$strAccDomain) {
            return false;
        }

        $selectSegment = "Id, Name, AnnualRevenue, NumberOfEmployees, Industry, Type, Website,
              BillingLatitude, BillingLongitude, BillingPostalCode, 
              BillingState, BillingCity, BillingStreet, BillingCountry,
              LastActivityDate, OwnerId";

        $selectSegment = HardCodings::extendSelectSegmentIf15Five($selectSegment, $client);

        $query = "SELECT 
              {$selectSegment}
            FROM 
              Account 
            WHERE 
              Website LIKE '%{$strAccDomain}%'
            ORDER BY 
              lastmodifieddate DESC 
            LIMIT 1";

        return self::sfdcExecuteGetQuery($instance_url, $access_token, $query);
    }

    /**
     * @param Client $client
     * @param $emailAddress
     * @return Meeting|null
     */
    static function getLastMeetingInDBForEmailAddress(Client $client, $emailAddress) {
        if(!$emailAddress) {
            return NULL;
        }

        $calendarUser = ClientCalendarUser::findByClientAndCalendar($client, $emailAddress);

        if(!$calendarUser) {
            return NULL;
        }

        $q = new MeetingQuery();
        return $q->filterByClientCalendarUser($calendarUser)->orderByEventDatetime(Criteria::DESC)->findOne();
    }

    /**
     * @param Google_Service_Calendar_Event $event
     * @return Meeting|null
     */
    static function getMeetingIfExists(Google_Service_Calendar_Event $event) {
        $mq = new MeetingQuery();
        $meetings = $mq->findByEventId($event->id);

        if($meetings->count() <= 0) {
            return NULL;
        }

        if($meetings->count() > 1) {
            trigger_error(__FUNCTION__ . " more than 1 meetings is found with event_id: {$event->id}", E_WARNING);
        }

        return $meetings[0];
    }

    /**
     * Function to send notification mail about access token expiry and how to revoke the access
     *
     * @param string $strEmail
     * @return bool
     */
    static function sendAccountExpirationMail($strEmail = "") {
        global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

        if(!$strEmail) {
            return false;
        }

        $to = $strEmail;
        $subject = "Google Calendar Access Expired";
        $strFrom = $strFromEmailAddress;

        $message = "Hello There,".'<br/><br/>';
        $message .= 'The Access to your calendar has been expired. <br/><br/>';

        $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

        $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
        $message .= 'Thanks';

        require_once 'Mail.php';

        $headers = array (
            'From' => $strFrom,
            'To' => $to,
            'Subject' => $subject);

        $smtpParams = array (
            'host' => $strSmtpHost,
            'port' => $strSmtpPPort,
            'auth' => true,
            'username' => $strSmtpUsername,
            'password' => $strSmtpPassword
        );

        // Create an SMTP client.
        $mail = Mail::factory('smtp', $smtpParams);

        // Send the email.
        $result = $mail->send($to, $headers, $message);

        if (PEAR::isError($result)) {
            echo("Email not sent. " .$result->getMessage() ."\n");
            return false;
        }

        echo("Email sent!"."\n");
        return true;
    }

    /**
     * @param string $userUrl
     * @param string $access_token
     * @return null | mixed
     */
    static function getUserDetailFromSFDC($userUrl, $access_token) {
        if( !$userUrl ) {
            return NULL;
        }

        $url = $userUrl;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth {$access_token}"));

        $json_response = curl_exec($curl);

        if(!$json_response) {
            trigger_error("There is a curl_error: " . curl_error($curl), E_ERROR);
        }

        curl_close($curl);

        $response = json_decode($json_response, true);
        return $response;
    }

    /**
     * @param $refresh_token
     * @param string $emailAddress
     * @return bool|mixed
     */
    static function sfdcRefreshToken($refresh_token, $emailAddress) {
        $url = "https://login.salesforce.com/services/oauth2/token";
        $strPostVariables = "grant_type=refresh_token&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&refresh_token=".$refresh_token;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPostVariables);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Accept: application/json"));

        $json_response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($json_response, true);

        if( !(is_array($response) && (count($response)>0)) ) {
            trigger_error("SFDC token refreshment request turned nothing", E_ERROR);
            return false;
        }

        if(isset($response['error']) &&
            $response['error_description'] == "expired access/refresh token") {

//            Helpers::sendEmailToSFDCAdminAboutTokenExpiry($emailAddress);

            trigger_error("Admin for SFDC got an email for account token reset.", E_NOTICE);
            return false;
        }

        if($response['access_token']) {
            return $response;
        }

        trigger_error("SFDC access token refreshing is not completed.", E_ERROR);
        return false;
    }

    static function sendEmailToSFDCAdminAboutTokenExpiry($strEmail) {
        global $strClientFolderName,$strFromEmailAddress,$strSmtpHost,$strSmtpUsername,$strSmtpPassword,$strSmtpPPort;

        if(!$strEmail) {
            return false;
        }

        $to = $strEmail;
        $subject = "Salesforce Oauth Access Expired";
        $strFrom = $strFromEmailAddress;

        $message = "Hello There,".'<br/><br/>';
        $message .= 'The Access to your salesforce account has been expired. <br/><br/>';

        $link = Helpers::generateLink($strClientFolderName.'/loadcals.php');

        $message .= "Please login at following URL to revoke the access: <a href='{$link}'>Revoke Access</a> <br/><br/><br/>";
        $message .= 'Thanks';

        require_once 'Mail.php';

        $headers = array (
            'From' => $strFrom,
            'To' => $to,
            'Subject' => $subject);

        $smtpParams = array (
            'host' => $strSmtpHost,
            'port' => $strSmtpPPort,
            'auth' => true,
            'username' => $strSmtpUsername,
            'password' => $strSmtpPassword
        );

        // Create an SMTP client.
        $mail = Mail::factory('smtp', $smtpParams);

        // Send the email.
        $result = $mail->send($to, $headers, $message);

        if (PEAR::isError($result)) {
            echo("Email not sent. " .$result->getMessage() ."\n");
            return false;
        }

        echo("Email sent!"."\n");
        return true;
    }

    /**
     * @param $googleCalAPICredentialFile
     * @param bool $accessOffline
     * @return Google_Client
     */
    static function setupGoogleAPIClient($googleCalAPICredentialFile, $accessOffline = false) {
        require_once("${_SERVER['DOCUMENT_ROOT']}/gcal/vendor/autoload.php");
        $client = new Google_Client();
        $client->setAuthConfig($googleCalAPICredentialFile);
        $client->addScope(array(Google_Service_Calendar::CALENDAR));
        $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzleClient);

        if($accessOffline) { $client->setAccessType("offline"); }

        return $client;
    }

    /**
     * @return array
     */
    static function getPersonalEmailDomains() {
        return array("gmail.com", "yahoo.com", "yahoo.co.in", "aol.com", "att.net", "comcast.net",
            "facebook.com", "gmail.com", "gmx.com", "googlemail.com", "hotmail.com", "hotmail.co.uk",
            "mac.com", "me.com", "mail.com", "msn.com", "live.com", "sbcglobal.net", "verizon.net", "yahoo.com",
            "yahoo.co.uk", "rediif.com");
    }

    /**
     * These are the emails that are excluded for meeting marking as "External"
     *
     * @return array
     */
    static function getNonContactEmailDomains() {
        return array(
            "calendar.google.com",
            "resource.calendar.google.com",
            "ywsync.com"
        );
    }

    /**
     *  "cuneyt@attent.ai" => "attent.ai"
     *
     * @param $emailAddress
     * @return String
     */
    static function getEmailDomain($emailAddress) {
        list($id, $domain) = explode('@', $emailAddress);
        return $domain;
    }

    /**
     * "cuneyt@attent.ai" => "attent"
     *
     * @param $emailDomain
     * @return String
     */
    static function getEmailDomainSegment($emailDomain) {
        $segments = explode('.', $emailDomain);
        return $segments[0];
    }
}
