<?php

class ZingTxn extends \Eloquent {
	protected $fillable = ['user_id', 'game_id', 'game_server_id', 'pay_amount','game_amount', 'description', 'ref_txn_id'];

    protected $table = 'zing_txns';

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