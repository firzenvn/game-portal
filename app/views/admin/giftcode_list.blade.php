<?php
$delete_url= '/'.$urlSegment.'/giftcodes/delete-code/';
?>

<h3><a href="/{{$urlSegment}}/giftcodes">Quản lý Giftcode</a> &raquo; Danh sách {{$type->name}}:</h3>


{{Form::open(array('url'=>'/admin/giftcodes/import','role'=>'form','class'=>'form','files'=>'true'))}}
<div class="form-group">
    {{Form::label('', 'Tải lên file (*.CSV):', array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-3">
        {{Form::file('file',array('class'=>'','required'))}}
        {{Form::hidden('giftcode_type_id',$type->id)}}
    </div>
    <div class="col-lg-4">
        {{Form::button('Upload',array('class'=>'btn btn-primary','type'=>'submit'))}}
    </div>
</div>
{{Form::close()}}
<div style="clear: both; height: 30px"></div>

{{-- The error / success messaging partial --}}
@include('includes.messaging')
{{Form::open(array('url'=>'/'.$urlSegment.'/giftcodes/list/'.$type->id, 'method'=>'get', 'role'=>'form'))}}
<div class="form-group">
    <div class="row">
        <div class="col-xs-1">
            {{Form::text('id',Input::get('id'),array('class'=>'form-control input-sm','placeholder'=>'ID'))}}
        </div>
        <div class="col-xs-4">
            {{Form::text('code',Input::get('code'),array('class'=>'form-control input-sm','placeholder'=>'Tìm theo code'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('username',Input::get('username'),array('class'=>'form-control input-sm','placeholder'=>'Tìm theo username'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('start_date',Input::get('start_date'),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('end_date',Input::get('end_date'),array('class'=>'form-control input-sm','placeholder'=>'Đến:', 'id'=>'end_date'))}}
        </div>
        <div class="col-xs-1">
            {{Form::button('Tìm', array('class'=>'btn btn-success btn-sm', 'type'=>'submit'))}}
        </div>
    </div>
</div>
{{Form::close()}}
<div>
    <p>Số code: {{$items->getTotal()}}</p>
    <p>Số code đã phát: {{$count_used}}</p>
</div>
<table class="table table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Username</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
    <tr>
        <td><a href="#">{{ $item->id }}</a></td>
        <td><a href="#">{{ $item->code }}</a></td>
        <td><a href="#">{{ $item->username }}</a></td>
        <td>
            <div class="pull-right">
                <a href="javascript:deleteCode({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
    {{$items->appends(array(
        'id'=>Input::get('id'),
        'code'=>Input::get('code'),
        'username'=>Input::get('username'),
        'start_date'=>Input::get('start_date'),
        'end_date'=>Input::get('end_date')
    ))->links()}}
</div>

<script type="text/javascript">
    function deleteCode(id){
        bootbox.confirm("Bạn chắc chắn muốn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
    }
</script>