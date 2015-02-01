<?php
$myApp = App::make('myApp');
if($myApp->externalPortal)
    return;

?>
<?php
use Util\Oauth2Helper;

$myApp=App::make('myApp');
$game = $myApp->game;
$category = \EModel\Category::where('group_code', '=', \Util\CommonConstant::CATEGORY_SUB_ARTICLE)
    ->where('code', '=', \Util\CommonConstant::CATEGORY_ARTICLE_HEADER_BAR_CODE)->first();
if($category)
    $allArticles = \EModel\Articles::join('article_category', 'articles.id', '=', 'article_category.article_id')
        ->join('game_articles', 'articles.id', '=', 'game_articles.article_id')
        ->join('category', 'category.id', '=' , 'article_category.category_id')
        ->where('category.lft', '>=', $category->lft)
        ->where('category.rgt', '<=', $category->rgt)
        ->where('game_articles.game_id','=', $game->id)->orderBy('created_at', 'desc')
        ->limit(1)
        ->get(array('articles.*',  'category.code', 'category.name as category_name','category.alias as category_alias'));
else
    $allArticles = array();

$totalBalance =0;
$account= Oauth2Helper::loadAccounts();
$primaryBalance = (int)$account->mainBalance;
$secondaryBalance = (int)$account->subBalance;

$totalBalance = $primaryBalance + $secondaryBalance;

$allGames = \EModel\Games::join('uploads','games.id', '=', 'uploads.uploadable_id')
    ->join('game_categories','games.id','=','game_categories.game_id')
    ->join('category', 'category.id', '=' , 'game_categories.category_id')
    ->where('games.active',1)
    ->where('category.group_code',\Util\CommonConstant::CATEGORY_GAME)
    ->where('uploads.type', '=', \Util\CommonConstant::UPLOAD_TYPE_GAME_THUMB_IMAGE)
    ->orderBy('games.id','desc')->limit(3)->get(array('games.*', 'uploads.path', 'category.name as category_name'));

?>

<nav class="playgate-nav">
    <div class="container">
        <div class="row">
            <section class="main-logo col-xs-3">
                <a href="#"><img src="/media/common/img/logo.png" alt=""></a>
            </section>
            <section class="col-xs-2 nav-event">
                <?php
                foreach ($allArticles as $anArticle) {
                    echo('<a href="'.$anArticle->getGameUrl($anArticle->category_alias).'#top" target="_blank" title="'.$anArticle->title.'">'.$anArticle->title.'</a>');
                }

                ?>

            </section>
            <section class="btn-danhsachgame col-xs-2">
                <a href="javascript:;">Game HOT</a>
                <section class="danhsachgame">
                    <ul>
                        @foreach($allGames as $aGame)
                        <li>
                            <section class="thumb">
                                <a href="http://{{$aGame->subdomain}}.{{\Illuminate\Support\Facades\Config::get('app.base_domain')}}" target="_blank"><img src="{{$aGame->path}}" alt=""/></a>
                            </section>
                            <section class="content">
                                <p class="game-name"><a href="http://{{$aGame->subdomain}}.{{\Illuminate\Support\Facades\Config::get('app.base_domain')}}" target="_blank">{{$aGame->name}}</a></p>
                                <p class="game-type">{{$aGame->category_name}}</p>
                                <p class="description">{{$aGame->description}}</p>
                                <p class="count" style="display: none">22.224 người chơi</p>
                                <a class="more" href="http://{{$aGame->subdomain}}.{{\Illuminate\Support\Facades\Config::get('app.base_domain')}}" target="_blank">Chi tiết &raquo;</a>
                            </section>
                        </li>
                        @endforeach

                    </ul>
                </section>
            </section>
            <script type="text/javascript">
                $(function(){
                    var maxlength = 130;
                    $('.danhsachgame ul li p.description').each(function(e){
                        var str = $(this).html();
                        if(str.length > maxlength){
                            str = str.substring(0, maxlength)+'...';
                            $(this).html(str);
                        }
                    });
                });
            </script>
            <section class="hello col-xs-6 text-right">
                @if(!Auth::user())
                <a href="javascript:;" data-toggle="modal" data-target="#popup-dangky">Đăng ký</a> |
                <a href="javascript:;" data-toggle="modal" data-target="#popup-dangnhap">Đăng nhập</a>
                <div class="modal fade" id="popup-dangnhap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Đăng nhập</h4>
                            </div>
                            {{Form::open(array('url'=>'#'))}}
                            <div class="modal-body">

                                <div class="form-group">
                                    <input type="text" id="username" placeholder="Tên đăng nhập" class="form-control user"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" id="password" placeholder="Mật khẩu" class="form-control pass"/>
                                </div>
                                <label for="checkbox">
                                    <input type="checkbox" value="on" name="checkbox" id="chkConfirm" />
                                    Ghi nhớ đăng nhập
                                </label>
                                <div id="ctnRegisterStatus" style="display: none"></div>
                                <div class="row">
                                    <div class="col-xs-4 text-left">
                                        <button type="button" class="btn btn-primary" id="buttonLogin" onclick="login2()" >Đăng nhập</button>
                                    </div>
                                    <div class="col-xs-4 text-right to-register">
                                        <a href="javascript:;" data-toggle="modal" data-target="#popup-dangky" data-dismiss="modal">Đăng ký</a>
                                    </div>
                                    <div class="col-xs-4 text-right forgetpass">
                                        <a href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/users/recover-password" target="_blank">Quên mật khẩu</a>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <p>Đăng nhập bằng tài khoản khác</p>
                                <a class="fb-icon" href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/login?by=facebook&return_url={{Request::url()}}">Facebook</a>
                                <a class="gp-icon" href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/login?by=google&return_url={{Request::url()}}">Google+</a>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="popup-dangky" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Đăng ký</h4>
                            </div>
                            {{Form::open(array('url'=>'#'))}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <input  id="txtRegisterUserName"  type="text" placeholder="Tên đăng nhập" class="form-control user"/>
                                </div>
                                <div class="form-group">
                                    <input id="txtRegisterPassword" type="password" placeholder="Mật khẩu" class="form-control pass"/>
                                </div>
                                <div class="form-group">
                                    <input id="txtRegisterRetypedPassword" type="password" placeholder="Nhập lại mật khẩu" class="form-control pass"/>
                                </div>
                                <label for="checkbox">
                                    <input id="chkRegisterConfirm" type="checkbox" checked />
                                    Tôi đã đọc và đồng ý với <a href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/terms" target="_blank">các điều khoản</a> của MAXGATE
                                </label>
                                <span id="ctnRegisterStatus" style="padding: 5px"></span>
                            </div>
                            <div class="modal-footer">
                                <section class="col-xs-4">
                                    <button type="button" class="btn btn-primary" id="buttonRegister" >Đăng ký</button>
                                </section>
                                <section class="col-xs-8">
                                    <section class="register-social">
                                        <span>Hoặc dùng TK: </span>
                                        <a class="fb-icon" title="Đăng ký bằng Facebook" href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/login?by=facebook&return_url={{Request::url()}}"></a>
                                        <a class="gp-icon" title="Đăng ký bằng Google" href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/login?by=google&return_url={{Request::url()}}"></a>
                                    </section>
                                </section>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
                @else
                <a href="javascript:;">Xin chào, {{Auth::user()->username}}<span class="caret"></span></a>
                <section class="dropdown">
                    <div class="row">
                        <div class="user-info">
                            <div class="col-xs-12">
                                <div class="avatar col-xs-3">
                                    <img src="/images/no-avatar.png" alt="avatar"/>
                                </div>
                                <div class="user-name col-xs-9">
                                    <p>{{Auth::user()->username}}</p>
                                    <div class="email">{{isset(Auth::user()->email) ? Auth::user()->email : ''}}</div>
                                    <div class="taikhoanxu"><span>{{number_format($totalBalance)}}</span> XU</div>
                                    <section class="taikhoanxu-icon">
                                        <a id="icon-show-taikhoanxu" href="javascript:;">?</a>
                                        <section class="show-taikhoanxu" style="display: none">
                                            <p>Tài khoản chính: <span>{{number_format($primaryBalance)}} xu</span></p>
                                            <p>Tài khoản phụ: <span>{{number_format($secondaryBalance)}} xu</span></p>
                                        </section>
                                    </section>

                                </div>
                            </div>
                        </div>
                        <ul class="link-list list-unstyled col-xs-12">
                            <li><a href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/users/profile" target="_blank">Thông tin tài khoản</a></li>
                            <li><a href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/users/profile?panel=security" target="_blank">Đổi mật khẩu</a></li>
                            <li><a href="/nap-tien" target="_blank">Nạp tiền</a></li>
                        </ul>
                        <div class="botmenu col-xs-12 text-right">
                            <a href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/users/logout?return_url={{URL::to('/')}}" class="btn btn-default">Đăng xuất</a>
                        </div>
                    </div>
                </section>
                @endif
            </section>
        </div>
    </div>
</nav>
<script>
    $(function(){

        $("#popup-dangnhap").on("shown.bs.modal",function(){
            $("#username").focus();
        });

        $("#password").keypress(function(e){
            if(e.which==13){
                login2();
            }
        });

        $("#popup-dangky").on("shown.bs.modal",function(){
            $("#txtRegisterUserName").focus();
        });

        $("#txtRegisterRetypedPassword").keypress(function(e){
            if(e.which==13){
                $('#buttonRegister').trigger('click');
            }
        });

        if ($.browser.webkit) {
            $('#username').attr('autocomplete', 'off');
        }
        $('#icon-show-taikhoanxu').hover(function(){
            $('.show-taikhoanxu').toggle(50);
        });

        $('.modal').appendTo("body");
        $('#buttonRegister').click(function(){
            retUrl = window.location.href;

            username = $('#txtRegisterUserName').val();
            password = $('#txtRegisterPassword').val();
            retypePassword = $('#txtRegisterRetypedPassword').val();

            isConfirmed =  $('#chkRegisterConfirm').is(':checked')?1:0;
            if(isConfirmed == 0){
                $('#ctnRegisterStatus').html('Vui lòng xác nhận điều khoản');
                $('#ctnRegisterStatus').show();
                $('#chkRegisterConfirm').focus();
                return;
            }else{
                $('#ctnRegisterStatus').html('');
                $('#ctnRegisterStatus').hide();
            }
            $.post('/register-sso',{
                    username:username, password:password, retypePassword:retypePassword,retUrl:retUrl
                }
                ,function(result){
                    if(result.success){
                        window.location.href = result.url;
                    }else{
                        $('#ctnRegisterStatus').html(result.message);
                        $('#ctnRegisterStatus').show();
                    }
                },'json');
        });

    });



    function login2(){
        username = $('#username').val();
        password = $('#password').val();
        retUrl = window.location.href;

        $.post('/login',{
                password:password, username:username,url:retUrl
            }
            ,function(result){
                if(result.success){
                    window.location.href = result.url;
                }else{
                    alert("Thông tin đăng nhập không hợp lệ");
                    $('#username').focus();
                }
            },'json');
    }
</script>