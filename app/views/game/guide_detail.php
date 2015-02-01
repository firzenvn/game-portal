<section class="camnang">
    <section class="camnang-nav">
        <h2>Hướng dẫn</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="">Trang chủ</a> &raquo; <a href="/tin-tuc/Huong-dan#top">Hướng dẫn</a> &raquo; <?php echo $anArticle->title ?>
    </section>
    <section class="camnang-title">
        <?php
        echo('<h3>'.$anArticle->title.'</h3>');
        ?>
    </section>


    <section class="camnang-content">
        <?php
        echo($anArticle->content);
        ?>
    </section>
</section>