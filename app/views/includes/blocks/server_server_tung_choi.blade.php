<?php
$serverList = array();
$game = App::make('myApp')->game;
if(Auth::user()){
    $serverList = \Util\GameHelper::getPlayedServer();
}
?>
@if(Auth::user())
<ul class="server-list list-unstyled list-inline">
    <?php
    foreach ($serverList as $aServer) {
        echo('<li><a href="/play/'.$aServer->id.'">S'.$aServer->order_number.': '.$aServer->name.'</a></li>');
    }

    ?>
</ul>
@else
<section class="yeucaudangnhap">
    <a class="dangnhap-button"  href="javascript:;" data-toggle="modal" data-target="#popup-dangnhap">Đăng nhập</a>
    <p>Hãy đăng nhập để xem</p>
</section>
@endif
