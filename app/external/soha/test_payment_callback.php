<?php
/**
* This is how SOAP would call to game backend payment callback url
**/
require_once 'libs/soapconfig.php';
require_once 'libs/soapoauth.php';
require_once 'libs/OAuth.php';
//require_once 'libs/SPOAuthDataStore.php';

$connection = new MingOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$data = array ("method" => "payments_status_update", "order_id" => "017561312433591","status"=>"settled", 
	"order_details" => "{\"item_id\":\"test1\",\"title\":\"snsplus_vcc\",\"description\":\"1 Ecoins\",\"image_url\":\"\",\"product_url\":\"\",\"price\":1,\"data\":\"AASSTTVM110728000170\"}");
$order_info=array();
				$order_info['order_id'] = '544901331638487';
				$order_info['order_info'] = 'BOSWCBWF120313000BE8';
				$order_info['method'] = 'payments_get_items';
				
$order_info1['order_id'] = "544901331638487";
			$order_info1['status'] = 'settled';
			//$order_info1['order_details'] = json_encode('detail something');
			$order_info1['order_details'] = '{"item_id":"vcc_item_200","title":"Ng\u1ed9 Kh\u00f4ng Truy\u1ec1n K\u1ef3","description":"1050 v\u00e0ng","image_url":"","product_url":"http:\/\/vn.game.snsplus.com\/vccgame\/index\/game\/wonderjourney_vn_s2\/","price":200,"data":"BOSWCBWF120313000BE8"}';
			$order_info1['method'] = 'payments_status_update';
			
//get order details example
$response = $connection->post(PAYMENT_CALLBACK, $order_info);
echo "Order details: ";
print_r($response);

//set status example
$response2 = $connection->post(PAYMENT_CALLBACK, $order_info1);
//$response2 = $connection->post("", $order_info1);
echo "<br/>Transaction result: ";
print_r($response2);


?>