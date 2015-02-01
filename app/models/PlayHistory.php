<?php

class PlayHistory extends \Eloquent {
    protected $table = 'play_history';
	protected $fillable = ['game_id', 'server_id', 'user_id', 'is_first'];
}