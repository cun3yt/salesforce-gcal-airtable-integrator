<?require_once('config.php');require_once('../libraries/Helpers.php');$authUrl = LOGIN_URI . "/services/oauth2/authorize?response_type=code&client_id=" . CLIENT_ID . "&redirect_uri=" . urlencode(REDIRECT_URI);Helpers::redirect($authUrl);