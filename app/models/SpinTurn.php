<?php

class SpinTurn extends \Eloquent {
    protected $table = 'spin_turns';
	protected $fillable = ['user_id', 'result', 'token', 'amount'];

    public function user(){
        return $this->belongsTo('User');
    }
}