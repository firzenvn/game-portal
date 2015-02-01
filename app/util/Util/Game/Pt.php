<?php

namespace Util\Game;

use Auth;
use Util\Exceptions\SystemException;
use Util\Oauth2\PlaygateIDRestClient;

class Pt{

    const SUCCESS_PAY_CODE = '1';

    public $codeArr = array(
        '1'=>'Nạp game thành công',
        '2'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '3'=>'Nhân vật được nạp không tồn tại. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '4'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '5'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '6'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '7'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',
        '8'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Maxgate Id để nạp lại.',

        );

    public function getPlayUrl($gameServer){
        if(Auth::guest())
            $username = session_id();
        else{
            $user = Auth::user();
            if($user->session_id && $user->session_subdomain == 'pt')
                $username = $user->session_id;
            else
                $username = $user->username;
        }
        $url = $gameServer->url;
        $time = time() - 50;
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $username = 'mxg_'.$username;
        $sign = md5('uname='.strtolower($username).'&sid='.$serverId.'&time='.$time.'&key='.$key);
        return $url."?server_ip=".$gameServer->ip."&server_port=8001&uname=".strtolower($username)."&sid=".$serverId."&time=".$time."&sign=".$sign;
    }

    public function getPayUrl($gameServer, $tombo, $amount){
        $url = 'http://'.$gameServer->ip.':7001/pay?';
        $user = Auth::user();
        if($user->session_id && $user->session_subdomain == 'pt')
            $username = $user->session_id;
        else
            $username = $user->username;

        $time = time() - 50;
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $paysn = "1000".time();
        $money = $amount*100;
        $username = 'mxg_'.$username;
        $sign = md5("paysn=".$paysn."&uname=".strtolower($username)."&sid=".$serverId."&money=".$money."&amount=".$tombo."&time=".$time."&key=$key");
        return $url.'paysn='.$paysn.'&uname='.strtolower($username).'&sid='.$serverId.'&money='.$money."&amount=".$tombo.'&time='.$time.'&sign='.$sign;
    }

    public function processPayResult($gameResult,$txn){
        $snip = str_replace(array( ' ', "\n", "\t", "\r"), '', $gameResult);
        if($snip != self::SUCCESS_PAY_CODE){
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