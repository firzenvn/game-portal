<?php

namespace Controllers\Admin;



use EModel\ArticleCategory;
use EModel\Articles;

use EModel\Category;
use EModel\GameArticle;
use EModel\Games;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\ArticleService;
use Services\CatalogService;
use Services\TagService;
use Services\UploadService;
use Util\CommonConstant;
use Util\CommonHelper;
use Util\Exceptions\EntityNotFoundException;
use Util\Exceptions\SystemException;

class ArticlesController extends AdminBaseController {

    function __construct(ArticleService $articlesService,TagService $tagService,
                         CatalogService $catalogService, UploadService $uploadService)
    {
        parent::__construct();
        $this->articlesService = $articlesService;
        $this->catalogService = $catalogService;
        $this->uploadService = $uploadService;
        $this->tagService = $tagService;

        /*$this->beforeFilter('permission', array('except'=>array(
            'getIndex'
        )));*/
    }

    public function getIndex()
    {
        $items = Articles::leftJoin('article_category', 'articles.id', '=', 'article_category.article_id')
            ->leftJoin('game_articles', 'articles.id', '=', 'game_articles.article_id')
            ->leftJoin('games', 'games.id', '=' , 'game_articles.game_id')
            ->groupBy('articles.id')->orderBy('articles.created_at','desc');
        if(Input::has('id'))
        {
            $items->where('articles.id',Input::get('id'));
        }
        if(Input::has('title'))
        {
            $items->where('title','LIKE','%'.Input::get('title').'%');
        }
        if(Input::has('articles.start_date'))
        {
            $items->where('created_at','>=',date("Y-m-d H:i:s", strtotime(Input::get('start_date'))));
        }
        if(Input::has('articles.end_date'))
        {
            $items->where('created_at','<=',date("Y-m-d 23:59:59", strtotime(Input::get('end_date'))));
        }
        if(Input::has('active'))
        {
            $items->where('active',Input::get('active'));
        }
        if(Input::has('priCat'))
        {
            $priCategory = Category::find(Input::get('priCat'));
            $items->join( 'category as pc','pc.id','=', 'article_category.category_id' )
                ->where('pc.lft','>=', $priCategory->lft)
                ->where('pc.rgt','<=', $priCategory->rgt);

        }
        if(Input::has('subCat'))
        {
            $subCatId = Input::get('subCat');
            $subCategory = Category::find($subCatId);
            $items->join('category as sc', 'sc.id','=', 'article_category.category_id' )
                ->where('sc.lft','>=', $subCategory->lft)
                ->where('sc.rgt','<=', $subCategory->rgt);
        }
        if(Input::has('game'))
        {
            $items->where('games.id', Input::get('game'));
        }
        $items = $items->select('articles.id as id','title','articles.created_at as created_at','articles.active', 'games.name as game')->paginate(10);

        $articleCode = Config::get('danhmuc.category-group-code.article');
        $subArticleCode = Config::get('danhmuc.category-group-code.sub-article');
        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($articleCode);
        $allPrimaryCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($subArticleCode);
        $allSubCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allGames = Games::lists('name','id');

        $this->layout->content = View::make('admin.article_index')
            ->with( 'items' ,  $items)
            ->with('allPrimaryCategory', $allPrimaryCategory)
            ->with('allSubCategory', $allSubCategory)
            ->with('allGames', $allGames);
    }

    public function getNew()
    {
        $articleCode = CommonConstant::CATEGORY_ARTICLE;
        $subArticleCode = CommonConstant::CATEGORY_SUB_ARTICLE;
        $posArticleCode = CommonConstant::CATEGORY_POSITION_ARTICLE;

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($articleCode);
        $allPrimaryCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($subArticleCode);
        $allSubCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($posArticleCode);
        $allPosCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allGame = Games::lists('name','id');

        $this->layout->content = View::make('admin.article_new')
            ->with('allPrimaryCategory', $allPrimaryCategory)
            ->with('allSubCategory', $allSubCategory)
            ->with('allPosCategory', $allPosCategory)
            ->with('allGame',$allGame);

    }

    public function postNew(){
        $paramArr = Input::all();
        $newRecord = new Articles( array('title'=>$paramArr['title'], 'description'=>$paramArr['description'],'keyword'=>$paramArr['keyword'],'content'=>$paramArr['content'],
            'active'=>$paramArr['active']));

        $valid = $newRecord->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $newRecord->getErrors())));

        $newRecord->save();
// add image
        if(Input::has('imageFile') && $paramArr['imageFile']){
            $uploadType = get_class( $newRecord );
            $type = Config::get('danhmuc.upload-type.article-topic-image');
            $this->uploadService->save($newRecord->id,$uploadType, $paramArr['imageFile'], $type);
        }

        if(Input::has('imageThumbFile') && $paramArr['imageThumbFile']){
            $uploadType = get_class( $newRecord );
            $type = Config::get('danhmuc.upload-type.article-thumb-image');
            $this->uploadService->save($newRecord->id,$uploadType, $paramArr['imageThumbFile'], $type);
        }
// add tag

        if(Input::has('tags') && $paramArr['tags']){
            $tagArr = explode(',', $paramArr['tags']);
            $tagType = get_class( $newRecord );
            foreach ($tagArr as $aTag) {
                $this->tagService->save($newRecord->id,$tagType, $aTag);
            }
        }

// add category
        if($paramArr['primaryCategory'] && $paramArr['primaryCategory'] != CommonConstant::SELECT_ALL_VALUE){
            $anArticleCategory = new ArticleCategory(array('article_id'=>$newRecord->id,
                'category_id'=>$paramArr['primaryCategory']));
            $anArticleCategory->save();
        }

        if($paramArr['posCategories'] && $paramArr['posCategories'] != CommonConstant::SELECT_ALL_VALUE){
            $posCategories = $paramArr['posCategories'];
            $count = 0;
            foreach ($posCategories as $aCat) {
                $anArticleCategory = new ArticleCategory(array('article_id'=>$newRecord->id, 'category_id'=>$aCat));
                $anArticleCategory->save();
                $count++;
            }
        }

        if($paramArr['subCategories']){
            $subCategories = $paramArr['subCategories'];
            $count = 0;
            foreach ($subCategories as $aCat) {
                if($count == 0)
                    $isGameMain = 1;
                else
                    $isGameMain = 0;
                $anArticleCategory = new ArticleCategory(array('article_id'=>$newRecord->id, 'category_id'=>$aCat, 'is_game_main'=>$isGameMain));
                $anArticleCategory->save();
                $count++;
            }
        }

//add game
        if($paramArr['games'])
        {
            $games = $paramArr['games'];
            foreach ($games as $aGame)
            {
                $aGameArticle = new GameArticle(array(
                    'article_id'=>$newRecord->id,
                    'game_id'=>$aGame
                ));
                $aGameArticle->save();
            }
        }

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Thêm mới bài viết thành công!')));
    }

    public function getDelete( $id ){

        $this->articlesService->delete($id);

        $message = 'Xóa thành công.';
        return Redirect::to( '/admin/articles' )
            ->with('success', new MessageBag( array( $message ) ) );

    }

    public function getEdit( $id ){

        try{
            $item = $this->articlesService->requireById($id);
            $type = Config::get('danhmuc.upload-type.article-topic-image');
            $allImages =  $item->getUploadByType($type);
            $upload = null;
            if($allImages && count($allImages) > 0){
                $upload = $allImages[0];
            }

            $type = Config::get('danhmuc.upload-type.article-thumb-image');
            $allImages =  $item->getUploadByType($type);
            $thumbUpload = null;
            if($allImages && count($allImages) > 0){
                $thumbUpload = $allImages[0];
            }

            $tagsCsv = $item->getTagsCsvAttribute();


        } catch( EntityNotFoundException $e ){
            return Redirect::to( '/admin/articles')->
                with('errors', new MessageBag( array("Không tìm thấy bài viết ID:".$id .".") ) );
        }

        $articleCode = CommonConstant::CATEGORY_ARTICLE;
        $subArticleCode = CommonConstant::CATEGORY_SUB_ARTICLE;
        $posArticleCode = CommonConstant::CATEGORY_POSITION_ARTICLE;

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($articleCode);
        $allPrimaryCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($subArticleCode);
        $allSubCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allCategory = $this->catalogService->getAllCategoriesByGroupCode($posArticleCode);
        $allPosCategory = CommonHelper::getCategoryForCombo($allCategory);

        $allGame = Games::lists('name','id');

        $primaryCategory = $item->getPrimaryCategory();
        $subCategories = $item->getSubCategories();
        $posCategories = $item->getPosCategories();
        $gameArticles = $item->games()->lists('game_id');

        $this->layout->content = View::make('admin.article_edit')
            ->with( 'item' , $item )->with('upload', $upload)
            ->with('thumbUpload', $thumbUpload)
            ->with('tagsCsv', $tagsCsv)
            ->with('allPrimaryCategory', $allPrimaryCategory)
            ->with('allSubCategory', $allSubCategory)
            ->with('allPosCategory', $allPosCategory)
            ->with('primaryCategory', $primaryCategory)
            ->with('subCategories', $subCategories)
            ->with('posCategories', $posCategories)
            ->with('allGame',$allGame)
            ->with('gameArticles', $gameArticles);

    }

    public function postEdit($id){

        $record = $this->articlesService->requireById( $id );

        if(!$record)throw new SystemException("Bài viết có ID: ".$id.' không tồn tại!');

        $paramArr = Input::all();

        $record ->fill( array('title'=>$paramArr['title'], 'description'=>$paramArr['description'],'keyword'=>$paramArr['keyword'], 'content'=>$paramArr['content'],
            'active'=>$paramArr['active']));

        $valid = $record->isValid();
        if( !$valid )
            return Response::json(array('success'=>false, 'msg'=>$this->errorToString( $record->getErrors())));

        $record->save();

        //xoa image cu
        $uploadType = get_class( $record );
        $this->uploadService->deleteByIdType($id, $uploadType);
        // add image
        if(Input::has('imageFile') && $paramArr['imageFile']){
            $type = Config::get('danhmuc.upload-type.article-topic-image');
            $this->uploadService->save($record->id,$uploadType, $paramArr['imageFile'], $type);
        }

        if(Input::has('imageThumbFile') && $paramArr['imageThumbFile']){
            $type = Config::get('danhmuc.upload-type.article-thumb-image');
            $this->uploadService->save($record->id,$uploadType, $paramArr['imageThumbFile'], $type);
        }

// add tag
        $tagType = get_class( $record );
        $this->tagService->deleteByIdType($record->id, $tagType);
        if(Input::has('tags') && $paramArr['tags']){
            $tagArr = explode(',', $paramArr['tags']);
            foreach ($tagArr as $aTag) {
                $this->tagService->save($record->id,$tagType, $aTag);
            }
        }

        ArticleCategory::where('article_id', '=', $record->id)
            ->delete();
        GameArticle::where('article_id', $record->id)->delete();
// add category
        if($paramArr['primaryCategory'] && $paramArr['primaryCategory'] != CommonConstant::SELECT_ALL_VALUE){
            $anArticleCategory = new ArticleCategory(array('article_id'=>$record->id,
                'category_id'=>$paramArr['primaryCategory']));
            $anArticleCategory->save();
        }
        if($paramArr['subCategories']){
            $subCategories = $paramArr['subCategories'];
            $count = 0;
            foreach ($subCategories as $aCat) {
                if($count == 0)
                    $isGameMain = 1;
                else
                    $isGameMain = 0;

                $anArticleCategory = new ArticleCategory(array('article_id'=>$record->id,
                    'category_id'=>$aCat,  'is_game_main'=>$isGameMain));
                $anArticleCategory->save();
            }
        }


        if($paramArr['posCategories']){
            $posCategories = $paramArr['posCategories'];
            foreach ($posCategories as $aCat) {
                $anArticleCategory = new ArticleCategory(array('article_id'=>$record->id,
                    'category_id'=>$aCat, ));
                $anArticleCategory->save();
            }
        }

//add game
        if($paramArr['games'])
        {
            $games = $paramArr['games'];
            foreach ($games as $aGame)
            {
                $aGameArticle = new GameArticle(array(
                    'article_id'=>$record->id,
                    'game_id'=>$aGame
                ));
                $aGameArticle->save();
            }
        }

        return Response::json(array('success'=>true, 'msg'=> $this->successToString('Sửa bài viết thành công!')));
    }

}
