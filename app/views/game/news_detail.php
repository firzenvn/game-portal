
<section class="news">
    <section class="news-nav">
        <h2>Tin tức</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; <a href="/tin-tuc">Tin Tức</a> &raquo; <?php echo $anArticle->title ?>
    </section>
    <section class="news-title">
        <?php
            echo('<h3>'.$anArticle->title.'</h3>');
        ?>
    </section>
    <section class="news-content">
        <?php
            echo($anArticle->content);
        ?>
    </section>

    <section class="prev-next">
        <div class="col-xs-6">
            <a href="#">
                &laquo; <span>Tiêu đề bài xem trước</span>
            </a>
        </div>
        <div class="col-xs-6 text-right">
            <a class="text-right" href="#">
                <span>Tiêu đề bài tiếp theo</span> &raquo;
            </a>
        </div>

    </section>
    <section class="news-related">
        <h3>TIN LIÊN QUAN</h3>
        <ul class="list-unstyled list-inline">
        <?php
        foreach ($allRelated as $relatedArticle) {
            echo('<li><a href="'.$relatedArticle->getGameUrl($relatedArticle->category_alias).'#top">['.$relatedArticle->category_name.'] '.$relatedArticle->title.'</a></li>');
        }
        ?>
        </ul>
    </section>
</section>