<?php

$myApp = App::make('myApp');
$game = $myApp->game;
$servers = \Util\GameHelper::getPlayedServer();

$user = Auth::user();
?>
<script type="text/javascript" src="/js/so_ha_client.js"></script>
<section class="charge">
    <section class="charge-nav">
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; Nạp Scoin
    </section>
    <section class="charge-content">

        {{Form::open(array('url'=>'#','id'=>'charge-form','class'=>'col-xs-9'))}}
        <div class="row">
            <div class="form-group chonserver">
                <label for="server" class="col-xs-3">Chọn máy chủ:</label>
                <div class="col-xs-7">
                    <select  class='form-control' id="cboServer">
                        <option value="">-- Chọn server --</option>
                        <?php
                        foreach ($servers as $aServer) {
                            echo('<option value="'.$aServer->id.'">S'.$aServer->order_number.'-'.$aServer->name.'</option>');
                        }
                        ?>
                    </select>
                </div>
            </div>


            <section class="hinhthucnap col-xs-12">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="xu">
                        <div class="form-group">
                            <div class="row" style="display: none" id="ctnCoinSuccess">
                                <div class="col-xs-6" style="border: 1px solid #efefef; background-color: #e0f0d7; color: #0E7A0E;margin: 0px 10px 10px 0px">
                                    Nạp Scoin thành công
                                </div>
                            </div>
                            <div class="row" id="ctnCoinError" style="display: none">
                                <div class="col-xs-6" style="border: 1px solid #ff5c5c; background-color: #ffd1d1; color: #eb1c1c;margin: 0px 10px 10px 0px">
                                </div>
                            </div>
                            <div class="row">

                                <label class="col-xs-2" for="">Số Scoin:</label>
                                <div class="col-xs-4">
                                    <select id="cboScoin" class="form-control">
                                        <option value="10">10 Scoin</option>
                                        <option value="20">20 Scoin</option>
                                        <option value="50">50 Scoin</option>
                                        <option value="100">100 Scoin</option>
                                        <option value="200">200 Scoin</option>
                                        <option value="500">500 Scoin</option>
                                        <option value="1000">1000 Scoin</option>
                                        <option value="2000">2000 Scoin</option>
                                    </select>
                                </div>
                                <div class="col-xs-4" style="display: none" id="input-amount">

                                </div>

                            </div>
                        </div>
                        <div class="clear"></div>


                        <div class="form-group nhapcaptcha">
                            <div class="row">
                                <label class="col-xs-2" for="captcha">Mã xác nhận:</label>
                                <div class="col-xs-4">
                                    {{Form::text('captcha',null,array('class'=>'form-control col-xs-3', 'id'=>'txtCoinCaptcha'))}}
                                </div>
                                <div class="col-xs-5" id="ctnCaptchaCoin">
                                    @captcha()
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </section>

            <section class="confirm col-xs-12">
                {{Form::button('Nạp',array('id'=>'btnCharge', 'type'=>'button','class'=>'btn btn-primary', 'onclick'=>'chargeCoin()'))}}
                {{Form::button('Nhập lại',array('type'=>'reset','class'=>'btn btn-default'))}}
                <span id="spnLoading" style="display: none"><img src="/images/ajax_loading.gif"></span>
            </section>

        </div>
        {{Form::close()}}
        <section class="tylequydoi col-xs-3">


            <div id="ctnTblCoinExchange">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>Mệnh giá</td>
                        <td>{{$game->unit}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>10 Scoin</td>
                        <td>{{number_format(100)}}</td>
                    </tr>
                    <tr>
                        <td>20 Scoin</td>
                        <td>{{number_format(200)}}</td>
                    </tr>
                    <tr>
                        <td>50 Scoin</td>
                        <td>{{number_format(500)}}</td>
                    </tr>
                    <tr>
                        <td>100 Scoin</td>
                        <td>{{number_format(1000)}}</td>
                    </tr>
                    <tr>
                        <td>200 Scoin</td>
                        <td>{{number_format(2000)}}</td>
                    </tr>
                    <tr>
                        <td>500 Scoin</td>
                        <td>{{number_format(5000)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </section>
    </section>


</section>
<script>
    $(function(){
        $(".captcha-refresh").click(function(){
            $.ajax({
                type: "GET",
                url: "/captcha",
                success:function(captcha)
                {
                    $(".captcha").attr("src",captcha);
                }
            });
        });

        $("#input-amount input").number(true,0);

    });

    $(document).ready(function(){
        $('#ctnCaptchaCoin button.captcha-refresh').click();
    });

    function getUrlParameter(sParam)
    {
         sPageURL = window.location.search.substring(1);
         sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

    function chargeCoin(){

        signed_request = getUrlParameter('signed_request');
        serverId = $('#cboServer').val();
        scoin = $('#cboScoin').val();
        captcha = $('#txtCoinCaptcha').val();
        $('#spnLoading').show();
        $('#btnCharge').hide();
        $.post('/do-nap-soha',{
                serverId:serverId, captcha:captcha, scoin:scoin,signed_request:signed_request
            }
            ,function(result){
                $('#ctnCaptchaCoin button.captcha-refresh').click();
                if(result.success){
                    paymentUrl = getPaymentUrl(result.orderId);
                    window.location.assign(paymentUrl);

                }else{
                    $('#ctnCoinError div').html(result.message);
                    $('#ctnCoinSuccess').hide();
                    $('#ctnCoinError').show();
                }
                $('#spnLoading').hide();
                $('#btnCharge').show();

            },'json');
    }

    function doRefresh(){
        $("#cboServer").val($("#cboServer option:first").val());
        $("#cboScoin").val($("#cboScoin option:first").val());
        $('#txtCoinCaptcha').val('');
        $('#ctnCoinError').hide();
        $('#ctnCoinSuccess').hide();
    }

</script>