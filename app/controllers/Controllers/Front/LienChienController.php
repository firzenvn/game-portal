<?php


use Util\Oauth2\PlaygateIDOauth2;

class LienChienController extends FrontBaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/


    public function home($league_id = '')
    {
        $myApp=App::make('myApp');
        $this->layout = 'lienchien_index';
        $this->setupLayout();
        $this->layout->level_range = Config::get('common.game_leagues_level_range.'.$myApp->game->subdomain);
        if($league_id){
            $league = FightLeague::find($league_id);
            if(!$league || $league->active != 1)
                $league = FightLeague::where('active', '=', 1)
                    ->orderBy('id', 'desc')->first();
        }else{
            $league = FightLeague::where('active', '=', 1)
                ->orderBy('id', 'desc')->first();
        }

        $this->layout->league = $league;

        $allLeagues = FightLeague::where('active', '=', 1)
            ->orderBy('id', 'asc')->limit(4)->get();
        $this->layout->allLeagues = $allLeagues;

    }

    public function loadResult()
    {
        $leagueId = Input::get('leagueId');
        $levelRange = Input::get('levelRange');

        $allMatchs = LeagueMatch::join('league_users as lu1', 'league_matchs.first_user_id', '=', 'lu1.id')
            ->join('league_users as lu2', 'league_matchs.second_user_id', '=', 'lu2.id')
            ->where('league_matchs.league_id', '=' ,$leagueId)
            ->where('range_level', '=' ,$levelRange)
            ->orderBy('round','desc')
            ->orderBy('id','desc')
            ->get(array('league_matchs.*', 'lu1.username as first_username','lu2.username as second_username'));

        return View::make('lienchien_result')->with('allMatchs', $allMatchs);
    }

    public function loadList(){
        $sId = Input::get('sId');
        $rankType = Input::get('rankType');
        $page = Input::get('page');

        return View::make('lienchien_list',array(
            'sId'=>$sId,
            'rankType'=>$rankType,
            'page'=>$page
        ));
    }

    public function loadWinner(){
        $leagueId = Input::get('leagueId');
        $levelRange = Input::get('levelRange');
        $winner = LeagueWinner::where('league_id',$leagueId)->where('level_range',$levelRange)->first();
        $content = $winner ? $winner->content : '';
        return View::make('lienchien_winner',array(
            'content'=>$content
        ));
    }



}
