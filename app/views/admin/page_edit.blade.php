<h3>Sửa page : {{$item->name}}</h3>

@include('includes.messaging')

{{ Form::open( array( 'url'=>'/admin/pages/edit/'.$item->id , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}

<div class="form-group">
    {{ Form::label( "cboTemplate" , 'Template' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        @include('includes.snippets.elo_collection_to_select', array('data'=>$allTemplates,'selected'=>$item->template_id, 'id'=>'cboTemplate', 'valCol'=>'id', 'displayCol'=>'name', 'hasBlank'=>true, 'class'=>'selectpicker show-tick'))
    </div>
</div>
{{-- Name --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name",$item->name ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Tên' ) ) }}
    </div>
</div>
{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtDescription" , Input::old( "description",$item->description ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập mô tả' ) ) }}
    </div>
</div>

{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtTitle" , 'Tiêu đề' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtTitle" , Input::old( "title",$item->title ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tiêu đề' ) ) }}
    </div>
</div>

{{-- Url --}}
<div class="form-group">
    {{ Form::label( "txtRoute" , 'Route (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtRoute" , Input::old( "route",$item->route ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập route' ) ) }}
    </div>
</div>

{{-- Controller --}}
<div class="form-group">
    {{ Form::label( "cboController" , 'Controller' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboController', $allControllers, $item->controller, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>

{{-- Url --}}
<div class="form-group">
    {{ Form::label( "txtAction" , 'Action (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtAction" , Input::old( "action", $item->action ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập action' ) ) }}
    </div>
</div>
<div class="form-group bg-info" style="padding: 7px">
    <a href="javascript:openPageBlockWindow({{$item->id}})"><span class=" text-info" >Quản lý block trên page</span></a>
</div>

{{ Form::submit('Lưu' , array('class'=>'btn btn-large btn-primary pull-right')) }}

{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#name').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('#cboTemplate').selectpicker('val', [{{$item->template_id}}]);
        $('.selectpicker').selectpicker('refresh');
    })

    function openPageBlockWindow(pageId){
        win = window.open('/admin/pages/edit-block/'+pageId, '_blank');
        win.focus();
    }

</script>
