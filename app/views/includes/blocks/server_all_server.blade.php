<?php

$gameId = App::make('myApp')->game->id;

$builder = \EModel\GameServer::where('game_id', '=', $gameId)
    ->where('active', '=', 1)->orderBy('order_number');
if(isset($minId))
    $builder->where('id', '>=', $minId);
$allServer = $builder->get();
?>

<section class="allserver">
    <h3>Tất cả server</h3>
    <ul class="server-list list-unstyled list-inline">
        @foreach($allServer as $aServer)
        <li><a href="/play/{{$aServer->id}}">S{{$aServer->order_number}}: {{$aServer->name}}</a></li>
        @endforeach
    </ul>
</section>