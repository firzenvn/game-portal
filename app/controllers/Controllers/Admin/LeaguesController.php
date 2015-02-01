<?php

namespace Controllers\Admin;




use Config;
use EModel\Games;
use FightLeague;
use Illuminate\Support\MessageBag;
use Input;
use LeagueMatch;
use LeagueUser;
use LeagueWinner;
use Redirect;
use Response;
use Util\Exceptions\SystemException;
use Validator;
use View;

class LeaguesController extends AdminBaseController {

    function __construct()
    {
        parent::__construct();

    }
    public function getIndex()
    {
        $items = FightLeague::join('games', 'game_id', '=', 'games.id')
            ->select(array('fight_leagues.*', 'games.name as game'))
        ->get();

        $allGames = Games::lists('name','id');
        $this->layout->content = View::make('admin.league_index')
            ->with( 'items' ,  $items)
            ->with('allGames', $allGames);
    }

    public function getNew()
    {

        $allGame = Games::lists('name','id');
        $this->layout->content = View::make('admin.league_new')
            ->with('allGame',$allGame);

    }

    public function postNew(){
        $paramArr = Input::all();
        $newRecord = new FightLeague( array('name'=>$paramArr['name'], 'description'=>$paramArr['description'],
            'start_date'=>date('Y-m-d', strtotime($paramArr['start_date'])),
            'end_date'=>date('Y-m-d', strtotime($paramArr['end_date'])),
            'game_id'=>$paramArr['game'],
            'active'=>$paramArr['active']));

        $validator = Validator::make( $paramArr , $newRecord->validationRules );

        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $validator->errors())));

        $newRecord->save();

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Thêm mới thành công!')));
    }

    public function getDelete( $id ){
        FightLeague::where('id', '=', $id)->delete();
        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/league' )
            ->with('success', new MessageBag( array( $message ) ) );
    }

    public function getEdit( $id ){

        $league = FightLeague::findOrFail($id);
        $allGame = Games::lists('name','id');
        $allUsers = LeagueUser::where('league_id', '=' , $id)
            ->orderBy('level_range')
            ->orderBy('id')
            ->get();

        $allMatch  = LeagueMatch::join('league_users as lu1', 'lu1.id' , '=' ,'league_matchs.first_user_id')
            ->join('league_users as lu2', 'lu2.id' , '=' ,'league_matchs.second_user_id')
            ->where('league_matchs.league_id', '=' , $id)
            ->orderBy('league_matchs.range_level')
            ->orderBy('league_matchs.round', 'desc')
            ->get(array('league_matchs.*', 'lu1.username as first_username','lu2.username as second_username'));
        $game = Games::findOrFail($league->game_id);
        $allLevelRange = Config::get('common.game_leagues_level_range.' . $game->subdomain);
        $this->layout->content = View::make('admin.league_edit')
            ->with('allGame',$allGame)
            ->with('allMatch',$allMatch)
            ->with('allUsers',$allUsers)
            ->with('allLevelRange',$allLevelRange)
            ->with('game',$game)
            ->with('item',$league);

    }

    public function postEdit($id){
        $paramArr = Input::all();
        $record = FightLeague::findOrFail($id);
        $record->fill( array('name'=>$paramArr['name'], 'description'=>$paramArr['description'],
            'start_date'=>date('Y-m-d', strtotime($paramArr['start_date'])),
            'end_date'=>date('Y-m-d', strtotime($paramArr['end_date'])),
            'game_id'=>$paramArr['game'],
            'active'=>$paramArr['active']));

        $validator = Validator::make( $paramArr , $record->validationRules );

        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $validator->errors())));

        $record->save();

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Sửa thành công!')));
    }

    public function postAddUser(){
        $paramArr = Input::all();
        $record = new LeagueUser(array('username'=>$paramArr['username'], 'level_range'=>$paramArr['level_range'],
            'league_id'=>$paramArr['league_id'], 'point'=>1));

        $validator = Validator::make( $paramArr , $record->validationRules );

        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString(  $validator->errors())));

        $record->save();
        return Response::json(array('success'=>true, 'data'=> View::make('admin.league_user_row')->with('item',$record)->__toString()));

    }

    public function postEditUser(){
        $paramArr = Input::all();
        $record = LeagueUser::findOrFail($paramArr['id']);
        if(!$record)throw new SystemException("User có ID: ".$paramArr['id'].' không tồn tại!');

        $paramArr = Input::all();

        $record ->fill( array('username'=>$paramArr['username'],'level_range'=>$paramArr['level_range']));
        $validator = Validator::make( $paramArr , $record->validationRules );

        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $validator->errors())));

        $record->save();

        return Response::json(array('success'=>true,
            'data'=> $record->toArray()));

    }


    public function postDeleteUser(  ){
        $anUser = LeagueUser::find(Input::get('id'));
        $anUser->delete();
        return Response::json(array('success'=>true));
    }

    public function postLoadRoundPlayer(  ){
        $levelRange = Input::get('levelRange');
        $round = Input::get('round');
        $league_id = Input::get('league_id');

        $allUsers = LeagueUser::where('league_id','=' ,$league_id)
            ->where('point','=' ,$round)
            ->where('level_range','=' ,$levelRange)
            ->get();
        return Response::json(array('success'=>true,
            'data'=> $allUsers->toArray()));
    }


    public function postAddMatch(){
        $paramArr = Input::all();
        $record = new LeagueMatch (array('first_user_id'=>$paramArr['firstUser'],
            'second_user_id'=>$paramArr['secondUser'],
            'result'=>$paramArr['result'],
            'video_key'=>$paramArr['video'],
            'range_level'=>$paramArr['levelRange'],
            'league_id'=>$paramArr['league_id'],
            'round'=>$paramArr['round'],));

        $validator = Validator::make( $paramArr , $record->validationRules );
        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString(  $validator->errors())));
        $league = FightLeague::findOrFail($paramArr['league_id']);
        $game = Games::findOrFail($league->game_id);
        $record->save();

        //update point to user----------------------
        $record->arrangeUserPoint();

        return Response::json(array('success'=>true,
            'data'=> View::make('admin.league_match_row')
                    ->with('item',$record)
                    ->with('game',$game)
                    ->__toString()));

    }

    public function postDeleteMatch(  ){
        $aMatch = LeagueMatch::find(Input::get('id'));
        $idArr = $aMatch->strictDelete();
        return Response::json(array('success'=>true, 'data'=>$idArr));
    }


    public function getWinner( $id ){

        $league = FightLeague::findOrFail($id);
        $game = Games::findOrFail($league->game_id);
        $allLevelRange = Config::get('common.game_leagues_level_range.' . $game->subdomain);
        $allWinners = LeagueWinner::where('league_id', '=', $id)
            ->get();
        $this->layout->content = View::make('admin.league_winner')
            ->with('allLevelRange',$allLevelRange)
            ->with('allWinners',$allWinners)
            ->with('game',$game)
            ->with('item',$league);

    }


    public function postWinner($id){
        $paramArr = Input::all();
        $record = LeagueWinner::where('league_id','=', $id)
            ->where('level_range', '=',Input::get('level') )
            ->first();

        if($record)
            $record->content = $paramArr['content'];
        else
            $record = new LeagueWinner (array('content'=>$paramArr['content'],
                'league_id'=>$id,
                'level_range'=>$paramArr['level'],));

        $validator = Validator::make( $paramArr , $record->validationRules );
        $valid = $validator->passes();

        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString(  $validator->errors())));
        $record->save();

        //update point to user----------------------

        return Response::json(array('success'=>true,
            'msg'=> 'Lưu thành công'));

    }


}
