<?php

$blockArr = $pageItem->blocks();
if(!$blockArr)
    $blockArr = array();

$user = Auth::user();
?>
<h1>{{{$user?$user->username:''}}}</h1>
<section class="slider">
    <div class="container">
        <div class="row">
            <section class="banner col-lg-9">
                <img src="/images/top-banner.jpg" alt="Banner" />
                <section class="caption-bg">
                </section>
                <section class="caption-info col-lg-4">
                    <h3><a href="#">Tề Thiên</a></h3>
                    <p>Webgame Đỉnh cao Mùa Hè 2014</p>
                    <p>28.730 người chơi</p>
                </section>
                <section class="choingay col-lg-2 pull-right">
                    <a href="#"><img src="/images/choingay.png" alt="choi ngay" /></a>
                </section>

            </section>

            <div class="col-lg-3">
                @include('includes.snippets.danh_sach_su_kien')
            </div>

        </div>
    </div>
</section>

<main>
<div class="container">
<div class="row">
<section class="content col-lg-9">

    @include('includes.snippets.phuc_thanh_gioi_thieu', array('code'=>Config::get('danhmuc.game-sub-category-code.ptgt')))

    <section class="gamehot">
        <nav class="gamehot-nav col-lg-12">

            <h3 class="col-lg-3">Game hot</h3>
            <ul class="col-lg-9 list-unstyled list-inline nopadding nomargin">
                <li class="active"><a href="#">Tất cả</a></li>
                <li><a href="#">Web game</a></li>
                <li><a href="#">Mini game</a></li>
                <li><a href="#">Mobile game</a></li>
            </ul>

        </nav>
        <section class="gamehot-content col-lg-12">
            <div class="row">
                <div class="gamehot-column">
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot1.jpg" alt="game hot 1" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot2.jpg" alt="game hot 2" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot3.jpg" alt="game hot 3" />
                        <div class="gameintro">
                            <a href="#">Tiếu Ngạo Giang Hồ</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot4.jpg" alt="game hot 4" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot5.jpg" alt="game hot 5" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                </div>
                <div class="gamehot-column">
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot6.jpg" alt="game hot 6" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot7.jpg" alt="game hot 7" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot8.jpg" alt="game hot 8" />
                        <div class="gameintro">
                            <a href="#">Tiếu Ngạo Giang Hồ</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot9.jpg" alt="game hot 9" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot10.jpg" alt="game hot 10" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                </div>
                <div class="gamehot-column">
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot11.jpg" alt="game hot 11" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot12.jpg" alt="game hot 12" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot13.jpg" alt="game hot 13" />
                        <div class="gameintro">
                            <a href="#">Tiếu Ngạo Giang Hồ</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot14.jpg" alt="game hot 14" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                    <article class="gamehot-item col-lg-five">
                        <img src="/images/hot15.jpg" alt="game hot 15" />
                        <div class="gameintro">
                            <a href="#">Tam Quốc Soha</a>
                            <p>25.544 người chơi</p>
                        </div>
                    </article>
                </div>
            </div>

        </section> <!-- End .gamehot-content -->

    </section> <!-- End .gamehot-->

</section> <!-- End .content -->

<aside class="sidebar col-lg-3">
    @include('includes.snippets.lich_mo_server')



    <section class="topgame">
        <nav class="topgame-nav">
            <h3>Game</h3>
        </nav>
        <section class="topgame-list">
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">1</div>
                        <div class="col-lg-7">iGà</div>
                    </a>
                    <div class="col-lg-3 download">354.055</div>
                    <div class="col-lg-1 download">2</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">2</div>
                        <div class="col-lg-7">Tình Kiếm</div>
                    </a>
                    <div class="col-lg-3 download">203.788</div>
                    <div class="col-lg-1 download">3</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">3</div>
                        <div class="col-lg-7">Beat3DBeat3D</div>
                    </a>
                    <div class="col-lg-3 download">199.073</div>
                    <div class="col-lg-1 download">4</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">4</div>
                        <div class="col-lg-7">Đại Minh Chủ</div>
                    </a>
                    <div class="col-lg-3 download">180.544</div>
                    <div class="col-lg-1 download">5</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">5</div>
                        <div class="col-lg-7">Ngộ Không TKỳ</div>
                    </a>
                    <div class="col-lg-3 download">160.211</div>
                    <div class="col-lg-1 download">6</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">6</div>
                        <div class="col-lg-7">Thủy Hử 3D</div>
                    </a>
                    <div class="col-lg-3 download">147.120</div>
                    <div class="col-lg-1 download">7</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">7</div>
                        <div class="col-lg-7">Ma Thần 3D</div>
                    </a>
                    <div class="col-lg-3 download">132.409</div>
                    <div class="col-lg-1 download">8</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">8</div>
                        <div class="col-lg-7">Xếp Rồng Soha</div>
                    </a>
                    <div class="col-lg-3 download">127.785</div>
                    <div class="col-lg-1 download">9</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">9</div>
                        <div class="col-lg-7">Đế Chế Soha</div>
                    </a>
                    <div class="col-lg-3 download">127.785</div>
                    <div class="col-lg-1 download">9</div>
                </div>
            </article>
            <article class="topgame-item">
                <div class="row">
                    <a href="#">
                        <div class="col-lg-1">10</div>
                        <div class="col-lg-7">Đế Chế Soha</div>
                    </a>
                    <div class="col-lg-3 download">127.785</div>
                    <div class="col-lg-1 download">9</div>
                </div>
            </article>
        </section>
    </section><!-- End .topgame -->
    <div class="fb-like-box" data-href="https://www.facebook.com/phongthanonline.vn" data-width="218" data-height="230" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>
    <section class="sidebar-banner">
        @include('includes.block_place_holder', array('type'=>'3x80', 'containerId'=>'blockSidebar_02', 'blockArr'=>$blockArr))
        @include('includes.block_place_holder', array('type'=>'3x80', 'containerId'=>'blockSidebar_01', 'blockArr'=>$blockArr))
        <img src="/images/sidebar_banner.jpg" alt="Sidebar Banner" />
    </section>


</aside>
</div>
</div>

</main>

