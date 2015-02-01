<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Client Oauth Demo</title>  
<script type="text/javascript" src="client.js"></script>
</head>
<body>

<?php
if (isset($_REQUEST['status'])) {
	$order_id = $_REQUEST['order_id'];
	//kiem tra backend  giao dich voi ma order_id
	
	echo "Giao dịch '".$order_id."' thành công!";
} else {
	$order_id = $_REQUEST['order_id'];
	$error_code = $_REQUEST['error_code'];
	echo "Giao dịch '".$order_id."' không thành công!";
}
?>


</body>
</html>