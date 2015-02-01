<?php

$gameId = App::make('myApp')->game->id;

$categoryEvent = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_EVENT_CODE)->first();
$categoryTinhNang = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_HUONG_DAN_CODE)->first();
$allTmpCat = array();
array_push($allTmpCat, $categoryEvent->id);
array_push($allTmpCat, $categoryTinhNang->id);
$allCat = $categoryEvent->getAllChildren();
foreach ($allCat as $aCat) {
    array_push($allTmpCat, $aCat->id);
}

$allCat = $categoryTinhNang->getAllChildren();
foreach ($allCat as $aCat) {
    array_push($allTmpCat, $aCat->id);
}



$allArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
    ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
    ->join('category', 'category.id', '=' , 'article_category.category_id')
    ->whereIn('article_category.category_id', $allTmpCat)
    ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
    ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')
    ->groupBy('articles.id')
    ->limit(4)
    ->get(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name','category.alias as category_alias'));


$allTinhNangArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
    ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
    ->join('category', 'category.id', '=' , 'article_category.category_id')
    ->where('category.lft', '>=', $categoryTinhNang->lft)
    ->where('category.rgt', '<=', $categoryTinhNang->rgt)
    ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
    ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')

    ->limit(4)
    ->get(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name'));

$allEventArticles = \EModel\Articles::leftJoin('uploads','articles.id', '=', 'uploads.uploadable_id')
    ->join('article_category', 'articles.id', '=', 'article_category.article_id')
    ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
    ->join('category', 'category.id', '=' , 'article_category.category_id')
    ->where('category.lft', '>=', $categoryEvent->lft)
    ->where('category.rgt', '<=', $categoryEvent->rgt)
    ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_ARTICLE_THUMB_IMAGE)
    ->where('game_articles.game_id','=', $gameId)->orderBy('created_at', 'desc')
    ->limit(4)
    ->get(array('articles.*', 'uploads.path', 'category.code', 'category.name as category_name'))


?>
<div class="row">
    <ul class="mainnews-nav list-unstyled list-inline col-xs-12">
        <li class="active"><a href="javascript:void" section-ref="ctn_all_hot_news">Tất cả</a></li>
        <li><a href="javascript:void" section-ref="ctn_tinh_nang_hot_news"><?php echo $categoryTinhNang->name?></a></li>
        <li><a href="javascript:void" section-ref="ctn_event_hot_news"><?php echo $categoryEvent->name?></a></li>
    </ul>
    <section class="mainnews-content" id="ctn_all_hot_news">
        <section class="mainnews-thumb col-xs-4"><img src="<?php if(count($allArticles) > 0)echo $allArticles[0]->path?>" alt="<?php if(count($allArticles) > 0)echo $allArticles[0]->title?>"></section>
        <section class="col-xs-8 mainnews-list">
            <a href="<?php if(count($allArticles) > 0)echo $allArticles[0]->getGameUrl($allArticles[0]->category_alias)?>#top">
                <h3><?php if(count($allArticles) > 0)echo '['.$allArticles[0]->category_name.'] '.$allArticles[0]->title?></h3>
            </a>
            <ul class="list-unstyled">
                <?php
                $count = 0;
                foreach ($allArticles as $anArticle) {
                    if($count > 0){
                        echo('<li>');
                        echo('<a class="col-xs-9" href="'.$anArticle->getGameUrl($anArticle->category_alias).'#top">- ['.$anArticle->category_name.'] '.$anArticle->title.'</a>');
                        echo('<p class="col-xs-3">['.$anArticle->created_at->format('d/m/Y') .']</p>');
                        echo('</li>');
                    }

                    $count++;
                }
                ?>
            </ul>
        </section>
    </section>


    <section class="mainnews-content" id="ctn_tinh_nang_hot_news" style="display: none">
        <section class="mainnews-thumb col-xs-4"><img src="<?php if(count($allTinhNangArticles) > 0)echo $allTinhNangArticles[0]->path?>" alt="<?php if(count($allTinhNangArticles) > 0) echo $allTinhNangArticles[0]->title?>"></section>
        <section class="col-xs-8 mainnews-list">
            <a href="<?php if(count($allTinhNangArticles) > 0)echo $allTinhNangArticles[0]->getGameUrl($categoryTinhNang->alias)?>#top">
                <h3><?php if(count($allTinhNangArticles) > 0)echo '['.$allTinhNangArticles[0]->category_name.'] '.$allTinhNangArticles[0]->title?></h3>
            </a>
            <ul class="list-unstyled">
                <?php
                $count = 0;
                foreach ($allTinhNangArticles as $anArticle) {
                    if($count > 0){
                        echo('<li>');
                        echo('<a class="col-xs-9" href="'.$anArticle->getGameUrl($categoryTinhNang->alias).'#top">- ['.$anArticle->category_name.'] '.$anArticle->title.'</a>');
                        echo('<p class="col-xs-3">['.$anArticle->created_at->format('d/m/Y') .']</p>');
                        echo('</li>');
                    }
                    $count++;
                }
                ?>
            </ul>
        </section>
    </section>

    <section class="mainnews-content" id="ctn_event_hot_news" style="display: none">
        <section class="mainnews-thumb col-xs-4"><img src="<?php if(count($allEventArticles) > 0)echo $allEventArticles[0]->path?>" alt="<?php if(count($allEventArticles) > 0)echo $allEventArticles[0]->title?>"></section>
        <section class="col-xs-8 mainnews-list">
            <a href="<?php if(count($allEventArticles) > 0)echo $allEventArticles[0]->getGameUrl($categoryEvent->alias)?>#top">
                <h3><?php if(count($allEventArticles) > 0)echo '['.$allEventArticles[0]->category_name.'] '.$allEventArticles[0]->title?></h3>
            </a>
            <ul class="list-unstyled">
                <?php
                $count = 0;
                foreach ($allEventArticles as $anArticle) {
                    if($count > 0){
                        echo('<li>');
                        echo('<a class="col-xs-9" href="'.$anArticle->getGameUrl($categoryEvent->alias).'#top">- ['.$anArticle->category_name.'] '.$anArticle->title.'</a>');
                        echo('<p class="col-xs-3">['.$anArticle->created_at->format('d/m/Y') .']</p>');
                        echo('</li>');
                    }

                    $count++;
                }
                ?>
            </ul>
        </section>
    </section>


</div>
<script>
    $(document).ready(function(){

        $('ul.mainnews-nav a').click(function(){
            $('ul.mainnews-nav li').removeClass('active');
            $(this).closest('li').addClass('active');
            sectionRef = $(this).attr('section-ref');
            $('section.mainnews-content').hide();
            $('#'+sectionRef).show();
        })
    })

</script>