<?php

class LeagueWinner extends \Eloquent {
	protected $fillable = array('level_range', 'league_id', 'content');

    protected $table = 'league_winners';
    public  $validationRules = [
//        'content'    => 'required',
//        'level_range'    => 'required',
//        'league_id'    => 'required',
    ];

}