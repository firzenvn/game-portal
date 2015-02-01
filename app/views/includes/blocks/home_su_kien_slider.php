<?php
$gameId = App::make('myApp')->game->id;

$category = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_TOPSLIDER_CODE)->first();
//    $gameId = 12;
$allArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
    ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
    ->where('article_category.category_id', '=', $category->id)
    ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_TOPIC_IMAGE)
    ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')
    ->limit(6)
    ->get(array('articles.*', 'uploads.path'))
?>
    <div id="slider" class="nivoSlider col-xs-9 list-unstyled">
        <?php
        foreach($allArticles as $aArticle){
            echo('<a href="'.$aArticle->getGameUrl($category->alias).'#top" class="nivo-imageLink" style="display:block">
            <img src="'.$aArticle->path.'" alt="" title="'.$aArticle->title.'" style="visibility: hidden;">
            </a>');
        }
        ?>
    </div>
<script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({directionNav: false });
    });
</script>
