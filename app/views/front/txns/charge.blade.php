<?php

use Util\Oauth2Helper;

$myApp = App::make('myApp');
$game = $myApp->game;
$servers = \Util\GameHelper::getPlayedServer();

$totalBalance =0;
//dd($account_balances);
$account= Oauth2Helper::loadAccounts();
$primaryBalance = (int)$account->mainBalance;
$secondaryBalance = (int)$account->subBalance;

$totalBalance = $primaryBalance + $secondaryBalance;
;
?>
<section class="charge">
    <section class="charge-nav">
    	<h2>Nạp tiền</h2>
    </section>
    <section class="breadcrumb text-right">
        <a href="/">Trang chủ</a> &raquo; Nạp tiền
    </section>
    <section class="charge-content">
        <section class="sotien">
            <section class="cothenap">
                Số dư tài khoản: <span>{{number_format($totalBalance)}} Xu</span>
            </section>

            <section class="about-icon">
                <a id="icon-show-account" href="javascript:;">About</a>
                <section class="show-account" style="display: none">
                    <p>Từ tài khoản chính: <span>{{number_format($primaryBalance)}} xu</span></p>
                    <p>Từ tài khoản phụ: <span>{{number_format($secondaryBalance)}} xu</span></p>
                </section>
            </section>
        </section>
        <section class="napxu">
            <a href="javascript:;" data-toggle="modal" data-target="#popup-napxu">Nạp XU</a>
            <div class="modal fade" id="popup-napxu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Nạp XU</h4>
                        </div>
                        <div class="modal-body">
                            <iframe id="register-iframe" src="{{\Util\GameHelper::getOauth2_BASE_URL()}}/charges/index?return_url={{Request::url()}}&ui_mode=pop-up" width="780" height="500" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="clear"></div>
        {{Form::open(array('url'=>'#','id'=>'charge-form','class'=>'col-xs-9'))}}
        <div class="row">
            <div class="row" style="display: none" id="ctnCoinSuccess">
                <div class="col-xs-6" style="border: 1px solid #efefef; background-color: #e0f0d7; color: #0E7A0E;margin: 15px 10px 0px 18px;">
                    Nạp tiền thành công
                </div>
            </div>
            <div class="row" id="ctnCoinError" style="display: none">
                <div class="col-xs-6" style="border: 1px solid #ff5c5c; background-color: #ffd1d1; color: #eb1c1c;margin: 15px 10px 0px 18px;">
                    Nạp tiền thành công
                </div>
            </div>
            <div class="form-group chonserver">
                <label for="server" class="col-xs-3">Chọn máy chủ:</label>
                <div class="col-xs-4">
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
             <div class="form-group chonsotien">
                <label class="col-xs-3" for="amount">Số tiền:</label>
                <div class="col-xs-4">
                    {{Form::select('amount',$card_amounts+array('other'=>'Khác'),'10',array('class'=>'form-control', 'id'=>'cboAmount'))}}
                </div>
                <div class="col-xs-3" style="display: none" id="input-amount">
                    {{Form::text('amount',null,array('class'=>'form-control', 'id'=>'txtOtherAmount'))}}
                </div>
                <div class="col-xs-1" style="padding-top: 8px">
                    XU
                </div>
            </div>
            <div class="clear"></div>
            <div class="form-group nhapcaptcha">
                <label class="col-xs-3" for="captcha">Mã xác nhận:</label>
                <div class="col-xs-4">
                    {{Form::text('captcha',null,array('class'=>'form-control', 'id'=>'txtCoinCaptcha'))}}
                </div>
                <div class="col-xs-4" id="ctnCaptchaCoin">
                    @captcha()
                </div>
            </div>
            <section class="confirm col-xs-12">
                {{Form::button('Nạp',array('id'=>'btnCharge', 'type'=>'button','class'=>'btn btn-primary', 'onclick'=>'doChargePto()'))}}
                {{Form::button('Nhập lại',array('type'=>'reset','class'=>'btn btn-default'))}}
                <span id="spnLoading" style="display: none"><img src="/images/ajax_loading.gif"></span>
            </section>
        </div>
        {{Form::close()}}
        <section class="tylequydoi">
            <div id="ctnTblCoinExchange">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>Mệnh giá thẻ</td>
                        <td>Mệnh giá</td>
                        <td>{{$game->unit}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>10,000đ</td>
                        <td>100 xu</td>
                        <td>{{number_format(100*$game->exchange_rate)}}</td>
                    </tr>
                    <tr>
                        <td>20,000đ</td>
                        <td>200 xu</td>
                        <td>{{number_format(200*$game->exchange_rate)}}</td>
                    </tr>
                    <tr>
                        <td>50,000đ</td>
                        <td>500 xu</td>
                        <td>{{number_format(500*$game->exchange_rate)}}</td>
                    </tr>
                    <tr>
                        <td>100,000đ</td>
                        <td>1,000 xu</td>
                        <td>{{number_format(1000*$game->exchange_rate)}}</td>
                    </tr>
                    <tr>
                        <td>200,000đ</td>
                        <td>2,000 xu</td>
                        <td>{{number_format(2000*$game->exchange_rate)}}</td>
                    </tr>
                    <tr>
                        <td>500,000đ</td>
                        <td>5,000 xu</td>
                        <td>{{number_format(5000*$game->exchange_rate)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <script>
        var whichTab = 0;
        $(function(){
            $("#lnkXu").click(function(){
                whichTab = 0;
                $('#ctnCaptchaCoin button.captcha-refresh').click();
                $('#ctnTblCardExchange').hide();
                $('#ctnTblCoinExchange').show();
                $('#ctnCoinSuccess').hide();
                $('#ctnCoinError').hide();


            })
            $("#lnkTheCao").click(function(){
                whichTab = 1;

                $('#ctnCaptchaCard button.captcha-refresh').click();
                $('#ctnTblCardExchange').show();
                $('#ctnTblCoinExchange').hide();
                $('#ctnCardSuccess').hide();
                $('#ctnCardError').hide();
            })

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
            $("select[name='amount']").change(function(){
                if($(this).val()==='other'){
                    $("#input-amount").fadeIn();
                    $("#input-amount input").focus();
                }else{
                    $("#input-amount").fadeOut();
                }
            });
            $("#input-amount input").number(true,0);
            $('#icon-show-account').hover(function(){
                $('.show-account').toggle(50);
            });
        });

        $(document).ready(function(){
            $('#ctnCaptchaCoin button.captcha-refresh').click();
        })
        function chargeCoin(){
            serverId = $('#cboServer').val();
            amount = $('#cboAmount').val();
            if(amount == 'other')
                amount = $('#txtOtherAmount').val();
            captcha = $('#txtCoinCaptcha').val();

            $('#spnLoading').show();
            $('#btnCharge').hide();
            $.post('/nap-tien',{
                    serverId:serverId, amount:amount,captcha:captcha
                }
                ,function(result){

//
                    $('#ctnCaptchaCoin button.captcha-refresh').click();

                    if(result.success){

                        doRefresh();
                        $('#ctnCoinSuccess div').html(result.message);
                        $('#ctnCoinSuccess').show();
                        $('#ctnCoinError').hide();

                    }else{
                        $('#ctnCoinError div').html(result.message);
                        $('#ctnCoinSuccess').hide();
                        $('#ctnCoinError').show();
                    }
                    $('#spnLoading').hide();
                    $('#btnCharge').show();

                },'json');
        }

        function chargeCard(){
            serverId = $('#cboServer').val();
            pin = $('#txtPin').val();
            seri = $('#txtSeri').val();
            cardType = $('input[name=card_type]:checked').val();
            captcha = $('#txtCardCaptcha').val();
            $('#spnLoading').show();
            $('#btnCharge').hide();
            $.post('/nap-the',{
                    serverId:serverId, pin:pin,seri:seri, cardType:cardType,captcha:captcha
                }
                ,function(result){


                    $('#ctnCaptchaCard button.captcha-refresh').click();

                    if(result.success){

                        doRefresh();
                        $('#ctnCardSuccess div').html(result.message);
                        $('#ctnCardSuccess').show();
                        $('#ctnCardError').hide();
                    }else{
                        $('#ctnCardError div').html(result.message);
                        $('#ctnCardSuccess').hide();
                        $('#ctnCardError').show();
                    }
                    $('#spnLoading').hide();
                    $('#btnCharge').show();

                },'json');
        }


        function doChargePto(){
            if(whichTab == 0){
                chargeCoin();
            }else{
                chargeCard();
            }
        }

        function doRefresh(){
            $("#input-amount").fadeOut();
            $('#txtOtherAmount').val('0');
            $("#cboServer").val($("#cboServer option:first").val());
            $("#cboAmount").val($("#cboAmount option:first").val());
            $('#txtCoinCaptcha').val('');
            $('#txtCardCaptcha').val('');
            $('#txtPin').val('');
            $('#txtSeri').val('');

            $('#ctnCardSuccess').hide();
            $('#ctnCardError').hide();
            $('#ctnCoinError').hide();
            $('#ctnCoinSuccess').hide();
        }

    </script>
</section>            