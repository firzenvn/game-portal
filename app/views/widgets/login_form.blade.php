<section class="login-form">
    <section class="login-nav">Đăng nhập</section>
    <section class="social">
        <a class="fb-icon" href="<?php echo \Util\GameHelper::getOauth2_BASE_URL() ?>/login?by=facebook&return_url=<?php echo Request::url() ?>">Facebook</a>
        <a class="gp-icon" href="<?php echo \Util\GameHelper::getOauth2_BASE_URL() ?>/login?by=google&return_url=<?php echo Request::url() ?>">Google+</a>
    </section>
    <form>
        <div class="form-group">
            <input type="text" name="username" id="txtUsername" placeholder="Email/ Tên đăng nhập" />
            <input type="password" name="password" id="txtPassword" placeholder="Mật khẩu" />
        </div>
        <section class="login-button">
            <input type="button" value="Đăng nhập" onclick="login();"/>
        </section>
        <label><input type="checkbox" name="remember" /> Ghi nhớ</label>
        <a class="forget-pass" href="{{\Util\GameHelper::getOauth2_BASE_URL()}}/users/recover-password">Quên mật khẩu</a>
    </form>
</section>
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
