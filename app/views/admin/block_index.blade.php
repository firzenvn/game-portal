<?php
$edit_url = '/'.$urlSegment.'/blocks/edit/';
$new_url = '/'.$urlSegment.'/blocks/new/';
$delete_url= '/'.$urlSegment.'/blocks/delete/';
?>


<h3>Quản lý block</h3>

{{-- The error / success messaging partial --}}
@include('includes.messaging')

@if( !$items->isEmpty() )
<table class="table table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Loại</th>
        <th>Params</th>
        <th>View file</th>
        <th>Active</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
    <tr>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->id }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->name }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->type }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->params }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->view_file }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->active }}</a></td>
        <td>
            <div class="pull-right">
                <a href="{{ $edit_url.$item->id }}" class="btn btn-sm btn-primary">Sửa</a> <a href="javascript:deleteBlock({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@else
<div class="alert alert-info">
    <strong>Chưa có bản ghi :</strong> Click Thêm mới.
</div>
@endif
<a href="{{ $new_url }}" class="btn btn-primary pull-right">Thêm mới</a>

<script type="text/javascript">
    function deleteBlock(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
    }
</script>