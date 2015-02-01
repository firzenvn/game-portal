<?php 
define('CONSUMER_KEY', 'a6b9fcf07c2ea58ee86c09242ba31020');
define('CONSUMER_SECRET', '9e16ae4c38a2002ef10b8a7dcf490781');

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

function parse_signed_request($signed_request, $secret) {	
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);
  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }
  
  // Adding the verification of the signed_request below
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

if ($_REQUEST) {  
	$signed_request = $_REQUEST['signed_request'];
	//echo '$signed_request: '.$signed_request.'<br/>';
	$result = parse_signed_request($signed_request,CONSUMER_SECRET);
	if (!$result) {
	echo 'Invalid Signature';
	exit;
	}
  
	echo 'Signed request : '.$signed_request.'<br/><br/>';
	echo 'User id : '.$result['user_id'].'<br/><br/>';
	echo 'Access token : '.$result['access_token'].'<br/><br/>';
	echo 'Expires : '.$result['expires'].'<br/><br/>';
	echo 'Algorithm : '.$result['algorithm'].'<br/><br/>';
}
else{
	echo 'signed request is empty';
}

?>