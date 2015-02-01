<?php
$edit_url = '/'.$urlSegment.'/giftcodes/edit/';
$new_url = '/'.$urlSegment.'/giftcodes/new/';
$delete_url= '/'.$urlSegment.'/giftcodes/delete/';
?>



<div class="form-group">
    <div class="col-lg-10">
        <h3>Quản lý giftcode</h3>
    </div>
    <div class="col-lg-2">
        <a style="margin-top: 15px" href="{{ $new_url }}" class="btn btn-primary pull-right">Thêm mới loại giftcode</a>
    </div>
</div>
<div style="clear: both"></div>

{{-- The error / success messaging partial --}}
@include('includes.messaging')
{{Form::open(array('url'=>'/'.$urlSegment.'/giftcodes/index', 'method'=>'get', 'role'=>'form'))}}
<div class="form-group">
    <div class="row">
        <div class="col-xs-1">
            {{Form::text('id',Input::get('id'),array('class'=>'form-control input-sm','placeholder'=>'ID'))}}
        </div>
        <div class="col-xs-2">
            {{Form::select('game_id',array(''=>'-- Tên game --')+$allGames,Input::get('game_id'),array('class'=>'form-control input-sm'))}}
        </div>
        <div class="col-xs-3">
            {{Form::text('name',Input::get('name'),array('class'=>'form-control input-sm','placeholder'=>'Tên Giftcode:'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('start_date',Input::get('start_date'),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('end_date',Input::get('end_date'),array('class'=>'form-control input-sm','placeholder'=>'Đến:', 'id'=>'end_date'))}}
        </div>
        <div class="col-xs-1">
            {{Form::select('active',array(''=>'-- Active --','0'=>'Không','1'=>'Có'),Input::get('active'),array('class'=>'form-control input-sm'))}}
        </div>
        <div class="col-xs-1">
            {{Form::button('Tìm', array('class'=>'btn btn-success btn-sm', 'type'=>'submit'))}}
        </div>
    </div>
</div>
{{Form::close()}}

<table class="table table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Game</th>
        <th>Dùng cho</th>
        <th>Active</th>
        <th>Ngày tạo</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
    <tr>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->id }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->name }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $allGames[$item->game_id] }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ Config::get('common.giftcode_apply_for.'.$item->apply_for) }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->active }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->created_at }}</a></td>

        <td>
            <div class="pull-right">
                <a href="/{{$urlSegment}}/giftcodes/list/{{$item->id}}" class="btn btn-sm btn-default">Danh sách code</a>
                <a href="{{ $edit_url.$item->id }}" class="btn btn-sm btn-primary">Sửa</a>
                <a href="javascript:deleteGiftcodetype({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
    {{$items->appends(array(
    'id'=>Input::get('id'),
    'name'=>Input::get('name'),
    'game_id'=>Input::get('game_id'),
    'start_date'=>Input::get('start_date'),
    'end_date'=>Input::get('end_date'),
    'active'=>Input::get('active')
    ))->links()}}
</div>

<script type="text/javascript">
    function deleteGiftcodetype(id){
        bootbox.confirm("Bạn chắc chắn muốn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
    }
</script>
