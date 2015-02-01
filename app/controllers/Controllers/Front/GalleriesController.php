<?php


use EModel\Category;
use EModel\GameGallery;
use EModel\GameVideo;
use Illuminate\Support\Facades\View;

class GalleriesController extends BaseController {

    public function getIndex($type=''){
        $this->Loadlayout('news');

        $myApp=App::make('myApp');
        $game=$myApp->game;

       if($type == 'video'){
            $allVideo = GameVideo::where('game_id','=',$game->id)
               ->get();
           $tmpArr = array();
           foreach ($allVideo as $aVideo) {
               array_push($tmpArr, $aVideo->youtobe_code);
           }

           $this->layout->content = View::make('front.galleries.videos',array(
               'allVideos'=>$tmpArr,'game'=>$game
           ));
           $this->layout->title = 'Thư viện Video | ';

       }else{
           $category = Category::where('alias','=',$type)->first();

           $upLoadType = Config::get('danhmuc.upload-type.game-gallery-image');
           if($category){
               $allGameGalleries = GameGallery::join('uploads','game_galleries.id','=','uploads.uploadable_id' )
                   ->where('uploads.type','=',$upLoadType)
                   ->where('game_galleries.game_id', '=', $game->id)
                   ->where('game_galleries.category_id', '=', $category->id)
                    ->orderBy('game_galleries.id')
                   ->get(array('game_galleries.*', 'uploads.path as path'));
               $allImages = array();
               foreach ($allGameGalleries as $aGameGallery) {
                   array_push($allImages,$aGameGallery->path);
               }

               $this->layout->content = View::make('front.galleries.images',array(
                   'allImages'=>$allImages,'category'=>$category,'game'=>$game
               ));

               $this->layout->title = 'Thư viện '.$category->name.' | ';
           }
           else{
               $this->layout->content = View::make('front.galleries.index',array(
               'game'=>$game
                   /*'allImages'=>$allImages,
                   'allWallpapers'=>$allWallpapers,
                   'allVideos'=>$allVideos*/
               ));

               $this->layout->title = 'Thư viện | ';
           }
       }

       /* //TODO: Lấy dữ liệu từ DB
        $allImages = array(
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img1.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img2.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img3.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img4.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img5.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img6.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img7.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img8.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img9.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/img10.jpg',


        );
        $allWallpapers = array(
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal1.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal2.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal3.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal4.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal5.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal6.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal7.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal8.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal9.jpg',
            'http://bado.maxgate.vn/media/game-tpl/bado/gallery/gal10.jpg',
        );

        $allVideos = array(
            'WubDHEDRBk0'
        );
        if($type=='hinh-anh'){
            $this->layout->content = View::make('front.galleries.images',array(
                'allImages'=>$allImages
            ));

            $this->layout->title = 'Thư viện Hình ảnh | ';
        }elseif($type=='wallpaper'){
            $this->layout->content = View::make('front.galleries.wallpapers',array(
                'allWallpapers'=>$allWallpapers
            ));
            $this->layout->title = 'Thư viện Wallpaper | ';
        }elseif($type=='video'){
            $this->layout->content = View::make('front.galleries.videos',array(
                'allVideos'=>$allVideos
            ));
            $this->layout->title = 'Thư viện Video | ';
        }else{
            $this->layout->content = View::make('front.galleries.index',array(
                'allImages'=>$allImages,
                'allWallpapers'=>$allWallpapers,
                'allVideos'=>$allVideos
            ));

            $this->layout->title = 'Thư viện | ';
        }*/

    }


}