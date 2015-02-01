<?php

namespace Controllers\Admin;



use EModel\GameCategory;
use EModel\Games;

use EModel\GameServer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\GameService;
use Services\CatalogService;
use Services\UploadService;
use Util\CommonHelper;
use Util\Exceptions\EntityNotFoundException;
use Util\Exceptions\SystemException;

class GamesController extends AdminBaseController {

    function __construct(GameService $gamesService,
                         CatalogService $catalogService, UploadService $uploadService)
    {
        parent::__construct();
        $this->gamesService = $gamesService;
        $this->catalogService = $catalogService;
        $this->uploadService = $uploadService;

        /*$this->beforeFilter('permission', array('except'=>array(
            'getIndex'
        )));*/
    }

    public function getIndex()
    {
        $items = Games::orderBy('created_at')->get();
        $this->layout->content = View::make('admin.game_index')
            ->with( 'items' ,  $items);
    }

    public function getNew()
    {
        $gameCode = Config::get('danhmuc.category-group-code.game');
        $subGameCode = Config::get('danhmuc.category-group-code.sub-game');

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($gameCode);
        $allPrimaryCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($subGameCode);
        $allSubCategory = CommonHelper::getCategoryForCombo($allCategory);

        $this->layout->content = View::make('admin.game_new')
            ->with('allPrimaryCategory', $allPrimaryCategory)
            ->with('allSubCategory', $allSubCategory);

    }

    public function postNew(){
        $paramArr = Input::all();
        $newRecord = new Games( array('name'=>$paramArr['name'], 'description'=>$paramArr['description'],
            'active'=>$paramArr['active'], 'subdomain'=>$paramArr['subdomain'], 'tpl'=>$paramArr['tpl'],
            'exchange_rate'=>$paramArr['exchange_rate'], 'unit'=>$paramArr['unit']));

        $valid = $newRecord->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $newRecord->getErrors())));


        $newRecord->save();
// add image
        if(isset($paramArr['imageFile'])){
            $uploadType = get_class( $newRecord );
            $type = Config::get('danhmuc.upload-type.game-topic-image');
            $this->uploadService->save($newRecord->id,$uploadType, $paramArr['imageFile'], $type);
        }

        if(isset($paramArr['imageThumbFile'])){
            $uploadType = get_class( $newRecord );
            $type = Config::get('danhmuc.upload-type.game-thumb-image');
            $this->uploadService->save($newRecord->id,$uploadType, $paramArr['imageThumbFile'], $type);
        }

// add category
        if($paramArr['primaryCategory']){
            $anGameCategory = new GameCategory(array('game_id'=>$newRecord->id,
                'category_id'=>$paramArr['primaryCategory']));
            $anGameCategory->save();
        }
        if($paramArr['subCategories']){
            $subCategories = $paramArr['subCategories'];
            foreach ($subCategories as $aCat) {
                $anGameCategory = new GameCategory(array('game_id'=>$newRecord->id, 'category_id'=>$aCat));
                $anGameCategory->save();
            }
        }

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Thêm mới game thành công!')));
    }

    public function getDelete( $id ){

        $this->gamesService->delete($id);

        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/games' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit( $id ){

        try{
            $item = $this->gamesService->requireById($id);
            $type = Config::get('danhmuc.upload-type.game-topic-image');
            $allImages =  $item->getUploadByType($type);
            $uploadToppic = null;
            if($allImages && count($allImages) > 0){
                $uploadToppic = $allImages[0];
            }

            $type = Config::get('danhmuc.upload-type.game-thumb-image');
            $allImages =  $item->getUploadByType($type);
            $uploadThumb = null;
            if($allImages && count($allImages) > 0){
                $uploadThumb = $allImages[0];
            }


        } catch( EntityNotFoundException $e ){
            return Redirect::to( '/admin/games')->
                with('errors', new MessageBag( array("Không tìm thấy bài viết ID:".$id .".") ) );
        }

        $gameCode = Config::get('danhmuc.category-group-code.game');
        $subGameCode = Config::get('danhmuc.category-group-code.sub-game');

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($gameCode);
        $allPrimaryCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($subGameCode);
        $allSubCategory = CommonHelper::getCategoryForCombo($allCategory);

        $primaryCategory = $item->getPrimaryCategory();
        $subCategories = $item->getSubCategories();


        $this->layout->content = View::make('admin.game_edit')
            ->with( 'item' , $item )
            ->with('upload', $uploadToppic)
            ->with('uploadThumb', $uploadThumb)
            ->with('allPrimaryCategory', $allPrimaryCategory)
            ->with('allSubCategory', $allSubCategory)
            ->with('primaryCategory', $primaryCategory)
            ->with('subCategories', $subCategories);

    }

    public function postEdit($id){

        $record = $this->gamesService->requireById( $id );

        if(!$record)throw new SystemException("Game có ID: ".$id.' không tồn tại!');

        $paramArr = Input::all();

        $record ->fill( array('name'=>$paramArr['name'], 'description'=>$paramArr['description'],
            'active'=>$paramArr['active'], 'subdomain'=>$paramArr['subdomain'], 'tpl'=>$paramArr['tpl'],
        'exchange_rate'=>$paramArr['exchange_rate'], 'unit'=>$paramArr['unit']));

        $valid = $record->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $record->getErrors())));

        $record->save();

        // add image
        if(isset($paramArr['imageFile'])){
            $uploadType = get_class( $record );
            $type = Config::get('danhmuc.upload-type.game-topic-image');
            $this->uploadService->save($record->id,$uploadType, $paramArr['imageFile'], $type);
        }

        if(isset($paramArr['imageThumbFile'])){
            $uploadType = get_class( $record );
            $type = Config::get('danhmuc.upload-type.game-thumb-image');
            $this->uploadService->save($record->id,$uploadType, $paramArr['imageThumbFile'], $type);
        }

        GameCategory::where('game_id', '=', $record->id)
            ->delete();
// add category
        if($paramArr['primaryCategory']){
            $anGameCategory = new GameCategory(array('game_id'=>$record->id,
                'category_id'=>$paramArr['primaryCategory']));
            $anGameCategory->save();
        }
        if($paramArr['subCategories']){
            $subCategories = $paramArr['subCategories'];
            foreach ($subCategories as $aCat) {
                $anGameCategory = new GameCategory(array('game_id'=>$record->id, 'category_id'=>$aCat));
                $anGameCategory->save();
            }
        }

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Sửa game thành công!')));
    }

    public function postAddServer(){
        $paramArr = Input::all();
        $newRecord = new GameServer( array('name'=>$paramArr['name'],'order_number'=>$paramArr['order_number'], 'url'=>$paramArr['url'],
            'secret_key'=>$paramArr['key'], 'ip'=>$paramArr['ip'], 'active'=>$paramArr['active'],
         'game_id'=>$paramArr['game_id'], 'sid'=>$paramArr['sid'], 'apply_for'=>$paramArr['apply_for']));

        $valid = $newRecord->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $newRecord->getErrors())));

        $newRecord->save();
        return Response::json(array('success'=>true, 'data'=> View::make('admin.game_server_row')->with('item',$newRecord)->__toString()));

    }

    public function postEditServer(){
        $paramArr = Input::all();
        $record = GameServer::find($paramArr['id']);
        if(!$record)throw new SystemException("Server có ID: ".$paramArr['id'].' không tồn tại!');

        $paramArr = Input::all();

        $record ->fill( array('name'=>$paramArr['name'],'order_number'=>$paramArr['order_number'], 'url'=>$paramArr['url'],
            'secret_key'=>$paramArr['key'], 'ip'=>$paramArr['ip'], 'active'=>$paramArr['active'],'sid'=>$paramArr['sid'],
            'game_id'=>$paramArr['game_id'],'apply_for'=>$paramArr['apply_for']));

        $valid = $record->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $record->getErrors())));

        $record->save();

        return Response::json(array('success'=>true,
            'data'=> $record->toArray()));

    }

    public function postDeleteServer(  ){
        $aServer = GameServer::find(Input::get('id'));
        $aServer->delete();
        return Response::json(array('success'=>true));
    }

}
