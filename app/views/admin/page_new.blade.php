<h3>Thêm mới page</h3>

@include('includes.messaging')



{{ Form::open( array( 'url'=>'/admin/pages/new' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}

<div class="form-group">
    {{ Form::label( "cboTemplate" , 'Template' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        @include('includes.snippets.elo_collection_to_select', array('data'=>$allTemplates,'id'=>'cboTemplate', 'valCol'=>'id', 'displayCol'=>'name', 'hasBlank'=>true, 'class'=>'selectpicker show-tick'))
    </div>
</div>
{{-- Name --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Tên' ) ) }}
    </div>
</div>
{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtDescription" , Input::old( "description" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập mô tả' ) ) }}
    </div>
</div>

{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtTitle" , 'Tiêu đề' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtTitle" , Input::old( "title" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tiêu đề' ) ) }}
    </div>
</div>

{{-- Url --}}
<div class="form-group">
    {{ Form::label( "txtRoute" , 'Route (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtRoute" , Input::old( "route" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập route' ) ) }}
    </div>
</div>

{{-- Controller --}}
<div class="form-group">
    {{ Form::label( "cboController" , 'Controller' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboController', $allControllers, null, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>

{{-- Url --}}
<div class="form-group">
    {{ Form::label( "txtAction" , 'Action (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtAction" , Input::old( "action" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập action' ) ) }}
    </div>
</div>

<p class="bg-info text-info">Phân quyền truy cập</p>

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
