<?php

require_once('libs/soapoauth.php');
class SohaHelper {

    const CONSUMER_KEY = 'a6b9fcf07c2ea58ee86c09242ba31020';
    const CONSUMER_SECRET = '9e16ae4c38a2002ef10b8a7dcf490781';
    const EXTERNAL_PORTAL_CODE = 'soha';
    const ID_EXCHANGE_RATE = 10;

    public static function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function parse_signed_request($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        // decode the data
        $sig = self::base64_url_decode($encoded_sig);
        $data = json_decode(self::base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
//            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // Adding the verification of the signed_request below
        $expected_sig = hash_hmac('sha256', $payload, self::CONSUMER_SECRET, $raw = true);
        if ($sig !== $expected_sig) {
//            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public static function getUserInfo($accessToken){
        $mingOAuth = new MingOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        return $mingOAuth->get('https://soap.soha.vn/api/a/GET/me/show?access_token='.$accessToken);
    }

    public static function getUserInfoByArr($accessTokenArr){
        $mingOAuth = new MingOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        return $mingOAuth->get('https://soap.soha.vn/api/a/GET/shu/show?oauth_token='.$accessTokenArr['oauth_token'].
            '&oauth_token_secret='.$accessTokenArr['oauth_token_secret'].'&user_id='.$accessTokenArr['user_id']);
    }


    public static function getAuthorizeURL($url){
        $mingOAuth = new MingOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET);
        $token = $mingOAuth->getRequestToken($url);
        Session::put('soha_request_token',$token);
        Log::debug($token);
        Log::debug($mingOAuth->getAuthorizeURL($token));
        return  $mingOAuth->getAuthorizeURL($token);
    }

    public static function getAccessToken($oauth_token, $oauth_verifier)
    {
        $requestToken = Session::get('soha_request_token');
        $mingOAuth = new MingOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET,
            $requestToken['oauth_token'], $requestToken['oauth_token_secret']);
        return $mingOAuth->getAccessToken($oauth_verifier);
    }

    public static function processAuthSso()
    {
        if(!Auth::guest()){
            $user = Auth::user();
            //khong phai user soha
            if($user->source != self::EXTERNAL_PORTAL_CODE)
                return Redirect::to(self::getAuthorizeURL('/'));
            else{
                if(!Input::has('signed_request') && !Session::has('soha_signed_request')){
                    return Redirect::to(self::getAuthorizeURL('/'));
                }else{
                    Session::push('soha_signed_request', Input::get('signed_request'));
                }
            }
            return null;
        }else{

            if(!Input::has('signed_request') && !(Input::has('oauth_token') && Input::has('oauth_verifier'))){
                return Redirect::to(self::getAuthorizeURL(Request::url()));
            }

            if(Input::has('signed_request')){
                $signed_request = Input::get('signed_request');
                Log::debug($signed_request);
                $result = self::parse_signed_request($signed_request);
                if(!$result)
                    return Redirect::to('/');
                else{
                    Log::debug($result);
                    $sohaUser = self::getUserInfo($result['access_token']);
                    Log::debug($sohaUser->username);
                    Log::debug($sohaUser->email);
                    \Util\GameHelper::pushIdUser(array('username'=>$sohaUser->username,'id'=>$sohaUser->id,
                        'email'=>$sohaUser->email, 'source'=>self::EXTERNAL_PORTAL_CODE));
                }
                return null;
            }

            if(Input::has('oauth_token') && Input::has('oauth_verifier') ){
                $accessToken = self::getAccessToken(Input::get('oauth_token'), Input::get('oauth_verifier'));
                $sohaUser = self::getUserInfoByArr($accessToken);
                if($sohaUser->status == 1){
                    $user = $sohaUser->user;
                    Log::debug($user->username);
                    Log::debug($user->email);
                    \Util\GameHelper::pushIdUser(array('username'=>$user->username, 'id'=>$user->id,
                        'email'=>$user->email, 'source'=>self::EXTERNAL_PORTAL_CODE));
                }
                else{
                    return Redirect::to('/');
                }
                return null;
            }

        }


    }

}