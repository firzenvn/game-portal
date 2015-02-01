<?php

class SohaTxn extends \Eloquent {
	protected $fillable = [];

    protected $table = 'soha_txns';

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

    public function getGameResponsemsgAttribute(){
        return Config::get('gamecode.'.$this->game_id.'.'.$this->game_response);
    }
}