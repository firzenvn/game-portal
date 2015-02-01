<?php
require_once 'libs/soapconfig.php';
//require_once 'libs/soapoauth.php';
require_once 'libs/OAuth.php';
require_once 'libs/SPOAuthDataStore.php';


$oauthServer = new OAuthServer(new SPOAuthDataStore());
$oauthServer->add_signature_method( new OAuthSignatureMethod_HMAC_SHA1());
try {
	$req = OAuthRequest::from_request();
	//this is a security step, 
		//to verify whether this call is from SOAP or not
	if ($oauthServer->verify_url($req)) {
		//print_r(json_encode("Request is signed!"));
	}
	$method = $_POST['method'];
	//if method is get order info
	if ($method == 'payments_get_items') {
		//order_info is the param created by game/app when user click to buy something in game
			//from order_info param, game/app retrieves info to create specific order 
		$order_info = $_POST['order_info'];	
		
		//id of user who initiated the payment
		$user_id = $_POST['user_id'];
		
		//order_id is a unique string that SOAP creates for each order, use this as an ID for order	
		$order_id = $_POST['order_id'];
		//Set this order status to waiting to confirm

		//example order details;
		$order_details = array('item_id'=>'001',
								'title'=>'test item 1',
								'description'=>'this is a test item',
								'image_url'=>'',
								'product_url'=>'',
								'price'=>0,
								'data'=>'this is test data');
		//return order details to SOAP
		print_r(json_encode($order_details));
		exit;
	} else if ($method == 'payments_status_update'){
		$order_status = $_POST['status'];
		$order_id = $_POST['order_id'];		
		if ($order_status == 'settled') {
			//order is ok now, update the order with the $order_id
			$result = array('status'=>'settled');
			print_r(json_encode($result));
			exit;
		}
	}
} catch (OAuthException $e) {
	//not from soap, return error
	$error_mess = $e->getMessage();
	print_r (json_encode($error_mess));
}

?>