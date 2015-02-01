<?php
$tinhNangCat = \EModel\Category::where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_HUONG_DAN_CODE)->first();
$suKienCat = \EModel\Category::where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_EVENT_CODE)->first();

?>

<section class="news">
    <section class="news-nav">
        <h2>Tin tức</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; Tin Tức
    </section>

    <ul class="news-menu list-unstyled list-inline">
        <li class="<?php if(!$category) echo('active') ?>"><a href="/tin-tuc#top">Tất cả</a></li>
        <li class="<?php if($category && $category->id == $tinhNangCat->id) echo('active') ?>">
            <a href="/tin-tuc/<?php echo $tinhNangCat->alias ?>#top"><?php echo $tinhNangCat->name ?></a></li>
        <li class="<?php if($category && $category->id == $suKienCat->id) echo('active') ?>">
            <a href="/tin-tuc/<?php echo $suKienCat->alias ?>#top"><?php echo $suKienCat->name ?></a></li>
    </ul>
    <ul class="news-list list-unstyled">
        <?php
        foreach ($paginator as $anArticle) {
            echo('<li>');
            echo('<a href="'.$anArticle->getGameUrl($anArticle->category_alias).'#top" class="col-xs-10">'.$anArticle->title.'</a>');
            echo('<p class="col-xs-2 text-right">['.$anArticle->created_at->format('d/m').']</p>');
            echo('</li>');
        }
        ?>
    </ul>
    <div class="text-center">
        <?php echo $paginatorLinks; ?>
    </div>
</section>