<?php

use EModel\GameServer;
use EModel\GiftCode;
use EModel\GiftCodeType;
use Util\CommonConstant;
use Util\GameHelper;

class GameController extends BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth-sso', array('except'=>array(
            'servers','preCharge','play','playTrial'
        )));
    }

    public function servers()
    {
        $this->loadLayout( 'server');
        $this->layout->title = 'Server | ';
    }



    public function play($server_id='')
    {

        $myApp=App::make('myApp');
        $this->loadLayout('play');
        $game = $myApp->game;

        $externalPortal = $myApp->externalPortal;
        if('zing' == $externalPortal)
            $applyFor = $externalPortal;
        else
            $applyFor = 'maxgate';

        if($server_id!=''){


                $server = GameServer::where('active', '=' ,1)
                    ->where('apply_for', '=', $applyFor)
                    ->where('id', '=', $server_id)->first();


            if(!$server)
                return Redirect::back()->with('message','Server không tồn tại hoặc chưa active');
        }

        else{

            $aServer = GameHelper::getLatestPlayedServer($applyFor);
            if($aServer)
                $server = $aServer;
            else{
                $server =GameServer::where('active', '=' ,1)
                    ->where('apply_for', '=', $applyFor)
                    ->where('game_id', '=' , $game->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        if($server->active != 1){
			$trusted_ip=array();
			require_once public_path('media/config/trusted_ip.php');
			if(!in_array(Request::getClientIp(),$trusted_ip)){
				return Redirect::back()->with('message','Server không tồn tại hoặc chưa active');
			}
		}

		$url = GameHelper::getPlayUrl($game, $server);
        Event::fire('game.play', array($server));
        $this->layout->iframe = $url;
        $this->layout->server = $server;
        $this->layout->title = "S".$server->order_number.": ".$server->name." | ";
        $allServer = GameHelper::getPlayedServer();
        $this->layout->allServer = $allServer;
    }

    public function playTrial()
    {
        session_start();
        $myApp=App::make('myApp');
        $this->loadLayout('play-trial');
        $game = $myApp->game;
        $applyFor = 'maxgate';
        $server = GameHelper::getNewestServer($applyFor);

        $source = Input::get('_source');
        /*if($server->active != 1){
            $trusted_ip=array();
            require_once public_path('media/config/trusted_ip.php');
            if(!in_array(Request::getClientIp(),$trusted_ip)){
                return Redirect::back()->with('message','Server không tồn tại hoặc chưa active');
            }
        }*/
        $playTrialSession = new PlayTrialSession();
        $playTrialSession->session_id = session_id();
        $playTrialSession->source = $source;
        $playTrialSession->game_id = $game->id;
        $playTrialSession->server_id = $server->id;
        $playTrialSession->save();

        $url = GameHelper::getPlayUrl($game, $server);
        $this->layout->iframe = $url;
        $this->layout->server = $server;
        $this->layout->source = $source;
        $this->layout->title = "S".$server->order_number.": ".$server->name." | ";
    }

    public function getGiftCode()
    {
        $myApp=App::make('myApp');
        $this->loadLayout('news');
        $giftcode_types = GiftCodeType::where('game_id',$myApp->game->id)
            ->where(function($query){
                $query->where('apply_for',null)->orWhere('apply_for','');
            })
            ->where('active',CommonConstant::GIFT_CODE_ACTIVE)->orderBy('created_at','desc')->get();
        $this->layout->content = View::make('front.game.gift_code', array(
            'giftcode_types'=>$giftcode_types,
        ));
        $this->layout->title = "Giftcode | ";
    }

    public function postGiftCode()
    {
        $type = Input::get('giftcode_type');
        $game_server_id = Input::has('game_server_id') ? Input::get('game_server_id') : null;

        if(GiftCodeType::find($type)->active != CommonConstant::GIFT_CODE_ACTIVE)
        {
            return Response::json(array(
                'success'=>false,
                'msg'=>'Loại giftcode không hợp lệ'
            ));
        }

        //Nếu user đã từng nhận code
        $record = GiftCode::where('giftcode_type_id',$type)->where('user_id',Auth::user()->id)->first();
        if(isset($record))
        {
            return Response::json(array(
                'success'=>true,
                'code'=>$record->code,
                'msg'=>'Chúc mừng bạn đã nhận được giftcode'
            ));
        }

        //Nếu user chưa từng nhận code
        $record = GiftCode::where('giftcode_type_id',$type)->where('user_id',null)->first();
        if(isset($record))
        {
            $record->user_id = Auth::user()->id;
            $record->save();
            return Response::json(array(
                'success'=>true,
                'code'=>$record->code,
                'msg'=>'Chúc mừng bạn đã nhận được giftcode'
            ));
        }

        //Nếu đã hết code
        return Response::json(array(
            'success'=>false,
            'msg'=>'Rất tiếc! Giftcode này đã phát hết'
        ));
    }

    public function preCharge(){
        $game = App::make('myApp')->game;
        if(Input::has('userId')){
            $userId = Input::get('userId');
            if(substr($userId, 0, strlen('soha_')) == 'soha_')
                return Redirect::to('http://'.$game->subdomain.'.sohagame.vn/nap-soha');
            elseif(substr($userId, 0, strlen('plg_')) == 'plg_')
                return Redirect::to('http://'.$game->subdomain.'.playgate.vn/nap-tien');
            else
                return Redirect::to('http://'.$game->subdomain.'.maxgate.vn/nap-tien');
        }
        return Redirect::to('http://'.$game->subdomain.'.maxgate.vn/nap-tien');
    }

    public function playByOrderNumber($prefix, $orderNumber){
        $myApp=App::make('myApp');
        $this->loadLayout('play');
        $game = $myApp->game;

        $externalPortal = $myApp->externalPortal;
        if($externalPortal)
            $applyFor = $externalPortal;
        else
            $applyFor = 'maxgate';

        $server = GameServer::where('game_id', '=', $game->id)
            ->where('order_number', '=', $orderNumber)
            ->where('active', '=' ,1)
            ->where('apply_for', '=', $applyFor)
            ->first();
        if(!$server)
            return Redirect::back()->with('message','Server không tồn tại hoặc chưa active');

        if($server->active != 1){
            $trusted_ip=array();
            require_once public_path('media/config/trusted_ip.php');
            if(!in_array(Request::getClientIp(),$trusted_ip)){
                return Redirect::back()->with('message','Server không tồn tại hoặc chưa active');
            }
        }

        $url = GameHelper::getPlayUrl($game, $server);
        Event::fire('game.play', array($server));
        $this->layout->iframe = $url;
        $this->layout->server = $server;
        $this->layout->title = "S".$server->order_number.": ".$server->name." | ";
        $allServer = GameHelper::getPlayedServer();
        $this->layout->allServer = $allServer;

    }



}
