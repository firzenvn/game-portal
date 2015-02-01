<h3><a href="/{{$urlSegment}}/articles">Quản lý bài viết</a> &raquo; Sửa bài viết: {{$item->title}}</h3>
<?php
$subCategoriesStr = '';
foreach ($subCategories as $aCat) {
    $subCategoriesStr = $subCategoriesStr.','.$aCat->id;
}
if(strlen($subCategoriesStr) > 0)
    $subCategoriesStr = substr($subCategoriesStr, 1);


$posCategoriesStr = '';
foreach ($posCategories as $aCat) {
    $posCategoriesStr = $posCategoriesStr.','.$aCat->id;
}
if(strlen($posCategoriesStr) > 0)
    $posCategoriesStr = substr($posCategoriesStr, 1);

?>
@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtTitle" , 'Tiêu đề (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtTitle" , Input::old( "title", $item->title ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tiêu đề' ) ) }}
    </div>
</div>

{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::textarea( "txtDescription" , Input::old( "description", $item->description ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập mô tả', 'style'=>'height:60px' ) ) }}
    </div>
</div>

{{-- Keyword --}}
<div class="form-group">
    {{ Form::label( "txtKeyword" , 'Từ khóa' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtKeyword" , Input::old( "keyword", $item->keyword ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập các từ khóa (cách nhau bởi dấu phẩy)' ) ) }}
    </div>
</div>


{{-- Tags --}}
<div class="form-group">
    {{ Form::label( "txtTags" , 'Tags' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtTags" , Input::old( "tags", $tagsCsv ) , array( 'data-role'=>'tagsinput',  'class'=>'form-control' , 'placeholder'=>'Nhập các tag' ) ) }}
    </div>
</div>


{{-- Content --}}
<div class="form-group">
    {{ Form::label( "txtContent" , 'Nội dung' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <div style="min-height: 250px ;border: 1px solid #CCC; background-color: #fff" id = "contentContainer">{{$item->content}}</div>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-2">
    </div>
    <div class="col-lg-5">
        <a href="javascript:getTopicImg();" class="btn btn-info btn-sm" >Chọn ảnh chủ đề</a>
        &nbsp;
        <a id="btnRemoveTopicImg" href="javascript:removeTopicImg();" class="btn btn-danger btn-sm" >Xóa ảnh</a>
        <div style="background: #F8F8F8; margin: 0 auto; padding: 5px">
            <img id = "imgTopic" style="max-width: 180px; max-height: 180px" src="{{{$upload?$upload->path:'' }}}">
        </div>
        <div>
            <span id="spnFileName" class="bg-info">{{{$upload?$upload->path:'' }}}</span>
        </div>
    </div>
    <div class="col-lg-5">
        <a href="javascript:getThumbImg();" class="btn btn-info btn-sm" >Chọn ảnh thumb</a>
        &nbsp;
        <a id="btnRemoveThumbImg" href="javascript:removeThumbImg();" class="btn btn-danger btn-sm" >Xóa ảnh</a>
        <div style="background: #F8F8F8; margin: 0 auto; padding: 5px">
            <img id = "imgThumb" style="max-width: 180px; max-height: 180px"  src="{{{$thumbUpload?$thumbUpload->path:'' }}}">
        </div>
        <div>
            <span id="spnThumbFileName" class="bg-info">{{{$thumbUpload?$thumbUpload->path:'' }}}</span>
        </div>
    </div>
</div>

{{-- Primary Category --}}
<div class="form-group">
    {{ Form::label( "cboPrimaryCategory" , 'Nhóm chính(news)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <select id="cboPrimaryCategory" class="selectpicker show-tick">
            <option value="ALL">Không chọn</option>
            <?php
            unset($allPrimaryCategory[0]);
            foreach ($allPrimaryCategory as $aCategory) {
                echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
            }

            ?>
        </select>
    </div>
</div>

{{-- Pos Category --}}
<div class="form-group">
    {{ Form::label( "cboPosCategory" , 'Nhóm vị trí tin(news)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <select id="cboPosCategory" class="selectpicker show-tick" multiple="multiple" title="Chọn 1 hoặc nhiều">

            <?php
            unset($allPosCategory[0]);
            foreach ($allPosCategory as $aCategory) {
                echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
            }

            ?>
        </select>
    </div>
</div>

{{-- Sub Category --}}
<div class="form-group">
    {{ Form::label( "cboSubCategory" , 'Nhóm tin bài game' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <select id="cboSubCategory" class="selectpicker show-tick" multiple="multiple" title="Chọn 1 hoặc nhiều">

            <?php
            unset($allSubCategory[0]);
            foreach ($allSubCategory as $aCategory) {
                echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
            }

            ?>
        </select>
    </div>
</div>


{{-- Games --}}
<div class="form-group">
    {{ Form::label( "cboGame" , 'Game' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboGame', $allGame, $gameArticles, array('class'=>'selectpicker show-tick', 'multiple', 'title'=>'Chọn 1 hoặc nhiều'))}}
    </div>
</div>

<div class="form-group">
{{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
<div class="col-lg-10" >
    <input type="checkbox" id="chkActive" {{$item->active==1?'checked':''}} style="margin: 10 0 0 0px" />
</div>
</div>



<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Lưu</a>


{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#txtTitle').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        {{$upload?"":"$('#btnRemoveThumbImg').hide();"}}
        {{$thumbUpload?"":"$('#btnRemoveThumbImg').hide();"}}
        $('#cboPrimaryCategory').selectpicker('val', '{{$primaryCategory?$primaryCategory->id:''}}');
        $('#cboSubCategory').selectpicker('val', [{{$subCategoriesStr}}]);
        $('#cboPosCategory').selectpicker('val', [{{$posCategoriesStr}}]);
    $('.selectpicker').selectpicker('refresh');

    })


    function getTopicImg(){

        try{
            var finder = new CKFinder();
            finder.basePath = '/lib/ckfinder/';	// The path for the installation of CKFinder (default = "/ckfinder/").
            finder.selectActionFunction = setTopicFile;
            finder.popup();
        }catch(err){

        }
    }

    function setTopicFile(fileUrl){
        $("#imgTopic").attr("src",  fileUrl );
        $("#btnRemoveTopicImg").show();
        $('#spnFileName').html(fileUrl);
    }

    function removeTopicImg(){
        $("#imgTopic").removeAttr("src");
        $("#btnRemoveTopicImg").hide();
        $('#spnFileName').html('');
    }


    function getThumbImg(){

        try{
            var finder = new CKFinder();
            finder.basePath = '/lib/ckfinder/';	// The path for the installation of CKFinder (default = "/ckfinder/").
            finder.selectActionFunction = setThumbFile;
            finder.popup();
        }catch(err){

        }
    }

    function setThumbFile(fileUrl){
        $("#imgThumb").attr("src",  fileUrl );
        $("#btnRemoveThumbImg").show();
        $('#spnThumbFileName').html(fileUrl);
    }

    function removeThumbImg(){
        $("#imgThumb").removeAttr("src");
        $("#btnRemoveThumbImg").hide();
        $('#spnThumbFileName').html('');
    }



    function save(){
        $.blockUI({ message: 'Vui lòng chờ' });
        title = $('#txtTitle').val();
        description = $('#txtDescription').val();
        keyword = $('#txtKeyword').val();
        tags = $('#txtTags').val();
        content = editor.getData();
        imageFile = $('#imgTopic').attr('src');
        imageThumbFile = $('#imgThumb').attr('src');
        primaryCategory = $('#cboPrimaryCategory').val();
        subCategories = $('#cboSubCategory').val();
        posCategories = $('#cboPosCategory').val();
        active = $('#chkActive').is(':checked')?1:0;
        games = $('#cboGame').val();
//        console.log(imageFile);

        $.post('/admin/articles/edit/{{$item->id}}',{
                title:title, content:content, imageFile:imageFile,imageThumbFile:imageThumbFile,
                primaryCategory:primaryCategory,posCategories:posCategories,
                subCategories:subCategories, active:active,tags:tags,
                games:games, description:description, keyword:keyword
            }
            ,function(result){
                $.unblockUI();
                $('#ajaxMsg').html(result.msg);

                if(result.success){

                }

            },'json');
    }

    var editor;
    tougleEditor();

    function tougleEditor(){
        if ( editor )
            editor.destroy();
        editor = CKEDITOR.replace( "contentContainer" ,{
            filebrowserBrowseUrl : '/lib/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '/lib/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : '/lib/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
            customConfig : '/lib/ckeditor/config.js',
            language: 'vi'
        });
    }
</script>
