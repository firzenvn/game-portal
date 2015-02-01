<?php
/**
 * Created by PhpStorm.
 * User: Lê Trọng Dương
 * Date: 7/2/14
 * Time: 10:44 PM
 * To change this template use File | Settings | File Templates.
 */

use Gregwar\Captcha\CaptchaBuilder;
use Util\CommonConstant;

Widget::register('captcha',function(){
    $builder = new CaptchaBuilder;
	$builder->setIgnoreAllEffects(true);
	$builder->build();
    $captcha = $builder->inline();
    Session::put('captchaPhrase', $builder->getPhrase());
    return View::make('widgets.captcha',array(
        'captcha' => $captcha
    ));
});

/**
 * Widget hiển thị feed news từ zing
 */
Widget::register('zing_feed_news',function($feed_name, $feed_url, $feed_display=5){
	if(Cache::has($feed_name)){
		$news_items = json_decode(Cache::get($feed_name),true);
		return View::make('widgets.zing_feed_news')->with('news_items',$news_items)->with('feed_display',$feed_display);
	}

	$rss = new DOMDocument();
    if(@$rss->load($feed_url) === false){
        return array();
    }

	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array (
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'brief' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'image' => $node->getElementsByTagName('image')->item(0)->nodeValue,
		);
		array_push($feed, $item);
	}

	Cache::put($feed_name,json_encode($feed), 60);
	return View::make('widgets.zing_feed_news')->with('news_items',$feed)->with('feed_display',$feed_display);
});


/*
 * widget tạo danh sách tin theo danh mục
 * @param $cateCode array   Code của Category cần lấy tin
 * @param $numNews int  Số lượng tin cần lấy
 * @param $paginate bool    Cần phân trang hay không
 * */
Widget::register('articles_list',function($cateCode, $numNews, $paginate = false){
    $gameId = App::make('myApp')->game->id;
    if(!isset($cateCode) || !is_array($cateCode)) return;
    if(!isset($numNews) || !is_numeric($numNews)) $numNews = 5;

    $categories = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
        ->whereIn('code',$cateCode)->get();
    $allCatIds = array();
    foreach ($categories as $aCategory) {
        array_push($allCatIds, $aCategory->id);
        $tmpArr = $aCategory->getAllChildren();
        foreach ($tmpArr as $aChild) {
            array_push($allCatIds, $aChild->id);
        }
    }
    $allCatIds = array_unique($allCatIds);


    $allArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
        ->join('article_category', 'articles.id', '=', 'article_category.article_id')
        ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
        ->join('category', 'category.id', '=' , 'article_category.category_id')
        ->select(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name','category.alias as category_alias'))
        ->whereIn('article_category.category_id',$allCatIds)
        ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')
        ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
        ->groupBy('articles.id')
        ->paginate($numNews);

    return View::make('widgets.articles_list')->with('allArticles',$allArticles)->with('paginate',$paginate);
});

Widget::register('slider',function($numNews){
    $gameId = App::make('myApp')->game->id;
    if(!isset($numNews) || !is_numeric($numNews)) $numNews = 5;

    $category = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
        ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_TOPSLIDER_CODE)->first();

    $allArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
        ->join('article_category', 'articles.id', '=', 'article_category.article_id')
        ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
        ->where('article_category.category_id', '=', $category->id)
        ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_TOPIC_IMAGE)
        ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')
        ->limit($numNews)
        ->get(array('articles.*', 'uploads.path'));

    return View::make('widgets.slider')->with('allArticles',$allArticles)->with('category',$category);
});

Widget::register('login_form',function(){
    return View::make('widgets.login_form');
});

Widget::register('guides_list',function($cateCode, $numNews,$paginate = false){
    $gameId = App::make('myApp')->game->id;
    if(!isset($cateCode) || !is_array($cateCode)) return;
    if(!isset($numNews) || !is_numeric($numNews)) $numNews = 5;

    $categories = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
        ->whereIn('code',$cateCode)->get();
    $allCatIds = array();
    foreach ($categories as $aCategory) {
        array_push($allCatIds, $aCategory->id);
        $tmpArr = $aCategory->getAllChildren();
        foreach ($tmpArr as $aChild) {
            array_push($allCatIds, $aChild->id);
        }
    }
    $allCatIds = array_unique($allCatIds);


    $allArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
        ->join('article_category', 'articles.id', '=', 'article_category.article_id')
        ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
        ->join('category', 'category.id', '=' , 'article_category.category_id')
        ->select(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name','category.alias as category_alias'))
        ->whereIn('article_category.category_id',$allCatIds)
        ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')
        ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
        ->groupBy('articles.id')
        ->paginate($numNews);

    return View::make('widgets.guides_list')->with('allArticles',$allArticles)->with('paginate',$paginate);
});

Widget::register('chatbox',function($chatbox_name){
    return View::make('widgets.chatbox')->with('chatbox_name',$chatbox_name);
});

Widget::register('giftcode_fb',function($name,$linkFB,$game_server_id = null){
    $myApp=App::make('myApp');
    $giftcode_type = \EModel\GiftCodeType::where('game_id',$myApp->game->id)
        ->where('apply_for', CommonConstant::GIFT_CODE_FACEBOOK)
        ->where('game_server_id', $game_server_id)
        ->where('active',CommonConstant::GIFT_CODE_ACTIVE)
        ->orderBy('created_at','desc')->first();
    if(!isset($giftcode_type)){
        return '<p>Hiện tại chưa có Giftcode này</p>';
    }
    return View::make('widgets.giftcode_fb')
        ->with('name',$name)
        ->with('linkFB',$linkFB)
        ->with('giftcode_type',$giftcode_type);
});
