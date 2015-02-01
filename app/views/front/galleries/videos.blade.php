<section class="galleries">
    <section class="gal-nav">
    <h2>Thư viện</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; <a href="/thu-vien">Thư viện</a> &raquo; Video
    </section>
    <section class="gal-content">
        <div class="row">
            <section class="gal-video">
                <section class="gal-video-nav">
                    <h3>Video</h3>
                </section>
                <ul class="gal-video-content">
                    <?php
                    $count=1;
                    foreach($allVideos as $aVideo){
                        if($count>3) break;
                        ?>
                        <li>
                            <a class="gal-video-item" href="https://www.youtube.com/watch?v={{$aVideo}}?fs=1&autoplay=1">
                                <img src="http://img.youtube.com/vi/{{$aVideo}}/0.jpg" alt="video">
                                <span>Play</span>
                            </a>
                        </li>
                        <?php
                        $count++;
                    }
                    ?>
                </ul>
            </section>
        </div>
    </section>
</section>
<script type="text/javascript">
    $(function(){
        $(".gal-hinhanh-content li a").fancybox();
        $(".gal-wallpaper-content li a").fancybox();
        $("a.gal-video-item").click(function() {
            $.fancybox({
                'padding'		: 0,
                'autoScale'		: false,
                'transitionIn'	: 'none',
                'transitionOut'	: 'none',
                'title'			: this.title,
                'width'			: 870,
                'height'		: 523,
                'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
                'type'			: 'swf',
                'swf'			: {
                    'wmode'				: 'transparent',
                    'allowfullscreen'	: 'true'
                }
            });

            return false;
        });
    })
</script>
