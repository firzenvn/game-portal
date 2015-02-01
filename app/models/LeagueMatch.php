<?php

class LeagueMatch extends \Eloquent {
	protected $fillable = array('league_id', 'range_level',
        'first_user_id','second_user_id','result','video_key', 'round');

    protected $table = 'league_matchs';

    public  $validationRules = [
    ];

    public function firstUser(){
        return $this->belongsTo('LeagueUser', 'first_user_id');
    }

    public function secondUser(){
        return $this->belongsTo('LeagueUser', 'second_user_id');
    }

    public function arrangeUserPoint(){
        $firstUser = $this->firstUser;
        $secondUser = $this->secondUser;
        $total = $firstUser->point + $secondUser->point;
        if($this->result == 1){
            $firstUser->point = $total;
            $secondUser->point = 0;
        }elseif($this->result == 2){
            $firstUser->point = 0;
            $secondUser->point = $total;
        }else{

        }
        $firstUser->save();
        $secondUser->save();
    }

    public function payBackUserPoint(){
        $firstUser = $this->firstUser;
        $secondUser = $this->secondUser;
        $total = $firstUser->point + $secondUser->point;
        $firstUser->point = $total/2;
        $secondUser->point = $total/2;
        $firstUser->save();
        $secondUser->save();
    }

    public function getLiteralResult(){
        if($this->result == 1)
            return 'U1 thắng';
        elseif($this->result == 2)
            return 'U2 thắng';
        else
            return 'Chưa xác định';
    }

    public function strictDelete(&$idArr = array()){

        $collection = $this->findAllHigherRound();
        foreach ($collection as $aHigherMatch) {
            $aHigherMatch->strictDelete($idArr);
        }
        $this->payBackUserPoint();
        array_push($idArr, $this->id);
        $this->delete();
        return $idArr;
    }

    public function findAllHigherRound(){
        return LeagueMatch::where('round', '=' , $this->round*2)
            ->where('league_id', '=' , $this->league_id)
            ->where('range_level', '=' , $this->range_level)
            ->get();
    }



}