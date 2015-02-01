<?php
use Util\Oauth2\PlaygateIDOauth2;

$playgateIDOauth2 = new  PlaygateIDOauth2();
$loginUrl = $playgateIDOauth2->generateLoginUrl();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageItem->title ?></title>
    <link rel="stylesheet" href="/css/front-style.css">

    <link rel="stylesheet" href="/css/bootstrap.css">
    <!--<link rel="author" href="humans.txt">-->
</head>
<body>
<!--<h1><?php /*echo $content*/?></h1>-->
<div id="fb-root"></div>
<script>
    /*(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&appId=266392960206747&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));*/
</script>
<header>
    <div class="container">
        <nav class="top-nav navbar nomargin">
            <div class="row">
                <div class="logo col-lg-3">
                    <img src="images/logo.png" alt="logo" />
                </div>
                <div class="gamelist col-lg-2">
                    <a href="#">Danh sách game</a>
                </div>
                <ul class="col-lg-4 list-unstyled list-inline nomargin">
                    <div class="row">
                        <li class="col-lg-4 text-center"><a href="#">Tin tức</a></li>
                        <li class="col-lg-4 text-center"><a href="#">Diễn đàn</a></li>
                        <li class="col-lg-4 text-center"><a href="#">Hỗ trợ</a></li>
                    </div>

                </ul>
                <div class="user col-lg-2 pull-right">
                    <div class="row nomargin">
                        <a class="col-lg-6 text-center" href="<?php echo $loginUrl ?>">Đăng nhập</a>
                        <a class="col-lg-6 text-center" href="#">Đăng ký</a>
                    </div>

                </div>
            </div>
        </nav>
    </div>
</header>

<?php echo $content?>
<footer>
    <div class="container">
        <section class="info text-center">
            <p>Công ty TNHH Phúc Thành</p>
            <p>Địa chỉ: Tâng 03, 02 Đội Cung, Hai Bà Trưng, Hà Nội</p>
            <p>Tel: 04 38 999 888 (Việt Nam) - 84-4-397 434 10 (Ext: 156)</p>
            <p>Email: <a href="#">hotro@sphucthanh.vn</a> - <a href="#">Google+</a></p>
        </section>
        <section class="license text-center">
            <p>Giấy phép: 191/GP-TTĐT do Cục Quản lý phát thanh, truyền thông và thông tin điện tử cấp ngày 19/06/2010 </p>
        </section>
    </div>
</footer>
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>