<?php

$allServer = \EModel\GameServer::where('active','=',1)->orderBy('created_at', 'desc')->paginate(3);

?>
<section class="server">
    <nav class="sidebar-nav"><h3 class="text-center nomargin">Lịch mở server</h3></nav>
    <section class="server-list">
        <table>
            <thead>
            <tr>
                <th><p>Game</p></th>
                <th><p class="text-center">Ngày</p></th>
                <th><p class="text-center">Giờ</p></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($allServer as $aServer) {
                echo '<tr>
                <td><a href="#">'.$aServer->name.'</a></td>
                <td><p>20/06</p></td>
                <td><p>08:00</p></td>
            </tr>';
            }

            ?>

            </tbody>
        </table>

    </section>
    <section class="readmore text-center"><a href="#">Xem tất cả...</a></section>

</section><!-- End .server -->