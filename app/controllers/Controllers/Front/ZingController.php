<?php
use EModel\GameServer;
use Util\Exceptions\SystemException;
use Util\GameHelper;

include_once app_path('external/zing/BaseZingMe.php');
include_once app_path('external/zing/ZME_Me.php');
include_once app_path('external/zing/ZME_User.php');
include_once app_path('external/zing/ZC2_CallbackResultData.php');


class ZingController extends BaseController {
    public function home()
    {
        $zm_Me	=	new	ZME_Me(ZingHelper::getConfig());
        if(!Input::has('signed_request')){
            $this->loadLayout( 'dump');
            $param = http_build_query(Input::except(array('code','sign_user','username', 'signed_request', 'session_id', '_ver', '_srvid')));
            $this->layout->param = $param;
        }else{
            $signed_request = Input::get('signed_request');
            $access_token	=	$zm_Me->getAccessTokenFromSignedRequest($signed_request);
            Session::set('zing_access_token', $access_token);
            $me	=	$zm_Me->getInfo($access_token);
            \Util\GameHelper::pushIdUser(array('username'=>$me['username'],'id'=>$me['id'],
                'email'=>'',
                'source'=>ZingHelper::EXTERNAL_PORTAL_CODE));
            $this->loadLayout( 'server');
            $this->layout->title = 'Server | ';
            $param = http_build_query(Input::except(array('code','sign_user','username', 'signed_request', 'session_id', '_ver', '_srvid')));
            $this->layout->param = $param;
            if(Input::has('_srvid')){
                $this->layout->needRedirect = 0;

                $serverId = Input::get('_srvid');
                $orderNumber = substr($serverId, 1);
                $gameServer = GameServer::where('order_number', '=', $orderNumber)
                    ->where('apply_for', '=', 'zing')
                    ->where('active', '=', '1')
                    ->first();
                if(!$gameServer){
                    $gameServer =  GameHelper::getNewestServer('zing');
                    $this->layout->serverId = $gameServer->id;
                }else{
                    $this->layout->serverId = Input::get('_srvid');
                }



            }else{
                $this->layout->needRedirect = 1;
                if(Input::has('_src')){
                    if(in_array(Input::has('_src'), array('m','as-hot','as-oserver','gwlc',
                        'event','login','banner_login','notify_Monday', 'as-bnmid','as-promotion','ngw'))){
                        $gameServer = GameHelper::getNewestServer('zing');
                        $this->layout->serverId = 'z'.$gameServer->order_number;
                    }
                }else{
                    $gameServer = GameHelper::getLatestPlayedServer('zing');
                    if(!$gameServer)
                        $gameServer =  GameHelper::getNewestServer('zing');
                    $this->layout->serverId = 'z'.$gameServer->order_number;;

                }
            }
        }
    }


    public function charge(){
        if(!Session::has('zing_access_token'))
            return Redirect::to('http://login.me.zing.vn/?url=http://me.zing.vn/apps/'.ZingHelper::getAppId());
        $this->loadLayout( 'zing_charge');
        $this->layout->title = 'Nạp xu | ';
        $allServer = GameHelper::getPlayedServer();
        $this->layout->allServer = $allServer;
        $this->layout->balance = ZingHelper::getBalance();

    }


    public function doCharge(){
        if(!Input::has('serverId') ||  !Input::has('zingXu'))
            return Response::json(array('success'=>false, 'msg'=>'Thiếu thông tin server hoặc số Zing xu'));
        $serverId = Input::get('serverId');
        $zingXu = Input::get('zingXu');
        $url = ZingHelper::buildBillUrl($serverId, $zingXu);
        return Response::json(array('success'=>true, 'bill_url'=>$url));
    }


    public function paymentCallback(){
        try{
            if(!Input::has('data'))
                throw new SystemException('Không nhận được dữ liệu');
            $encrytedData = Input::get('data');
            $data = new ZC2_CallbackResultData();
            $ret = ZCypher2Lib::decodeDataForCallbackResult(ZingHelper::getKey2() , $encrytedData, $data);
            if($ret == 0){
                $zingTxn = ZingTxn::where('ref_txn_id', '=', $data->billNo)->first();
                if(!$zingTxn)
                    return '-1001:Giao dịch không tồn tại';
                $zingTxn->status = 0;
                $zingTxn->order_id = $data->txID_ZingCredits;
                $zingTxn->save();

                Auth::loginUsingId($zingTxn->user_id);
                $gameResult = GameHelper::pay($zingTxn->game_server_id, $zingTxn->game_amount, $zingTxn->pay_amount);
                $zingTxn->game_response = $gameResult;
                $zingTxn->save();
                return '1000:Thành công';
            }else{
                return '-1002:Giải mã thất bại';
            }
        }catch (Exception $e){
            Log::error($e->getMessage());
            return '-1003:Lỗi cổng game';
        }

    }
}
