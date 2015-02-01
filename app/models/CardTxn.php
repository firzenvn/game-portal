<?php

class CardTxn extends \Eloquent {
    protected $table = 'card_txns';
	protected $fillable = [];

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

    public function getCardTypeNameAttribute(){
        return Config::get('common.card_types.'.$this->card_type);
    }

    public function getGameResponsemsgAttribute(){
        return Config::get('common.game_responses.'.$this->game_response);
    }
}