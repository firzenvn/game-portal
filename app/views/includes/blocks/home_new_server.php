<?php
    $gameId = App::make('myApp')->game->id;
    $builder = \EModel\GameServer::where('game_id', '=', $gameId)
    ->where('active', '=', 1)->orderBy('order_number', 'desc')->take(3);
if(isset($minId))
        $builder->where('id', '>=', $minId);
    $allServer =  $builder->get();
?>
<section class="new-server">
    <section class="new-server-nav">Server mới</section>
    <ul class="new-server-list list-unstyled">
        <?php
        foreach ($allServer as $aServer) {
            echo('<li><span>S'.$aServer->order_number.':</span><a href="/play/'.$aServer->id.'">'.$aServer->name.'</a></li>');
        }

        ?>
    </ul>
    <section class="readmore"><a href="/server">Tất cả...</a></section>
</section>