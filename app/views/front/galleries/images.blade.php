<section class="galleries">
    <section class="gal-nav">
    <h2>Thư viện</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; <a href="/thu-vien">Thư viện</a> &raquo; {{$category->name}}
    </section>
    <section class="gal-content">
        <div class="row">
            <section class="gal-hinhanh">
                <section class="gal-hinhanh-nav">
                    <h3>{{$category->name}}</h3>
                </section>
                <ul class="gal-hinhanh-content">
                    <?php
                    foreach($allImages as $aImage){
                        ?>
                        <li><a href="{{$aImage}}" rel="gal-hinhanh"><img src="{{$aImage}}" alt=""/></a></li>
                        <?php
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
