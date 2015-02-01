<?php

namespace Controllers\Admin;



use Config;
use DB;

use EModel\Games;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Txn;

class ReportsController extends AdminBaseController {

    function __construct()
    {
        parent::__construct();
    }

    public function getSumByGame()
    {

//        if(!Input::has('gameId')){
//            $game = Games::orderBy('id')->first();
//        }else{
//            $game = Games::findOrFail(Input::get('gameId'));
//        }

//        $success_code =  Config::get('gamecode.'.$game->id.'.success_code');
//        $success_code = $success_code?$success_code:0;
        $txnBuilder = Txn::join('games', 'txns.game_id', '=', 'games.id')
//            where('game_id', '=', $game->id)
//            ->where('game_response', '=' ,$success_code)
            ->where('status', '=', 200)
            ->groupBy('game_id');
       /* $cardBuilder = CardTxn::join('games', 'card_txns.game_id', '=', 'games.id')->
            where('status', '=', 200)->groupBy('game_id');*/

        $soHaBuilder = \SohaTxn::join('games', 'soha_txns.game_id', '=', 'games.id')->
//            where('game_id', '=', $game->id)
//            ->where('game_response', '=' , $success_code)
            where('status', '=', 200)
                ->groupBy('game_id');

        $zingBuilder = \ZingTxn::join('games', 'zing_txns.game_id', '=', 'games.id')->
//            where('game_id', '=', $game->id)
//            ->where('game_response', '=' , $success_code)
            where('status', '=', 0)
            ->groupBy('game_id');

        if(Input::has('start_date'))
        {
            $txnBuilder->where('txns.created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
//            $cardBuilder->where('card_txns.created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
            $soHaBuilder->where('soha_txns.created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
            $zingBuilder->where('zing_txns.created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
            $startDate = Input::get('start_date');
        }
        else
            $startDate = '--';
        if(Input::has('end_date'))
        {
            $txnBuilder->where('txns.created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
//            $cardBuilder->where('card_txns.created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
            $soHaBuilder->where('soha_txns.created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
            $zingBuilder->where('zing_txns.created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
            $endDate = Input::get('end_date');
        }else
            $endDate = '--';

        $allTxns = $txnBuilder->select('games.name as game_name','game_id',
            DB::raw('sum(pay_amount) as pay_amount'), DB::raw('sum(game_amount) as game_amount'),
            DB::raw("'".$startDate."'".' as start_date'), DB::raw("'".$endDate."'".' as end_date'))->get();

        /*$allCardTxns = $cardBuilder->select('games.name as game_name','game_id',
            DB::raw('sum(pay_amount) as pay_amount'), DB::raw('sum(game_amount) as game_amount'),
            DB::raw("'".$startDate."'".' as start_date'), DB::raw("'".$endDate."'".' as end_date'))->get();*/

        $allSohaTxns = $soHaBuilder->select('games.name as game_name','game_id',
            DB::raw('sum(pay_amount) as pay_amount'), DB::raw('sum(game_amount) as game_amount'),
            DB::raw("'".$startDate."'".' as start_date'), DB::raw("'".$endDate."'".' as end_date'))->get();

        $allZingTxns = $zingBuilder->select('games.name as game_name','game_id',
            DB::raw('sum(pay_amount) as pay_amount'), DB::raw('sum(game_amount) as game_amount'),
            DB::raw("'".$startDate."'".' as start_date'), DB::raw("'".$endDate."'".' as end_date'))->get();

        $allGames = Games::orderBy('created_at', 'desc')->get();
        $this->layout->content = View::make('admin.report_sum_by_game')
        ->with('allGames',$allGames)
        ->with('allTxns',$allTxns)
//        ->with('game',$game)
        ->with('allSohaTxns',$allSohaTxns)
        ->with('allZingTxns',$allZingTxns);


    }




}
