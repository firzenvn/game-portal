<?php
namespace Util;

use App;
use EModel\GameServer;
use Auth;
use Log;
use PlayHistory;
use User;
use Config;
use Util\Exceptions\SystemException;
use Util\Oauth2\PlaygateIDRestClient;

class GameHelper{


    private static $playedServers;
    private static $latestServer;

    private static function getGameClass(){
        $myApp = App::make('myApp');
        $game = $myApp->game;
        $class = 'Util\Game\\'.ucfirst($game->subdomain);
        return  new $class;
    }
    public static  function getPlayUrl($game, $gameServer)
    {
        $gameClass = self::getGameClass();
        return $gameClass->getPlayUrl($gameServer);

    }

    public static function pushIdUser($params){
        $playgateIDRestClient = new PlaygateIDRestClient();
        $response = $playgateIDRestClient->post('/users-api/create-by-source',
            array('username'=>$params['username'] ,'client_id'=>GameHelper::getOauth2_APP_ID(),
                'email'=>$params['email'], 'source'=>$params['source'] ,'source_user_id'=>$params['id']));
        Log::debug($response);
        if($response['status'] == 200){
            $userInfo = $response['user'];
            $checkingUser = User::find($userInfo['id']);
            if(!$checkingUser){
                $checkingUser = new User(array('id'=>$userInfo['id'], 'username'=>$userInfo['username']
                , 'source'=>$params['source']));
                $checkingUser->save();
            }
            else{
                $checkingUser->fill(array('username'=>$userInfo['username'], 'source'=>$params['source']));
                $checkingUser->save();
            }
            Auth::loginUsingId($userInfo['id']);
        }else{
            throw new SystemException("Không gửi được user sang hệ thống Id");
        }
    }

    public static function pay($gameServerId,$game_amount, $amount)
    {
        $gameClass = self::getGameClass();
        $gameServer = GameServer::findOrFail($gameServerId);
        $url = $gameClass->getPayUrl($gameServer, $game_amount,$amount);
        $result = @file_get_contents($url);
        if($result === FALSE)
        {
//            throw new SystemException('Lỗi nạp xu vào game');
            $result = -999;
        }
        return $result;
    }

    public static function send($user, $gameServerId, $params)
    {
        $gameClass = self::getGameClass();
        $gameServer = GameServer::findOrFail($gameServerId);
        $url = $gameClass->getSendUrl($user, $gameServer, $params);
        $result = @file_get_contents($url);
        if($result === FALSE)
        {
//            throw new SystemException('Lỗi nạp xu vào game');
            $result = -999;
        }
        return $result;
    }

    public static function processPayResult($gameResult,$txn)
    {
        $gameClass = self::getGameClass();
        $gameClass->processPayResult($gameResult,$txn);
    }


    public static function getPlayedServer(){
        if(!self::$playedServers){
            $game = App::make('myApp')->game;
            self::$playedServers = PlayHistory::join('game_servers','play_history.server_id','=', 'game_servers.id')
                ->where('user_id','=',Auth::user()->id)
                ->where('play_history.game_id', '=' , $game->id)
//            ->where('game_servers.game_id', '=' , $game->id)
                ->where('is_first', '=' , 1)
                ->where('game_servers.active', '=', 1)
//            ->groupBy('play_history.server_id')
                ->orderBy('play_history.id', 'desc')
                ->get( array('play_history.server_id', 'game_servers.*'));
        }

        return self::$playedServers;
    }

    public static function getLatestPlayedServer($applyFor='maxgate'){
        if(!self::$latestServer){
            $game = App::make('myApp')->game;
            self::$latestServer = PlayHistory::join('game_servers','play_history.server_id','=', 'game_servers.id')
                ->where('play_history.user_id','=',Auth::user()->id)
                ->where('play_history.game_id', '=' , $game->id)
                ->where('game_servers.active', '=', 1)
                ->where('apply_for', '=', $applyFor)
                ->orderBy('play_history.id', 'desc')
                ->first( array('play_history.server_id', 'game_servers.*'));
        }

        return self::$latestServer;

    }

    public static function getNewestServer($applyFor='maxgate'){
        $game = App::make('myApp')->game;
        return GameServer::where('game_id', '=', $game->id)
            ->where('active','=', 1)->where('apply_for', '=', $applyFor)
            ->orderBy('id', 'desc')->first();
    }

    public static function getAllServer($limit,$arrangement, $applyFor='maxgate'){
        $game = App::make('myApp')->game;
        $allServer = \EModel\GameServer::where('game_id', '=', $game->id)
            ->where('active', '=', 1)
            ->where('apply_for', '=', $applyFor)->orderBy('order_number',$arrangement);
        if($limit == ''){
            $allServer = $allServer->get();
        }else $allServer = $allServer->paginate($limit);
        return $allServer;
    }

	public static function getOauth2_APP_ID(){
		return self::_getOauth2Config('app_id');
	}
	public static function getOauth2_APP_SECRET(){
		return self::_getOauth2Config('app_secret');
	}
	public static function getOauth2_LOGIN_REDIRECT_URI(){
		return self::_getOauth2Config('login_redirect_uri');
	}
	public static function getOauth2_BASE_URL(){
		return self::_getOauth2Config('base_url');
	}

	/**
	 * @return string
	 */
	public static function _getOauth2Config($configName)
	{
		$game = App::make('myApp')->game;
		$subdomain = 'default';
		if ($game)
			$subdomain = $game->subdomain;
		return Config::get('oauth2.'.$subdomain.".$configName");
	}
}

