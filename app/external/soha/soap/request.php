<?php
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
print_r(microtime());	
print_r("<br>");
session_start();
require_once '../libs/soapconfig.php';
require_once '../libs/soapoauth.php';
require_once '../libs/OAuth.php';
/* Build MingOAuth object with client credentials. */
$connection = new MingOAuth(CONSUMER_KEY, CONSUMER_SECRET);
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
	/* Build authorize URL and redirect user to Ming. */
	$url = $connection->getAuthorizeURL($token);
//    var_dump($url);die;
        
	header('Location: ' . $url); 
	break;
  default:
	/* Show notification if something went wrong. */
   header('Location: '.$domain."mingConsumer/loginfail");
}				
?>