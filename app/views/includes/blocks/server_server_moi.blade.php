<?php


    $gameId = App::make('myApp')->game->id;


$builder = \EModel\GameServer::where('game_id', '=', $gameId)
    ->where('active', '=', 1)->orderBy('order_number', 'desc')->take(3);
if(isset($minId))
    $builder->where('id', '>=', $minId);
$allServer = $builder->get()
?>

<ul class="server-moi list-unstyled list-inline">
    @foreach($allServer as $aServer)
    <li><a href="/play/{{$aServer->id}}">S{{$aServer->order_number}}: {{$aServer->name}}</a></li>
    @endforeach
</ul>