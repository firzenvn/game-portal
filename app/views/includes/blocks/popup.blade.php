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