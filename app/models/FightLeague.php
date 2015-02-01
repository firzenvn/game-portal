<?php

class FightLeague extends \Eloquent {
	protected $fillable = array('name', 'description', 'start_date','end_date','game_id','active');

    protected $table = 'fight_leagues';

    public  $validationRules = [
        'name'    => 'required|min:5',
    ];


}