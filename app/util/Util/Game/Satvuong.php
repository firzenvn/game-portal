<?php

namespace Util\Game;

use Auth;
use Util\Exceptions\SystemException;
use Util\Oauth2\PlaygateIDRestClient;

class Satvuong{

    const SUCCESS_PAY_CODE = '1';

    public $codeArr = array(
        '1'=>'Nạp game thành công',
        '2'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại.',
        '3'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại.',
        '4'=>'Lỗi time out',
        '5'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại.',
        '6'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại.',
        '7'=>'Nhân vật bị banned',
        '8'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại.',
        '9'=>'Chưa có nhân vật',
        '10'=>'Lỗi cộng tiền',
        '11'=>'Lỗi khi nạp vào game. Kiểm tra số dư trên Playgate Id để nạp lại',

    );

    public function getPlayUrl($gameServer){
        if(Auth::guest())
            $username = session_id();
        else{
            $user = Auth::user();
            if($user->session_id && $user->session_subdomain == 'satvuong')
                $username = $user->session_id;
            else
                $username = $user->username;
        }
        $url = $gameServer->url;
        $time = time() - 150;
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $sign = md5('uname='.strtolower($username).'&sid='.$serverId.'&time='.$time.'&key='.$key);
        return $url."?server_ip=".$gameServer->ip."&sid=".$serverId."&uname=".strtolower($username)."&time=".$time."&sign=".$sign."&cdn=http://cdn.satvuong.playgate.vn:8003/&config_url=http://satvuong.playgate.vn/media/game-tpl/satvuong/server/s".$serverId;
    }

    public function getPayUrl($gameServer, $tombo, $amount){
        $url = 'http://satvuong.com/api/pay?';
        $user = Auth::user();
        if($user->session_id && $user->session_subdomain == 'satvuong')
            $username = $user->session_id;
        else
            $username = $user->username;

        $time = time() - 150;
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $money = $tombo;
        $sign = md5('uname='.strtolower($username).'&sid='.$serverId.'&time='.$time.'&key='.$key.'&money='.$money);
        return $url.'uname='.strtolower($username).'&server_ip='.$gameServer->ip.'&time='.$time.'&sid='.$serverId.'&sign='.$sign.'&money='.$money.'&report=nojson';
    }

    public function processPayResult($gameResult,$txn){
        $snip = str_replace(array( ' ', "\n", "\t", "\r"), '', $gameResult);
        if($snip != self::SUCCESS_PAY_CODE){
            $txn->status = 300;
            $txn->save();
            $playgateIdRestClient=new PlaygateIDRestClient();
            $response = $playgateIdRestClient->post('/payments-api/refund-payment', array('ref_txn_id'=>$txn->ref_txn_id));
            if($response['status'] != 200)
                throw new SystemException("Lỗi khi nạp vào game. Vui lòng liên hệ Playgate để giải quyết.");
            throw new SystemException($this->codeArr[$gameResult]);
        }
    }

    public function getSendUrl($user, $gameServer,$params){
        $url = 'http://satvuong.com/api/sendmail?';
        if($user->session_id && $user->session_subdomain == 'satvuong')
            $username = $user->session_id;
        else
            $username = $user->username;
        $time = time() - 150;
        $serverId = $gameServer->sid;
        $key = $gameServer->secret_key;
        $money=0;
        $sign = md5('uname='.strtolower($username).'&sid='.$serverId.'&time='.$time.'&key='.$key);
        return $url.'uname='.strtolower($username).'&server_ip='.$gameServer->ip.'&time='.$time.'&sid='.$serverId.'&sign='.$sign.'&money='.$money.$params;
    }

}

?>