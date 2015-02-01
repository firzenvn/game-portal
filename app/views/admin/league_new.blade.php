<h3><a href="/{{$urlSegment}}/league">Quản lý giải đấu</a> &raquo; Thêm mới giải đấu</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên' ) ) }}
    </div>
</div>

{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::textarea( "txtDescription" , Input::old( "description" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập mô tả', 'style'=>'height:60px' ) ) }}
    </div>
</div>

{{-- Games --}}
<div class="form-group">
    {{ Form::label( "cboGame" , 'Game' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboGame', $allGame, null, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "start_date" , 'Ngày bắt đầu' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-2">
        {{Form::text('start_date',Input::get('start_date'),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
    </div>
</div>
<div class="form-group">
    {{ Form::label( "end_date" , 'Ngày kết thúc' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-2">
        {{Form::text('end_date',Input::get('end_date'),array('class'=>'form-control input-sm','placeholder'=>'Đến:','id'=>'end_date'))}}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10" >
        <input type="checkbox" id="chkActive" checked style="margin: 10 0 0 0px" />
    </div>
</div>



<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Lưu</a>


{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#txtName').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    })



    function save(){
        $.blockUI({ message: 'Vui lòng chờ' });
        name = $('#txtName').val();
        description = $('#txtDescription').val();

        active = $('#chkActive').is(':checked')?1:0;
        game = $('#cboGame').val();
        start_date = $('#start_date').val();
        end_date = $('#end_date').val();


        $.post('/admin/league/new',{
                name:name,  active:active,end_date:end_date,
                game:game, description:description, start_date:start_date
            }
            ,function(result){

                $.unblockUI();
                $('#ajaxMsg').html(result.msg);
                if(result.success){

                    window.location.assign('/admin/league')
                }else{

                }

            },'json');
    }



</script>
