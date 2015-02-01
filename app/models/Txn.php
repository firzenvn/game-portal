<?php

class Txn extends \Eloquent {
	protected $fillable = [];

    protected $table = 'txns';

    public function game(){
        return $this->belongsTo('\EModel\Games');
    }

    public function user(){
        return $this->belongsTo('User');
    }

    public function game_server(){
        return $this->belongsTo('\EModel\GameServer','game_server_id');
    }

    public function getStatusmsgAttribute(){
        return Config::get('common.txn_status.'.$this->status);
    }

    public function getGameresponsemsgAttribute(){
        return Config::get('gamecode.'.$this->game_id.'.game_responses.'.$this->game_response);
    }
}