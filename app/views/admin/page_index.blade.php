<?php
$edit_url = '/'.$urlSegment.'/pages/edit/';
$new_url = '/'.$urlSegment.'/pages/new/';
$delete_url= '/'.$urlSegment.'/pages/delete/';
?>


<h3>Quản lý page</h3>

{{-- The error / success messaging partial --}}
@include('includes.messaging')

@if( !$items->isEmpty() )
<table class="table table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Controller/action</th>
        <th>Template</th>
        <th>Url</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
    <tr>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->id }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->name }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->controller.'@'.$item->action}}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{{ isset($item->template) ? $item->template->name : '' }}}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->route }}</a></td>
        <td>
            <div class="pull-right">
                <a href="{{ $edit_url.$item->id }}" class="btn btn-sm btn-primary">Sửa</a> <a href="javascript:deleteTemplate({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
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
    function deleteTemplate(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
    }
</script>