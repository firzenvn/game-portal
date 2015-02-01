<?php

namespace Controllers\Admin;

use Config;
use EModel\Category;
use EModel\GameGallery;
use EModel\Games;
use EModel\GameVideo;
use Input;
use Response;
use Services\UploadService;
use Util\CommonConstant;
use Util\Exceptions\SystemException;
use View;

class GalleriesController extends AdminBaseController {

    function __construct( UploadService $uploadService)
    {
        parent::__construct();
        $this->uploadService = $uploadService;
    }

    public function getIndex()
    {

        $allGame = Games::where('active','=', 1)->get();
        if(count($allGame) == 0)
            throw new SystemException("Nhập game trước khi nhập gallery");
        $firstGame = $allGame[0];
        $items = GameGallery::where('game_id','=',$firstGame->id)->get();

        $subGalleriesCat = Category::where('group_code', '=', CommonConstant::CATEGORY_SUB_GALLERY)
            ->orderBy('lft')
            ->get();
        unset($subGalleriesCat[0]);

        $this->layout->content = View::make('admin.gallery_index')
            ->with( 'items' ,  $items)
            ->with( 'firstGame' ,  $allGame[0])
            ->with( 'allGame' ,  $allGame)
            ->with( 'subGalleriesCat' ,  $subGalleriesCat);


    }

    public function postAdd()
    {

        if(!Input::has('gameId'))
            return Response::json(array('success'=>false, 'msg'=>'Chưa chọn game'));

        if(!Input::has('catId'))
            return Response::json(array('success'=>false, 'msg'=>'Nhóm không hợp lệ'));


        $allImages = Input::get('images');
        $gameId = Input::get('gameId');
        $catId = Input::get('catId');
        $type = Config::get('danhmuc.upload-type.game-gallery-image');
        $tmpArr = array();
        foreach ($allImages as $aImage) {
            $gameGallery = new GameGallery(array('game_id'=>$gameId, 'category_id'=>$catId));
            $gameGallery->save();
            array_push($tmpArr, $gameGallery->id);
            // add image
            $uploadType = get_class( $gameGallery );

            $this->uploadService->save($gameGallery->id,$uploadType, $aImage, $type);
        }

        $allGameGalleries = GameGallery::join('uploads','game_galleries.id','=','uploads.uploadable_id' )
        ->where('uploads.type','=',$type)
        ->whereIn('game_galleries.id', $tmpArr)
        ->get(array('game_galleries.*', 'uploads.path as path'));

        return Response::json(array('success'=>true, 'data'=>$allGameGalleries->toArray()));
    }

    public function postLoad()
    {
        $catId = Input::get('catId');
        $gameId = Input::get('gameId');
        $type = Config::get('danhmuc.upload-type.game-gallery-image');
        $allGameGalleries = GameGallery::join('uploads','game_galleries.id','=','uploads.uploadable_id' )
            ->where('uploads.type','=',$type)
            ->where('game_galleries.game_id', '=', $gameId)
            ->where('game_galleries.category_id', '=', $catId)
            ->get(array('game_galleries.*', 'uploads.path as path'));
        return Response::json(array('success'=>true, 'data'=>$allGameGalleries->toArray()));
    }

    public function postLoadVideo()
    {
        $gameId = Input::get('gameId');
        $allGameVideo = GameVideo::where('game_id','=',$gameId)
            ->get();
        return Response::json(array('success'=>true, 'data'=>$allGameVideo->toArray()));
    }


    public function postDeleteGallery()
    {
        $galleryId = Input::get('galleryId');
        $record = GameGallery::find($galleryId);
        $uploadType = get_class( $record );

        $this->uploadService->deleteByIdType($galleryId, $uploadType);

        $record->delete();

        return Response::json(array('success'=>true));
    }

    public function postDeleteVideo()
    {
        $id = Input::get('videoId');
        $record = GameVideo::find($id);

        $record->delete();

        return Response::json(array('success'=>true));
    }


    public function postSaveVideo()
    {
        $videoId = Input::get('videoId');
        $code = Input::get('code');
        $gameId = Input::get('gameId');

        if(Input::has('videoId')){
            $gameVideo = GameVideo::find($videoId);
            $gameVideo->youtobe_code = $code;

        }else{
            $gameVideo = new GameVideo(array('youtobe_code'=>$code, 'game_id'=>$gameId));
        }
        $gameVideo->save();
        return Response::json(array('success'=>true));
    }



    public function postPopulate()
    {
        $type = Config::get('danhmuc.upload-type.game-gallery-image');
        $gameId = Input::get('gameId');
        $allImages = GameGallery::join('uploads', 'game_galleries.id','=','uploads.uploadable_id')
            ->where('uploads.type', '=', $type)
            ->where('game_id','=',$gameId)
        ->get(array('game_galleries.*', 'uploads.path'));
        return Response::json($allImages->toArray());

    }



}
