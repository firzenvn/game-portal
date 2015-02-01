<?php


    $gameId = App::make('myApp')->game->id;


$category = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_TNNB_CODE)->first();
$allArticles = array();
if($category){
    $allArticles = \EModel\Articles::join('article_category', 'articles.id', '=', 'article_category.article_id')
        ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
        ->where('article_category.category_id', '=', $category->id)
        ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')
        ->groupBy('articles.id')
        ->limit(4)
        ->get(array('articles.*'));
}

?>
<div class="row">
    <section class="tinhnang-nav">
        <h3><a href="<?php echo  isset($linkNav) ? $linkNav : '#' ?>"><?php if ($category) echo $category->name ?></a></h3>
    </section>
    <section class="tinhnang-content">
        <ul class="list-unstyled">
            <?php
            foreach ($allArticles as $anArticle) {
                echo('<li>');
                echo('<a href="'.$anArticle->getGameGuideUrl().'#top" class="col-xs-9">- ['.$category->name.'] '.$anArticle->title.'</a>');
                echo('<p class="col-xs-3">['.$anArticle->created_at->format('d/m').']</p>');
                echo('</li>');
            }

            ?>
        </ul>
    </section>
</div>
