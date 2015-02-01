<?php
use Gregwar\Captcha\CaptchaBuilder;
use Util\Exceptions\SystemException;
use Util\GameHelper;
use Util\Oauth2\PlaygateIDRestClient;
use Util\Oauth2Helper;

include_once app_path('external/soha/libs/soapconfig.php');
include_once app_path('external/soha/libs/OAuth.php');
include_once app_path('external/soha/libs/SPOAuthDataStore.php');


class TxnsController extends BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth-sso', array('except'=>array(
            'sohaPaymentCallback', 'payRedirectSoha')));
    }


    public function getChargeSoha()
    {
        $this->Loadlayout('news');

        $this->layout->content = View::make('front.txns.charge_soha');

        $this->layout->title = 'Nạp Scoin | ';
    }


    public function getCharge()
    {
        $this->Loadlayout('news');
        $card_amounts = Config::get('common.card_amounts');

        $this->layout->content = View::make('front.txns.charge', array(
            'card_amounts'=>$card_amounts
        ));

        $this->layout->title = 'Nạp tiền | ';
    }


    function validateChargeSoha(){
        $validator = Validator::make(
            array(
                'serverId' => Input::get('serverId'),
                'scoin' => Input::get('scoin'),
            ),
            array(
                'serverId' => 'required',
                'scoin' => 'required',
            )
        );
        if($validator->fails())
            throw new SystemException('Hãy chọn máy chủ và Scoin!');

        $builder = new CaptchaBuilder;
        $builder->setPhrase(Session::get('captchaPhrase'));
        if(!$builder->testPhrase(Input::get('captcha'))) {
            throw new SystemException('Mã an toàn không chính xác');
        }

    }

    public function doChargeSoha()
    {
        try{
            Log::debug('enter');
            $this->validateChargeSoha();
            Log::debug('enter11');
            $myApp = App::make('myApp');
            $txn=new SohaTxn();
            $txn->user_id=Auth::user()->id;
            $txn->game_id=$myApp->game->id;
            $txn->game_server_id=Input::get('serverId');
            $txn->pay_amount=Input::get('scoin');
            $txn->game_amount= $txn->pay_amount*SohaHelper::ID_EXCHANGE_RATE;
            $txn->description="User ".Auth::user()->username." nap ".Input::get('scoin');
            $txn->ref_txn_id=Auth::user()->username."_".$myApp->game->id."_".$txn->game_server_id.'_'.time();
            $txn->save();

            return Response::json(array('success'=>true, 'orderId'=>$txn->ref_txn_id));
        }catch (Exception $e){
            return Response::json(array('success'=>false,  'message'=>$e->getMessage()));
        }
    }


    function validateCharge(){
        $validator = Validator::make(
            array(
                'serverId' => Input::get('serverId'),
                'amount' => Input::get('amount'),
            ),
            array(
                'serverId' => 'required',
                'amount' => 'required',
            )
        );
        if($validator->fails())
            throw new SystemException('Vui lòng chọn máy chủ và số tiền!');

        $builder = new CaptchaBuilder;
        $builder->setPhrase(Session::get('captchaPhrase'));
        if(!$builder->testPhrase(Input::get('captcha'))) {
            throw new SystemException('Mã an toàn không chính xác');
        }

        $account_balances = Oauth2Helper::loadAccounts();
        if(Input::get('amount') > $account_balances->mainBalance+$account_balances->subBalance){
            throw new SystemException('Số xu trong tài khoản không đủ');
        }

    }


    public function doCharge()
    {
        try{

            $this->validateCharge();
            $myApp = App::make('myApp');
            $txn=new Txn();
            $txn->user_id=Auth::user()->id;
            $txn->game_id=$myApp->game->id;
            $txn->game_server_id=Input::get('serverId');
            $txn->pay_amount=Input::get('amount');
            $txn->game_amount= round($txn->pay_amount*$myApp->game->exchange_rate);
            $txn->description="User ".Auth::user()->username." nap ".Input::get('amount');
            $txn->ref_txn_id=Auth::user()->username."_".$myApp->game->id."_".$txn->game_server_id.'_'.time();
            $txn->save();

            $playgateIdRestClient=new PlaygateIDRestClient();
            $resp=$playgateIdRestClient->post('/payments-api/pay-by-accounts', array(
                'ref_txn_id'=>$txn->ref_txn_id,
                'amount'=>$txn->pay_amount,
                'description'=>$txn->description,
                'access_token'=>Oauth2Helper::getAccessToken()
            ));

            $txn->status = $resp['status'];
            $txn->save();
            if($txn->status == 200){
                $gameResult = GameHelper::pay($txn->game_server_id, $txn->game_amount, $txn->pay_amount);
                $txn->game_response = $gameResult;
                $txn->save();
                GameHelper::processPayResult($gameResult,$txn);
            }
            else
                return Response::json(array('success'=>false, 'status'=>$resp['status'], 'message'=>$resp['error_message']));

            return Response::json(array('success'=>true, 'status'=>$resp['status'], 'message'=>'Nạp xu thành công'));
        }catch (Exception $e){
            return Response::json(array('success'=>false, 'status'=>400, 'message'=>$e->getMessage()));
        }
    }


    function validateChargeCard(){
        $validator = Validator::make(
            array(
                'serverId' => Input::get('serverId'),
                'pin' => Input::get('pin'),
                'seri' => Input::get('seri'),
                'cardType' => Input::get('cardType'),
            ),
            array(
                'serverId' => 'required',
                'pin' => 'required',
                'seri' => 'required',
                'cardType' => 'required',

            )
        );
        if($validator->fails())
            throw new SystemException('Thiếu thông tin số pin hoặc seri hoặc loại thẻ');
        $builder = new CaptchaBuilder;
        $builder->setPhrase(Session::get('captchaPhrase'));
        if(!$builder->testPhrase(Input::get('captcha'))) {
            throw new SystemException('Mã an toàn không chính xác');
        }
    }

    public function doChargeCard()
    {
        try{
            $this->validateChargeCard();
            $myApp = App::make('myApp');
            $txn=new CardTxn();
            $txn->user_id=Auth::user()->id;
            $txn->game_id=$myApp->game->id;
            $txn->game_server_id=Input::get('serverId');
            $txn->pay_amount=0;
            $txn->game_amount=0;
            $txn->pin=Input::get('pin');
            $txn->seri=Input::get('seri');
            $txn->card_type=Input::get('cardType');
//            $txn->game_amount= round($txn->pay_amount/$myApp->game->exchange_rate);
            $txn->description="User ".Auth::user()->username." nap ".Input::get('pin').'/'.Input::get('seri').'@'.Input::get('cardType');
            $txn->ref_txn_id=Auth::user()->username."_".$myApp->game->id."_".$txn->game_server_id.'_'.time();
            $txn->save();

            $playgateIdRestClient=new PlaygateIDRestClient();
            try{
                $resp=$playgateIdRestClient->post('/payments-api/pay-by-mobile-card', array(
                    'ref_txn_id'=>$txn->ref_txn_id,
                    'card_type'=>$txn->card_type,
                    'pin'=>$txn->pin,
                    'seri'=>$txn->seri,
                    'description'=>$txn->description,
                    'access_token'=>Oauth2Helper::getAccessToken()
                ), array(), 120);
            }catch (Exception $e){
                throw new SystemException("Lỗi timeout. Vui lòng thử lại");
            }


            $txn->status = $resp['status'];
            $txn->save();
//            Log::debug($resp);
            if($resp['status'] == 200){
//                Log::debug('do pay game'.$resp['txn_amount']);
                $txn->pay_amount = $resp['txn_amount'];
                $txn->game_amount= round($txn->pay_amount*$myApp->game->exchange_rate);

                $gameResult = GameHelper::pay($txn->game_server_id, $txn->game_amount, $txn->pay_amount);
                $txn->game_response = $gameResult;
                $txn->save();
                GameHelper::processPayResult($gameResult,$txn);

            }
            else{
                return Response::json(array('success'=>false, 'message'=>$resp['error_message']));
            }


            return Response::json(array('success'=>true,  'message'=>'Nạp XU thành công'));
        }catch (Exception $e){
            return Response::json(array('success'=>false, 'message'=>$e->getMessage()));
        }
    }


    public function sohaPaymentCallback()
    {
        $oauthServer = new OAuthServer(new SPOAuthDataStore());
        $oauthServer->add_signature_method( new OAuthSignatureMethod_HMAC_SHA1());

        $game = App::make('myApp')->game;
        Log::debug('enter pay call back');
        try {
            $req = OAuthRequest::from_request();
            //this is a security step,
            //to verify whether this call is from SOAP or not
            if ($oauthServer->verify_url($req)) {
                Log::debug('Request is signed');
                //print_r(json_encode("Request is signed!"));
            }
            $method = $_POST['method'];
            //if method is get order info
            if ($method == 'payments_get_items') {
                //order_info is the param created by game/app when user click to buy something in game
                //from order_info param, game/app retrieves info to create specific order
                $order_info = $_POST['order_info'];

                //id of user who initiated the payment
                $user_id = $_POST['user_id'];

                //order_id is a unique string that SOAP creates for each order, use this as an ID for order
                $order_id = $_POST['order_id'];
                //Set this order status to waiting to confirm

                //example order details;
                $sohaTxn = SohaTxn::where('ref_txn_id', '=', $order_info)
                    ->first();

				//Kiem tra neu gd duoc xu ly roi thi thoi, tranh trung lap
				if($sohaTxn->status==200){
					exit('GD da duoc xu ly tu truoc!');
				}

				$sohaTxn->order_id = $order_id;
                $sohaTxn->save();
                $order_details = array('item_id'=>$game->subdomain.'_'.$sohaTxn->game_server_id,
                    'title'=>'Nạp Scoin vào '.$game->name,
                    'description'=>'Nạp Scoin vào '.$game->name,
                    'image_url'=>'',
                    'product_url'=>'',
                    'price'=>$sohaTxn->pay_amount,
                    'data'=>'Nạp Scoin vào '.$game->name);
                //return order details to SOAP
                print_r(json_encode($order_details));
                exit;
            } else if ($method == 'payments_status_update'){
                $order_status = $_POST['status'];
                $order_id = $_POST['order_id'];
                if ($order_status == 'settled') {
					//order is ok now, update the order with the $order_id
                    $sohaTxn = SohaTxn::where('order_id', '=', $order_id)
                        ->first();

					//Kiem tra neu gd duoc xu ly roi thi thoi, tranh trung lap
					if($sohaTxn->status==200){
						exit('GD da duoc xu ly tu truoc!');
					}

                    $sohaTxn->status = 200;
                    $sohaTxn->save();


                    $sohaTxn->game_amount= round($sohaTxn->pay_amount*SohaHelper::ID_EXCHANGE_RATE);
                    Auth::loginUsingId($sohaTxn->user_id);
                    $gameResult = GameHelper::pay($sohaTxn->game_server_id, $sohaTxn->game_amount, $sohaTxn->pay_amount);
                    $sohaTxn->game_response = $gameResult;
                    $sohaTxn->save();
//                    GameHelper::processPayResult($gameResult,$sohaTxn);

                    $result = array('status'=>'settled');
                    print_r(json_encode($result));
                    exit;
                }
            }
        } catch (OAuthException $e) {
            //not from soap, return error
            $error_mess = $e->getMessage();
            print_r (json_encode($error_mess));
        }

    }



    public  function payRedirectSoha(){
        if(Input::has('status')){
            $order_id = Input::get('order_id');
            return "Giao dịch '".$order_id."' thành công!";
        }
        else {
            $order_id = Input::get('order_id');
            $error_code = Input::get('error_code');
            return "Giao dịch '".$order_id."' không thành công!";
        }
    }





}
