<?php

namespace Util\Game;

use Auth;
use Cookie;
use Util\Exceptions\SystemException;
use Util\Oauth2\PlaygateIDRestClient;

class Bado{

//123.30.145.191
    const PAY_URL="http://x.x.x.x/sglj-admin/pay";

    const PF="phucthanh";
    const AD="";
    const IUID="0";
    const PAY_KEY="3RlIQUZxZG6XjrLkNOkT";
    const OPT="phucthanh";

    const PAY_OPT="phucthanh";
    const CURRENCY="VND";
    const SUCCESS_PAY_CODE = '0';

    public $codeArr = array('0'=>'Nạp tiền vào game thành công',
        '-2'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-3'=>'Nhân vật được nạp không tồn tại. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-4'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-5'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-6'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-7'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-8'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        );

    public function getPlayUrl($gameServer){
        $url = $gameServer->url;
        if(Auth::guest())
            $username = session_id();
        else{
            $user = Auth::user();
            if($user->session_id && $user->session_subdomain == 'bado')
                $username = $user->session_id;
            else
                $username = $user->username;
        }

        $time = time();
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $sign = md5(self::OPT.$serverId.$username.self::IUID.$time.$key);
        return $url.'?pf='.self::PF.'&serverId='.$serverId.'&userId='.$username.'&time='.$time.'&ad='.self::AD.'&iuid='.self::IUID.'&sign='.$sign;
    }

    public function getPayUrl($gameServer, $tombo, $amount){

        $url = str_replace('x.x.x.x', $gameServer->ip, self::PAY_URL);
        $user = Auth::user();
        if($user->session_id && $user->session_subdomain == 'bado')
            $username = $user->session_id;
        else
            $username = $user->username;
        $time = time();
        $order = $username.'_'.$time;
        $serverId = $gameServer->sid;
        $sign = md5(self::PAY_OPT.$serverId.$username.$tombo.$order.self::PAY_KEY.self::CURRENCY.$amount);
        return $url.'?opt='.self::PAY_OPT.'&server='.$serverId.'&user='.$username.'&tombo='.$tombo.'&order='.$order.'&sign='.$sign.'&currency='.self::CURRENCY.'&amount='.$amount;
    }

    public function processPayResult($gameResult,$txn){
        if($gameResult != self::SUCCESS_PAY_CODE){
            $txn->status = 300;
            $txn->save();
            $playgateIdRestClient=new PlaygateIDRestClient();
            $response = $playgateIdRestClient->post('/payments-api/refund-payment', array('ref_txn_id'=>$txn->ref_txn_id));
            if($response['status'] != 200)
                throw new SystemException("Lỗi khi nạp vào game. Vui lòng liên hệ Maxgate để giải quyết.");
            throw new SystemException($this->codeArr[$gameResult]);
        }
    }

}

?>