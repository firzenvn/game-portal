<?php
$huongDanCat = \EModel\Category::where('group_code', '=', Config::get('danhmuc.category-group-code.sub-article'))
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_HUONG_DAN_CODE)
    ->first();
$allChildren = $huongDanCat->getAllChildren();

?>

<section class="camnang-menu" id="camnang-menu">
    <section class="camnang-top"></section>
    <ul class="list-unstyled camnang-content">
        <?php
        $count = 0;
        foreach ($allChildren as $aCategory) {

            $allMyArticles = \EModel\Articles::join('article_category','articles.id','=','article_category.article_id')
                ->join('game_articles','articles.id','=','game_articles.article_id')
                ->where('game_articles.game_id','=',$gameId)
                ->where('article_category.category_id','=',$aCategory->id)
                ->where('articles.active','=',1)
                ->orderBy('articles.created_at', 'desc')
                ->get(array('articles.*'));
            if(count($allMyArticles) > 0){
                if($count == 0)
                    $klass = 'active';
                else
                    $klass = '';
                echo('<li class="'.$klass.'">
            <a href="javascript:;" data-toggle="collapse" data-target="#'.$aCategory->alias.'" data-parent="#camnang-menu">'.$aCategory->name.'</a>
            <ul class="list-unstyled collapse in" id="'.$aCategory->alias.'">');
                foreach ($allMyArticles as $anArticle) {
                    echo '<li><a href="'.$anArticle->getGameGuideUrl().'#top">'.$anArticle->title.'</a></li>';
                }

                echo('</ul></li>');
                $count++;
            }

        }


        ?>

    </ul>
    <section class="camnang-bot"></section>
</section>
<script>
    $(function()
    {
        $(".collapse").on('show.bs.collapse', function(){
            $(this).parent().addClass('active');
        });
        $(".collapse").on('hide.bs.collapse', function(){
            $(this).parent().removeClass('active');
        });
    });
</script>