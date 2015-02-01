<?php
$edit_url = '/'.$urlSegment.'/games/edit/';
$new_url = '/'.$urlSegment.'/games/new/';
$delete_url= '/'.$urlSegment.'/games/delete/';
?>


<h3>Quản lý game</h3>

{{-- The error / success messaging partial --}}
@include('includes.messaging')

@if( !$items->isEmpty() )
<table class="table table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Mô tả</th>
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
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->description }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->active }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->created_date }}</a></td>
        
        <td>
            <div class="pull-right">
                <a href="{{ $edit_url.$item->id }}" class="btn btn-sm btn-primary">Sửa</a> <a href="javascript:deleteGame({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
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
    function deleteGame(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
    }
</script>