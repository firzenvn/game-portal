<h3><a href="/{{$urlSegment}}/giftcodes">Quản lý Giftcode</a> &raquo; Sửa Giftcode: {{$item->name}}</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Name --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name", $item->name ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên Giftcode' ) ) }}
    </div>
</div>

{{-- Game --}}
<div class="form-group">
    {{ Form::label( "cboGame" , 'Game' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::select( "cboGame" ,$allGames+array(''=>'Không chọn') ,Input::old( "cboGame" ,$item->game_id) , array( 'class'=>'selectpicker show-tick' , 'id'=>'cboGame' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "cboServer" , 'Server' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10 allServer">
        {{ Form::select( "cboServer" ,array(null=>'--Tất cả--')+$allServers ,Input::old( "cboServer", $item->game_server_id ) , array( 'class'=>'selectpicker show-tick' , 'id'=>'cboServer' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "cboApplyFor" , 'Dùng cho' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::select( "cboApplyFor" ,$applyFors ,Input::old( "cboApplyFor", $item->apply_for ) , array( 'class'=>'selectpicker show-tick' , 'id'=>'cboApplyFor' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "txtGuideLink" , 'Link Hướng dẫn' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtGuideLink" , Input::old( "txtGuideLink", $item->input_guide_link ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Link hướng dẫn sử dụng code' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "txtGiftLink" , 'Link Phần thưởng' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtGiftLink" , Input::old( "txtGiftLink",$item->gift_link ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Link quà tặng code' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10" >
        <input type="checkbox" id="chkActive" {{$item->active==1?'checked':''}} style="margin: 10 0 0 0px" />
    </div>
</div>


<div class="col-lg-offset-2">
    <a class="btn btn-large btn-primary" href="javascript:save()">Lưu</a>
</div>
{{Form::close()}}
<div class="col-lg-offset-2">
    <a href="/{{$urlSegment}}/giftcodes/list/{{$item->id}}">Danh sách {{$item->name}}</a>
</div>



<script>
    $(function(){
        $("#txtName").focus();
        $(".selectpicker").selectpicker();
         $("#cboGame").change(function(){
            game_id = $('#cboGame').val();
            $.post('/admin/giftcodes/game-server',
            {game_id: game_id}
            ,function(result){
                $('.allserver').html(result);
                $(".selectpicker").selectpicker();
            });
        });
    });

    function save(){
        $.blockUI({ message: 'Vui lòng chờ' });
        name = $('#txtName').val();
        game = $('#cboGame').val();
        active = $('#chkActive').is(':checked')?1:0;
        input_guide_link = $("#txtGuideLink").val();
        gift_link = $("#txtGiftLink").val();
        apply_for = $("#cboApplyFor").val();
         server = $("#cboServer").val();


        $.post('/admin/giftcodes/edit/{{$item->id}}',{
                name:name, active:active, game:game, input_guide_link:input_guide_link,
                gift_link:gift_link, apply_for:apply_for, server:server
            }
            ,function(result){

                $.unblockUI();
                $('#ajaxMsg').html(result.msg);
                if(result.success){

                }else{

                }

            },'json');
    }

</script>