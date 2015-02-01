<?php

use EModel\Category;
use EModel\GameGallery;
use EModel\GameVideo;
use Util\CommonConstant;

//lay danh sach video
$tmpArr = GameVideo::where('game_id','=',$game->id)
    ->get();
$allVideos = array();
foreach ($tmpArr as $aVideo) {
    array_push($allVideos, $aVideo->youtobe_code);
}

$subGalleriesCat = Category::where('group_code', '=', CommonConstant::CATEGORY_SUB_GALLERY)
    ->orderBy('lft')
    ->get();
unset($subGalleriesCat[0]);




?>
<section class="galleries">
    <section class="gal-nav">
    <h2>Thư viện</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; Thư viện
    </section>
    <section class="gal-content">
        <div class="row">
            <?php
            $upLoadType = Config::get('danhmuc.upload-type.game-gallery-image');
            foreach ($subGalleriesCat as $aCategory) {
                $allGameGalleries = GameGallery::join('uploads','game_galleries.id','=','uploads.uploadable_id' )
                    ->where('uploads.type','=',$upLoadType)
                    ->where('game_galleries.game_id', '=', $game->id)
                    ->where('game_galleries.category_id', '=', $aCategory->id)
                    ->orderBy('game_galleries.id')
                    ->limit(9)
                    ->get(array('game_galleries.*', 'uploads.path as path'));


                echo '<section class="gal-hinhanh">
                <section class="gal-hinhanh-nav">
                    <h3>'.$aCategory->name.'</h3>
                    <a href="/thu-vien/'.$aCategory->alias.'#top">Xem tất cả</a>
                </section>
                <ul class="gal-hinhanh-content">';

                    foreach($allGameGalleries as $aGameGallery){

                     echo   '<li><a href="'.$aGameGallery->path.'" rel="gal-hinhanh"><img src="'.$aGameGallery->path.'" alt=""/></a></li>';
                    }

                echo '</ul></section>';

            }


            ?>


            <section class="gal-video">
                <section class="gal-video-nav">
                    <h3>Video</h3>
                    <a href="/thu-vien/video#top">Xem tất cả</a>
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
