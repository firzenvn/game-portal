<h3>Sửa quốc gia {{$item->name}}</h3>

@include('includes.messaging')
{{ Form::open( array( 'url'=>'/admin/catalogs/country-edit/'.$item->id , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}

{{-- Code --}}
<div class="form-group">
    {{ Form::label( "code" , 'Mã (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "code" , Input::old( "code", $item->code )  , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Mã' ) ) }}
    </div>
</div>

{{-- Name --}}
<div class="form-group">
    {{ Form::label( "name" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "name" , Input::old( "name", $item->name )  , array( 'class'=>'form-control' , 'placeholder'=>'Nhập Tên' ) ) }}
    </div>
</div>


{{ Form::submit('Lưu' , array('class'=>'btn btn-large btn-primary pull-right')) }}

{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#code').focus();
    });
</script>



