<?php
session_start();
require_once '../libs/soapconfig.php';
require_once '../libs/soapoauth.php';
require_once '../libs/OAuth.php';
/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
	echo "errrors";exit;
}
/* Create MingoAuth object with app key/secret and token key/secret from default phase */
$connection = new MingOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
/* Request access tokens from ming */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
if (200 == $connection->http_code) {
	/* The user has been verified and the access tokens can be saved for future use */
	$callback	=	CLIENT_CALLBACK . '?oauth_token='.$access_token['oauth_token'].'&oauth_token_secret='.$access_token['oauth_token_secret'].'&user_id='.$user_id;
	 print_r('<script type="text/javascript">
		var purl = parent.location.href;
		parent.location.href = "'.$callback.'";							
		</script>');
}
?>