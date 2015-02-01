<?php

namespace Util\Oauth2;
/**
 * Created by PhpStorm.
 * User: Firzen
 * Date: 8/21/14
 * Time: 9:13 AM
 */
use Log;
use RestClient;
use Util\GameHelper;

require_once 'RestClient.php';
class PlaygateIDRestClient extends RestClient {
	protected $_app_id=null;
	protected $_app_secret=null;
	protected $_login_redirect_uri=null;
	protected $_base_url=null;

	function __construct(){
		$this->_app_id=GameHelper::getOauth2_APP_ID();
		$this->_app_secret=GameHelper::getOauth2_APP_SECRET();
		$this->_login_redirect_uri=GameHelper::getOauth2_LOGIN_REDIRECT_URI();
		$this->_base_url=GameHelper::getOauth2_BASE_URL();
	}

	public function get($path, $params=array(), $headers = array(), $timeout = 30) {
		$this->_signData($params);
		$payload=http_build_query($params);
		$path.='?'.$payload;
		$response = parent::get($this->_base_url.$path, $headers, $timeout);
		return json_decode($response->getContent(),true);
	}

	public function post($path, $params=array(), $headers = array(), $timeout = 30) {
		$this->_signData($params);
		$payload=http_build_query($params);
		$response = parent::post($this->_base_url.$path, $payload, $headers, $timeout);
		return json_decode($response->getContent(),true);
	}

	protected function _signData(&$params){
        $params['client_id']=$this->_app_id;
		ksort($params);
		$params['checksum'] = hash_hmac('SHA1',implode('|',$params),$this->_app_secret);
	}
} 