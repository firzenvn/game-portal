<?php
/**
 * File callback.php se duoc goi khi ming server hoan tat viec authen cho nguoi dung qua Yahoo Facebook...
 */
session_start();
require_once 'libs/soapconfig.php';
require_once 'libs/soapoauth.php';
require_once 'libs/OAuth.php';

//Lay thong tin oauth token tu Request sau khi da authen
$_SESSION['oauth_token']			=	$_REQUEST['oauth_token'];
$_SESSION['oauth_token_secret']		=	$_REQUEST['oauth_token_secret'];
//print_r($_SESSION);echo '<br><br>';
//Tu oauth token co the lay goi cac API cua Ming ID
//Vi du lay thong tin nguoi dung:
$connection = 	new MingOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
//Thong tin tra ve kieu json object
$uinfo = $connection->get('get/shu/show');
$friends = $connection->get('get/shu/appfriend');
$connection1 = new MingOAuth(CONSUMER_KEY, CONSUMER_SECRET);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Client Oauth Demo</title>  
<script type="text/javascript" src="client.js"></script>
</head>
<body>
Get User:<br>
<?php
print_r($uinfo);
?>
<br><br>
Get Friends:<br>
<?php print_r($friends);
?>
<br><br>
Post feed: <br>
<a href="javascript://" onclick="openPostFeedWindow()">Post feed</a>

<br>
<br>
Request:<br>
<a href="javascript://" onclick="openRequestFeedWindow()">Request feed</a>

<br>
<br>
Thanh toán:<br>
<a href="./pay-start.php" target="_blank">Buy item</a>
<br>
<br>
<a href="http://soap.soha.vn/dialog/Authen/Logout?app_key=<?php echo CONSUMER_KEY;?>&callback=<?php echo LOGOUT_URL; ?>" >Logout</a>

</body>
</html>

