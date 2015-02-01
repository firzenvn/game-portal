<?php
$gameId = App::make('myApp')->game->id;
?>

@if(!isset($gameId) || !isset($cateCode) || !isset($numNews))
<p class="text-danger">Thiếu tham số</p>
@elseif(!is_numeric($gameId) || !is_array($cateCode) || !is_numeric($numNews))
<p class="text-danger">Tham số chưa đúng định dạng</p>
@else

<?php
	$categories = \EModel\Category::whereIn('code',$cateCode)->get();
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
->whereIn('article_category.category_id',$allCatIds)
->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')
->groupBy('articles.id')
->take($numNews)->get(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name','category.alias as category_alias'));
?>

    <ul>
    @foreach($allArticles as $anArticle)
        <li>
            <img src="{{$anArticle->path}}" alt="{{$anArticle->title}}"/>
            <a href="{{$anArticle->getGameGuideUrl()}}#top">[{{$anArticle->category_name}}] {{$anArticle->title}}</a>
            <p>{{$anArticle->created_at->format('d/m')}}</p>
        </li>
    @endforeach
    </ul>

@endif