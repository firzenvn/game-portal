<?php

use EModel\Articles;
use EModel\Category;
use EModel\Games;
use Util\Exceptions\BusinessException;

class NewsController extends BaseController {

    public function getDetail($catSlug , $slug)
    {
        $myApp=App::make('myApp');

        //------for game page----------
        if($myApp->isOnSubdomain){
            $this->loadLayout( 'news');
            $tmpArr = explode('-', $slug);
            $articleId = array_pop($tmpArr);
            $anArticle = Articles::find($articleId);
//            Log::debug($catSlug.'::'.$slug);
            if(!$anArticle )
                throw new BusinessException('Không tìm thấy bài viết.');

            $allRelated = $anArticle->findGameRelated($catSlug);

            $this->layout->content = View::make('game.news_detail')->with('gameId', $this->game->id)
                ->with('anArticle', $anArticle)
                ->with('allRelated', $allRelated);
            $this->layout->title = $anArticle->title.' | ';
            $this->layout->description = $anArticle->description;
            $this->layout->keyword = $anArticle->keyword;
        }else //------for portal page----------
        {

        }

    }

    public function news( $catSlug = '')
    {
        $myApp=App::make('myApp');

        //------for game page----------
        if($myApp->isOnSubdomain){
            $this->loadLayout( 'news');
            $category = Category::where('alias', '=', $catSlug)->first();
            if($category){
                $paginator = Articles::join('game_articles', 'articles.id', '=', 'game_articles.article_id')
                    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
                    ->join('category', 'category.id', '=', 'article_category.category_id')
                    ->where('category.lft', '>=', $category->lft)
                    ->where('category.rgt', '<=', $category->rgt)
//                    ->where('article_category.category_id', '=', $category->id)
                    ->where('game_articles.game_id', '=', $this->game->id)
                    ->groupBy('articles.id')
                    ->orderBy('articles.created_at','desc')
                    ->paginate(20, array('articles.*', 'category.alias as category_alias'));
            }
            else
                $paginator = Articles::join('game_articles', 'articles.id', '=', 'game_articles.article_id')
                    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
                    ->join('category', 'category.id', '=', 'article_category.category_id')
                    ->where('article_category.is_game_main', '=', 1)
                    ->where('game_articles.game_id', '=', $this->game->id)
                    ->groupBy('articles.id')
                    ->orderBy('articles.created_at','desc')
                    ->paginate(20, array('articles.*', 'category.alias as category_alias'));

            $paginatorLinks = preg_replace("/page=([0-9]*)/","page=$1#top",$paginator->links());

            $this->layout->content = \View::make('game.news')->with('gameId', $this->game->id)
                ->with('paginator',$paginator)
                ->with('category',$category)
                ->with('paginatorLinks',$paginatorLinks);
            $this->layout->title = 'Tin tức | ';
        }else
        {

        }

    }

    public function guideDetail($slug)
    {
        $this->loadLayout( 'huongdan');
        $tmpArr = explode('-', $slug);
        $articleId = array_pop($tmpArr);
        $anArticle = Articles::find($articleId);
        if(!$anArticle )
            throw new BusinessException('Không tìm thấy bài viết.');
        $this->layout->content = \View::make('game.guide_detail')->with('gameId', $this->game->id)
            ->with('anArticle', $anArticle);
        $this->layout->title = $anArticle->title.' | ';
        $this->layout->description = $anArticle->description;
        $this->layout->keyword = $anArticle->keyword;
    }


//--------for news.domain-------------------------------



    public function newsList( $catSlug)
    {
        $myApp=App::make('myApp');

        if($myApp->isOnNews){
            $this->layout = 'list';
            $this->setupLayout();
            $category = Category::where('alias', '=', $catSlug)
                ->where('group_code', '=',  \Util\CommonConstant::CATEGORY_ARTICLE)->first();
            if(!$category)
                throw new \Util\Exceptions\SystemException('Nhóm tin: '.$catSlug.' không tồn tại');

            $paginator = Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
                ->join('article_category', 'articles.id', '=', 'article_category.article_id')
                ->join('category', 'category.id', '=', 'article_category.category_id')
                ->where('category.lft', '>=', $category->lft)
                ->where('category.rgt', '<=', $category->rgt)
                ->where('category.group_code', '=', $category->group_code)
                ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
                ->groupBy('articles.id')
                ->orderBy('articles.id','desc')
                ->paginate(3, array('articles.*', 'category.alias as category_alias',  'uploads.path'));

            $this->layout->with('paginator',$paginator)
                ->with('category',$category);
            $this->layout->title = $category->name.' | ';
        }


    }

    public function newsDetail($catSlug , $slug)
    {
        $myApp=App::make('myApp');

        //------for game page----------
        if($myApp->isOnNews){
            $this->layout = 'detail';
            $this->setupLayout();
            $tmpArr = explode('-', $slug);
            $articleId = array_pop($tmpArr);
            $anArticle = Articles::find($articleId);
            $category = Category::where('alias', '=', $catSlug)->first();
            if(!$anArticle)
                throw new BusinessException('Tin '.$slug.' không tồn tại.');
            if(!$category)
                throw new BusinessException('Nhóm tin '.$catSlug.' không tồn tại.');

            $anArticle->view_count = $anArticle->view_count + 1;
            $anArticle->save();

            $allRelated = $anArticle->findNewsRelated($category);

            $this->layout->with('anArticle', $anArticle)
                ->with('category', $category)
                ->with('allRelated', $allRelated);
            $this->layout->title = $anArticle->title.' | ';
            $this->layout->description = $anArticle->description;
            $this->layout->keyword = $anArticle->keyword;
        }else
        {

        }
    }

    public function getList($catSlug){
        $this->loadLayout('list');
        $category = Category::where('alias', '=', $catSlug)->where('group_code',\Util\CommonConstant::CATEGORY_SUB_ARTICLE)->first();
        $this->layout->category = $category;
    }

}
