<?php

Event::listen('game.play', function($gameServer)
{
	$myApp=App::make('myApp');
	PlayHistory::firstOrCreate(array('game_id'=>$myApp->game->id,'server_id'=>$gameServer->id,'user_id'=>Auth::user()->id, 'is_first'=>1));
	$model = new PlayHistory(array('game_id'=>$myApp->game->id,'server_id'=>$gameServer->id,'user_id'=>Auth::user()->id, 'is_first'=>0));
	$model->save();
});

