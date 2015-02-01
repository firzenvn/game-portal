<?php

class LeagueUser extends \Eloquent {
	protected $fillable = array('username', 'level_range', 'league_id', 'point');

    protected $table = 'league_users';
    public  $validationRules = [
        'username'    => 'required|min:1',
    ];

}