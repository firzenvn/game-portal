<?php

include_once app_path('external/zing/BaseZingMe.php');
include_once app_path('external/zing/ZME_Me.php');
include_once app_path('external/zing/ZME_User.php');
include_once app_path('external/zing/ZC2_BalanceData.php');
include_once app_path('external/zing/ZC2_BillingData.php');


class ZingHelper {


    const EXTERNAL_PORTAL_CODE = 'zing';
    const EXCHANGE_RATE = 1;

    public static function getAppId(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.app_id');
    }

    public static function getPublicKey(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.public_key');
    }

    public static function getSecretKey(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.secret_key');
    }

    public static function getPaymentCallback(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.payment_callback');
    }

    public static function getKey1(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.key1');
    }

    public static function getKey2(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.key2');
    }

    public static function getBillUrl(){
        $subdomain = App::make('myApp')->game->subdomain;
        return Config::get('external.zing.'.$subdomain.'.bill_url');
    }


    public static function getConfig(){
        return array('appname'=>ZingHelper::getAppId(),
            'apikey'=>ZingHelper::getPublicKey(),
            'secretkey'=>ZingHelper::getSecretKey(),
            'env'=>'production');
    }

    public static function getBalance(){

        $accessToken = Session::get('zing_access_token');
        $zm_Me	=	new	ZME_Me(self::getConfig());
        $me = $zm_Me->getInfo($accessToken);
        $data = new ZC2_BalanceData();
        $data->uid = $me['id'];
        $encodedData = ZCypher2Lib::encodeDataForBalance(self::getKey1(), $data);
        $response = @file_get_contents(self::getBillUrl().'/billing/balance?appID='
            .self::getAppId().'&data='.urlencode($encodedData));
        $result =json_decode($response);
        Log::debug($result);
        return $result;

    }

    public static function buildBillUrl($serverId, $zingXu)
    {
        $accessToken = Session::get('zing_access_token');
        $zm_Me	=	new	ZME_Me(self::getConfig());
        $me = $zm_Me->getInfo($accessToken);

        $game = App::make('myApp')->game;
        $gameCoin = $game->exchange_rate*$zingXu;

        $data = new ZC2_BillingData();
        $data->uid = $me['id'];
        $refTxn = Auth::user()->username."_".time().'_'.$serverId;
        $data->billNo = $refTxn;
        $data->itemIDs = "1";
        $data->itemNames = 'Mua '.number_format($gameCoin).' '.$game->unit;
        $data->itemQuantities = number_format($gameCoin);
        $data->itemPrices = number_format($zingXu/$gameCoin,2);
        $data->amount = $zingXu;
        $data->localUnixTimeStampInSecs = strval(time());

        $encodedData = ZCypher2Lib::encodeDataForBilling(self::getKey1(), $data);
        $link = self::getBillUrl().'/billing/requestform?appID='.self::getAppId()
            .'&data=' . urlencode($encodedData);

        $zingTxn = new ZingTxn(array('user_id'=>Auth::user()->id,'game_id'=>$game->id,
        'game_server_id'=>$serverId, 'pay_amount'=>$zingXu, 'game_amount'=>$gameCoin,
        'description'=>"User ".Auth::user()->username." nap ".$zingXu.' zing xu',
        'ref_txn_id'=>$refTxn));
        $zingTxn->save();
        return $link;
    }
}