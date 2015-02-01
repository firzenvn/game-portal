<?php
$externalPortal = App::make('myApp')->externalPortal;
$myApp = App::make('myApp');
$src = '/media/game-tpl/'.$myApp->game->subdomain.'/assets/flash/playgame.swf';
if($externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE){
    $src = '/media/game-tpl/'.$myApp->game->subdomain.'/assets/flash/soha_playgame.swf';
}
?>
<section class="playnow">
    <object width="{{isset($fl_width)?$fl_width:'231'}}" height="{{isset($fl_height)?$fl_height:'180'}}">
        <embed src="{{$src}}" width="{{isset($fl_width)?$fl_width:'231'}}" height="{{isset($fl_height)?$fl_height:'180'}}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" menu="false" wmode="transparent"></embed>
    </object>
</section>
<section class="dangky">
    <?php
        $myApp = App::make('myApp');
        if($myApp->externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE){
            echo('<a class="soha_pay" target="_blank" href="http://soap.soha.vn/dialog/TopupCash">Nạp thẻ</a>');
            echo('<a class="soha_register" target="_blank" href="/nap-soha">Nạp Scoin</a>');
        }else{
            if(!Auth::user()){
                echo('<a class="register" href="javascript:;" data-toggle="modal" data-target="#popup-dangky">Đăng ký</a>');
            }
            else{
                echo('<a class="profile" target="_blank" href="'.\Util\GameHelper::getOauth2_BASE_URL().'/users/profile">Tài khoản</a>') ;
            }
            echo('<a class="pay" href="/nap-tien#top">Nạp thẻ</a>');
        }
    ?>


</section>

<script>
    $(function(){
        $("#popup-dangky").on("shown.bs.modal",function(){
            $("#txtRegisterUserName").focus();
        });
    });
    function registerViaIdGate(){

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

        retUrl = window.location.href;


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
    }

</script>
