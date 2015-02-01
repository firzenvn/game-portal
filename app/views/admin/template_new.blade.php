<h3>Thêm mới template</h3>

@include('includes.messaging')
{{ Form::open( array( 'url'=>'/admin/templates/new' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Name --}}
<div class="form-group">
    {{ Form::label( "name" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "name" , Input::old( "name" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Tên' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "file_name" , 'File template' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('file_name', $templateFiles, null, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>


{{-- Description --}}
<div class="form-group">
    {{ Form::label( "description" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "description" , Input::old( "description" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Mô Tả' ) ) }}
    </div>
</div>


{{ Form::submit('Lưu' , array('class'=>'btn btn-large btn-primary pull-right')) }}

{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#name').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    })

</script>
