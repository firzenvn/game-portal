<?php
$folder_name = 'client-bado';
define('CONSUMER_KEY', 'a6b9fcf07c2ea58ee86c09242ba31020');
define('CONSUMER_SECRET', '9e16ae4c38a2002ef10b8a7dcf490781');
define('OAUTH_CALLBACK', 'http://'.$_SERVER['SERVER_NAME'].'/'.$folder_name.'/soap/access.php');
define('CLIENT_CALLBACK', 'http://'.$_SERVER['SERVER_NAME'].'/'.$folder_name.'/callback.php');
define('LOGOUT_URL', 'http://'.$_SERVER['SERVER_NAME'].'/'.$folder_name.'/logout.php');

define('SOAP_API_URL', 'http://soap.soha.vn/api/a/');
define('SOAP_SERVER_URL', 'http://soap.soha.vn/dialog/');

define('PAYMENT_CALLBACK', 'http://'.$_SERVER['SERVER_NAME'].'/'.$folder_name.'/payment-callback.php');

?>