<?php
    $category =  \EModel\Category::where('code', '=', $code)
        ->where('group_code', '=', Config::get('danhmuc.category-group-code.sub-game'))
        ->first();
    $gameService = App::make('Services\GameService');
    $allGames = $gameService->getByCategoryCode($code, 6)

?>
<section class="primary">
    <nav class="primary-nav navbar">
        <div class="row">
            <h3 class="col-lg-4"><?php echo $category->name ?></h3>
            <div class="page col-lg-1 col-lg-offset-7 pull-right">
                <a href="#">&lsaquo;</i></a>
                <a href="#">&rsaquo;</i></a>
            </div>
        </div>

    </nav>
    <section class="primary-content col-lg-12">
        <div class="row">
            <?php
            $type = Config::get('danhmuc.upload-type.game-topic-image');
            foreach ($allGames as $aGame) {
                $uploads = $aGame->getUploadByType($type);
                $image = '';
                if(count($uploads) > 0)
                    $image = $uploads[0]->path;
                echo '<article class="primary-item col-lg-4 nopadding">
                        <img src="'.$image.'" alt="game 1" />
                        <div class="gameintro">
                            <a href="'.$aGame->getPrivateLink().'">'.$aGame->name.'</a>
                            <p>25.000 người chơi</p>
                        </div>
                    </article>';
            }

            ?>
        </div>

    </section>
</section> <!-- End .primary -->