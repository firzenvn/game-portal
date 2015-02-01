<?php


use Util\Oauth2\PlaygateIDOauth2;
use Util\Oauth2\PlaygateIDRestClient;
use Util\Oauth2Helper;

class SpinController extends FrontBaseController {



    public function home()
    {
//        $myApp=App::make('myApp');
        $this->layout = 'vongquay_index';
        $allWinTurn = SpinTurn::join('users', 'spin_turns.user_id', '=' , 'users.id')
            ->where('bonus_amount', '>' , 0)
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get(array('spin_turns.*', 'users.username'));
        $allMyTurn = array();
        if(Auth::user())
        $allMyTurn = SpinTurn::where('bonus_amount', '>' , 0)
            ->where('user_id', '=' , Auth::user()->id)
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get();
        $this->setupLayout();
        $this->layout->allWinTurn = $allWinTurn;
        $this->layout->allMyTurn = $allMyTurn;
    }


    public function check()
    {
        if(Auth::guest())
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng đăng nhập.'));
        if(!Input::has('betStr'))
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng nhập số.'));
        $betStr = Input::get('betStr');
        if(strlen($betStr) == 0)
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng nhập số.'));

        $tmpArr = explode('$$',$betStr);
        $betArr = array();
        foreach ($tmpArr as $aBetStr) {
          $aBetArr =  explode(':',$aBetStr);
          $betArr[$aBetArr[0]] =   $aBetArr[1];
        }


        $myAccount = \Util\Oauth2Helper::loadAccounts();
        if(array_sum($betArr) > $myAccount->mainBalance)
            return Response::json(array('success'=>false, 'msg'=>'Bạn không đủ xu để quay số.'));

        $token=time().'_'.\Util\CommonHelper::rand_string(10);
        $spinTurn = new SpinTurn(array('user_id'=>Auth::user()->id, 'token'=>$token, 'amount'=>array_sum($betArr)));
        $spinTurn->save();
        foreach ($betArr as $key => $val) {
            $spinDetail = new SpinDetail(array('spin_turn_id'=>$spinTurn->id, 'slot'=>$key , 'amount' => $val ));
            $spinDetail->save();
        }

        return Response::json(array('success'=>true, 'data'=>array('spin_turn_id'=>$spinTurn->id, 'token'=>$token)));

    }


    public function result()
    {
        if(Auth::guest())
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng đăng nhập.'));

        if(!Input::has('token') || !Input::has('spin_turn_id') || !Input::has('result'))
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng quay lại.'));

        $ref_txn_id = 'vq_'.time().'_'.Auth::user()->id;


        $spinTurn = SpinTurn::find(Input::get('spin_turn_id'));
        if(!$spinTurn)
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng quay lại.'));
        if($spinTurn->token != Input::get('token'))
            return Response::json(array('success'=>false, 'msg'=>'Vui lòng quay lại.'));

        $description = 'user '.Auth::user()->username.' quay '.$spinTurn->amount.' xu';

        $playgateIdRestClient=new PlaygateIDRestClient();
        $resp=$playgateIdRestClient->post('/payments-api/pay-by-accounts', array(
            'ref_txn_id'=>$ref_txn_id,
            'amount'=>$spinTurn->amount,
            'description'=>$description,
            'access_token'=>Oauth2Helper::getAccessToken()
        ));
        $spinTurn->result = Input::get('result');
        $spinTurn->description = $description;
        $spinTurn->status = $resp['status'];
        $spinTurn->pay_txn_id = $ref_txn_id;
        $spinTurn->save();
        if($spinTurn->status == 200){
            $result = Input::get('result');
            $model = SpinDetail::where('spin_turn_id', '=', $spinTurn->id)
                ->where('slot', '=', $result)
                ->first();

            if($model){
                $money = $model->amount*Config::get('common.bet_propotion');
                $ref_txn_id = 'vqw_'.time().'_'.Auth::user()->id;
                $resp = $playgateIdRestClient->post('/payments-api/add-bonus-xu', array(
                    'ref_txn_id'=>$ref_txn_id,
                    'amount'=>$money,
                    'description'=>'user '.Auth::user()->username.' trúng thưởng '.$money.' xu',
                    'access_token'=>Oauth2Helper::getAccessToken()
                ));
                $spinTurn->bonus_txn_id = $ref_txn_id;
                $spinTurn->win_status = $resp['status'];

                if($spinTurn->win_status == 200){
                    $spinTurn->bonus_amount = $money;
                    $spinTurn->save();
                    return Response::json(array('success'=>true, 'msg'=>'Kết quả: ô số '.$spinTurn->result.'.Chúc mừng bạn trúng thưởng '.$money.' xu'));
                }else{
                    $spinTurn->save();
                    $playgateIdRestClient=new PlaygateIDRestClient();
                    $response = $playgateIdRestClient->post('/payments-api/refund-payment', array('ref_txn_id'=>$spinTurn->pay_txn_id));
                    if($response['status'] != 200)
                        return Response::json(array('success'=>false, 'msg'=>'Lỗi nhận thưởng. Vui lòng liên hệ admin.'));
                    else
                        return Response::json(array('success'=>false, 'msg'=>'Lỗi nhận thưởng. Vui lòng liên hệ admin.'));
                }
            }else{
                return Response::json(array('success'=>true, 'msg'=>'Kết quả: ô số '.$spinTurn->result.'.Không trúng thưởng.'));
            }

        }
        else
            return Response::json(array('success'=>false,'msg'=>'Vui lòng quay lại. Lỗi nhận từ id: '. $spinTurn->status));



    }

}
