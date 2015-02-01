<?php

namespace Util\Game;

use Auth;
use Util\Exceptions\SystemException;
use Util\Oauth2\PlaygateIDRestClient;

class Rong{

//123.30.145.191
    const PAY_URL="http://221.132.24.115/m/api_pay.php";

    const SITE="vi";
    const CM=1;
    const PAY_KEY="~jk2-0*7D8";


    const SUCCESS_PAY_CODE = '1';

    public $codeArr = array('0'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '1'=>'Nạp game thành công',
        '2'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '3'=>'Nhân vật được nạp không tồn tại. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '4'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '5'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '6'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '7'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-7'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-1'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-4'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '-102'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        );

    public function getPlayUrl($gameServer){
        if(Auth::guest())
            $username = session_id();
        else{
            $user = Auth::user();
            if($user->session_id && $user->session_subdomain == 'rong')
                $username = $user->session_id;
            else
                $username = $user->username;
        }
        $url = $gameServer->url;

        $time = time();
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $sign = md5($username.$time.$key.self::CM);
//        return $url.'?lang=vi_vn&site='.self::SITE.'&s='.$serverId.'&username='.$user->username.'&time='.$time.'&cm='.self::CM.'&flag='.$sign;
        return $url.'&s='.$serverId.'&username='.$username.'&time='.$time.'&cm='.self::CM.'&flag='.$sign;
    }

    public function getPayUrl($gameServer, $tombo, $amount){
        $url = self::PAY_URL;
        $user = Auth::user();
        if($user->session_id && $user->session_subdomain == 'rong')
            $username = $user->session_id;
        else
            $username = $user->username;
        $time = time();
        $order = $username.'_'.$time;
        $serverId = $gameServer->sid;
        $sign = md5($username.$order.$tombo.$time.self::PAY_KEY);
        return $url.'?s='.$serverId.'&user='.$username.'&order='.$order.'&money='.$tombo.'&time='.$time.'&sign='.$sign;
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