<?php
namespace Util;

use Illuminate\Support\Facades\Session;
use Log;
use stdClass;
use Util\Oauth2\PlaygateIDOauth2;
use Util\Oauth2\PlaygateIDRestClient;
use Whoops\Example\Exception;

class Oauth2Helper{
	private static $accounts;

	public static function storeToken($accessToken=array()){
		Session::put('oauth2_token',$accessToken);
	}

	public static function forgetToken(){
		Session::forget('oauth2_token');
	}

	public static function getAccessToken(){
		return isset(Session::get('oauth2_token')['access_token'])?Session::get('oauth2_token')['access_token']:null;
	}

	public static function getRefreshToken(){
		return isset(Session::get('oauth2_token')['refresh_token'])?Session::get('oauth2_token')['refresh_token']:null;
	}

	public static function getTokenExpires(){
		return isset(Session::get('oauth2_token')['expires'])?Session::get('oauth2_token')['expires']:0;
	}

	public static function validateToken(){
		if(!Oauth2Helper::getRefreshToken())
			return false;

		//Nếu không có token hoặc token đã hết hạn thì try refresh token
		if(!Oauth2Helper::getAccessToken() || Oauth2Helper::getTokenExpires() < time()){
			if(!Oauth2Helper::refreshToken())
				return false;
		}

		return true;
	}

	/**
	 * Sử dụng refresh_token để lấy access_token mới trong trường hợp access_token cũ hết hạn
	 */
	public static function refreshToken()
	{
		if (!self::getRefreshToken())
			return FALSE;

		$playgateIDOauth2 = new PlaygateIDOauth2();
		$newAccessToken = $playgateIDOauth2->refreshToken(Oauth2Helper::getRefreshToken());
		if (isset($newAccessToken['access_token'])) {
			Oauth2Helper::storeToken($newAccessToken);
			return $newAccessToken;
		}
		
		return FALSE;
	}

	/**
	 * Load số dư tài khoản từ ID
	 * @return stdClass
	 */
	public static function loadAccounts()
	{
		if (self::$accounts)
			return self::$accounts;

		$playgateIDRestClient = new PlaygateIDRestClient();
		$account = new stdClass();
		$account->mainBalance = 0;
		$account->subBalance = 0;
		$account->loaded = false;

		if(!self::validateToken()){
			self::$accounts = $account;
			return self::$accounts;
		}

		$resp = $playgateIDRestClient->get('/graph-api/my-accounts', array('access_token' => self::getAccessToken()));

		//Trường hợp access_token hết hạn, sử dụng refresh_token để lấy access_token mới
		if (!isset($resp['status']) || $resp['status'] == 401) {
			if (self::refreshToken())
				$resp = $playgateIDRestClient->get('/graph-api/my-accounts', array('access_token' => self::getAccessToken()));
		}

		if (isset($resp['status']) && $resp['status'] == 200) {
			$account = new stdClass();
			$account->mainBalance = $resp['accounts'][1]['balance'];
			$account->subBalance = $resp['accounts'][2]['balance'];
			$account->loaded = true;
		} else {
			Log::debug('Error loading accounts, access_token=' . self::getAccessToken() . ', resp = ' . json_encode($resp));
		}

		self::$accounts = $account;
		return self::$accounts;
	}
}

