<?php

$myApp = App::make('myApp');
if($myApp->externalPortal)
    return;

$playgateIDOauth2 = new \Util\Oauth2\PlaygateIDOauth2();
$logOutUrl = $playgateIDOauth2->buildLogOut(urlencode(Request::url()));
?>
<?php if(!Auth::user()) {?>
<section class="login-form">
    <section class="login-nav"></section>
    <section class="social">
        <a class="fb-icon" href="<?php echo \Util\GameHelper::getOauth2_BASE_URL() ?>/login?by=facebook&return_url=<?php echo Request::url() ?>">Facebook</a>
        <a class="gp-icon" href="<?php echo \Util\GameHelper::getOauth2_BASE_URL() ?>/login?by=google&return_url=<?php echo Request::url() ?>">Google+</a>
    </section>
    <form role="form" class="form row" >
        <div class="form-group col-xs-9">
            <input type="text" name="username" id="txtUsername" class="form-control input-sm" placeholder="Email/ Tên đăng nhập" />
            <input type="password" name="password" id="txtPassword" class="form-control input-sm" placeholder="Mật khẩu" />
        </div>
        <section class="login-button col-xs-3">
            <input type="button" value="Đăng nhập" onclick="login();"/>
        </section>
        <label class="col-xs-6"><input type="checkbox" name="remember" /> Ghi nhớ</label>
        <section class="col-xs-6">
            <a href="<?php echo \Util\GameHelper::getOauth2_BASE_URL().'/users/recover-password' ?>">Quên mật khẩu</a>
        </section>
    </form>
</section>
<?php }
else{
    ?>

<?php
}
?>
<script>
    function login(){
        username = $('#txtUsername').val();
        password = $('#txtPassword').val();
        retUrl = window.location.href;

        $.post('/login',{
                password:password, username:username,url:retUrl
            }
            ,function(result){
                if(result.success){
                    window.location.href = result.url;
                }else{
                    alert("Thông tin đăng nhập không hợp lệ");
                    $('#txtUsername').focus();
                }
            },'json');
    }

    $(function(){
        $("#txtPassword").keypress(function(e){
            if(e.which==13){
                login();
            }
        });
    });
</script>
