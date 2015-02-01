

<div class="row">
    <section class="thuvien-nav">
        <h3><a href="<?php echo isset($linkNav) ? $linkNav : '#' ?>">Thư viện</a></h3>
    </section>
    <section class="thuvien-content">
        <ul class="list-unstyled list-inline">
            <li class="col-xs-4"><a rel="gallery" class="group-gallery" href="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien1.jpg"><img src="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien1.jpg" alt="thu vien 1"></a></li>
            <li class="col-xs-4"><a rel="gallery" class="group-gallery" href="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien2.jpg"><img src="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien2.jpg" alt="thu vien 2"></a></li>
            <li class="col-xs-4"><a rel="gallery" class="group-gallery" href="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien3.jpg"><img src="/media/game-tpl/{{$myApp->game->subdomain}}/assets/img/thuvien3.jpg" alt="thu vien 3"></a></li>
        </ul>
    </section>
</div>
<script>
    $(function(){
        $("a.group-gallery").fancybox();
    });
</script>