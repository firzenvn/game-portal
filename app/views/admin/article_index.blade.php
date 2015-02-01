<?php
$edit_url = '/'.$urlSegment.'/articles/edit/';
$new_url = '/'.$urlSegment.'/articles/new/';
$delete_url= '/'.$urlSegment.'/articles/delete/';
?>

<div class="form-group">
    <div class="col-lg-10">
        <h3>Quản lý bài viết</h3>
    </div>
    <div class="col-lg-2">
        <a style="margin-top: 15px" href="{{ $new_url }}" class="btn btn-primary pull-right">Thêm mới</a>
    </div>
</div>
<div style="clear: both"></div>



{{-- The error / success messaging partial --}}
@include('includes.messaging')
    {{Form::open(array('url'=>'/'.$urlSegment.'/articles', 'method'=>'get', 'role'=>'form'))}}



<div class="form-group">
    <div class="row">
        <div class="col-xs-3">
            <label for="">Tìm kiếm theo nhóm:</label>
        </div>
        <div class="col-xs-3">
            <select class="form-control" name="priCat">
                <option value="">-- Chọn nhóm chính --</option>
                <?php
                foreach ($allPrimaryCategory as $aCategory) {
                    if($aCategory->id==Input::get('priCat'))
                    {
                        echo '  <option value="'.$aCategory->id.'" selected>'.$aCategory->name.'</option>';
                    }
                    else echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
                }

                ?>
            </select>
        </div>
        <div class="col-xs-3">
            <select class="form-control" name="subCat">
                <option value="">-- Chọn nhóm phụ --</option>
                <?php
                foreach ($allSubCategory as $aCategory) {
                    if($aCategory->id==Input::get('subCat'))
                    {
                        echo '  <option value="'.$aCategory->id.'" selected>'.$aCategory->name.'</option>';
                    }
                    else echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
                }

                ?>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-xs-1">
            {{Form::text('id',Input::get('id'),array('class'=>'form-control input-sm','placeholder'=>'ID'))}}
        </div>
        <div class="col-xs-2">
            {{Form::select('game',$allGames+array(''=>'Chọn game'),Input::get('game'),array('class'=>'form-control input-sm'))}}
        </div>
        <div class="col-xs-2">
        {{Form::text('title',Input::get('title'),array('class'=>'form-control input-sm','placeholder'=>'Tiêu đề:'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('start_date',Input::get('start_date'),array('class'=>'form-control input-sm','placeholder'=>'Từ:','id'=>'start_date'))}}
        </div>
        <div class="col-xs-2">
            {{Form::text('end_date',Input::get('end_date'),array('class'=>'form-control input-sm','placeholder'=>'Đến:', 'id'=>'end_date'))}}
        </div>
        <div class="col-xs-2">
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
        <th>Tiêu đề</th>
        <th>Game</th>
        <th>Ngày tạo</th>
        <th>Active</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
    <tr>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->id }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->title }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->game }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->created_at }}</a></td>
        <td><a href="{{ $edit_url.$item->id }}">{{ $item->active }}</a></td>
        <td>
            <div class="pull-right">
                <a href="{{ $edit_url.$item->id }}" class="btn btn-sm btn-primary">Sửa</a>
                <a href="javascript:deleteArticle({{$item->id}})" class="btn btn-sm btn-danger">Xóa</a>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
{{$items->appends(array(
    'id'=>Input::get('id'),
    'title'=>Input::get('title'),
    'game'=>Input::get('game'),
    'start_date'=>Input::get('start_date'),
    'end_date'=>Input::get('end_date'),
    'active'=>Input::get('active'),
    'priCat'=>Input::get('priCat'),
    'subCat'=>Input::get('subCat')
    ))->links()}}
</div>

<script type="text/javascript">
    function deleteArticle(id){
        bootbox.confirm("Bạn chắc chắn muốn xóa?", function(result) {
            if(result){
                window.location.assign('{{$delete_url}}' + id)
            }
        });
        $(function(){
            $('.selectpicker').selectpicker();
        });
    }
</script>