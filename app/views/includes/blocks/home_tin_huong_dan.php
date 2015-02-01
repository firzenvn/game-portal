<?php


    $gameId = App::make('myApp')->game->id;


$category = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_HUONG_DAN_CODE)->first();
$allArticles = \EModel\Articles::join('article_category', 'articles.id', '=', 'article_category.article_id')
    ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
    ->join('category', 'category.id', '=', 'article_category.category_id')
    ->where('category.lft', '>=', $category->lft)
    ->where('category.rgt', '<=', $category->rgt)
    ->where('category.group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')->orderBy('created_at', 'desc')
    ->groupBy('articles.id')
    ->limit(5)
    ->get(array('articles.*'))
?>
<div class="row">
    <section class="huongdan-nav"><h3><a href="<?php echo isset($linkNav) ? $linkNav : '#' ?>"><?php echo $category->name ?></a></h3></section>
    <section class="huongdan-content">
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