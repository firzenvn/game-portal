<?php

require_once('../util/Util/Oauth2/PlaygateIDRestClient.php');

$playgateIDRestClient = new \Util\Oauth2\PlaygateIDRestClient();
$response = $playgateIDRestClient->post('/payments-api/refund-payment', array('ref_txn_id'=>'trieuthang1991_12_26_1409915641',
    'access_token'=>'A55Bty08L0wFQGuQbK2nALiPeCSrHCRZBLK6S0W4'));
var_dump($response);