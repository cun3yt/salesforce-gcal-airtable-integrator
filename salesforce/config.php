<?
require_once("${_SERVER['DOCUMENT_ROOT']}/global-config.php");

/**
 * The below app's owner is Techila. This part should be replaced with Attent's one below.
 */
//define("CLIENT_ID", "3MVG9YDQS5WtC11rkXiky9nK_y9Iu_pEr2PRkMl_w2LNbZzBtcPwjidYek_1Vxw132_h1Quk3quNejsW0sjVD");
//define("CLIENT_SECRET", "945681184236244810");

/**
 * The below app's owner in SFDC is cuneyt@attent.ai
 * https://na40.salesforce.com/_ui/core/application/force/connectedapp/ForceConnectedApplicationPage/d?applicationId=06P46000000c5mz
 */
define("CLIENT_ID", "3MVG9i1HRpGLXp.oum9utXoxpin3yeYR32TKUm0hdDtB_gcVZbnP7l2H8_QBVHriPBY2jKhWONOGbmIQaGlOz");
define("CLIENT_SECRET", "3745620791270244934");

define("REDIRECT_URI", BASE_URL."salesforce/oauth_callback.php");
define("LOGIN_URI", "https://login.salesforce.com");
