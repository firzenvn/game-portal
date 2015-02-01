<h3><a href="/{{$urlSegment}}/games">Quản lý game</a> &raquo; Thêm mới Game</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "name" ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên' ) ) }}
    </div>
</div>


{{-- Content --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::textarea( "txtDescription" , Input::old( "description" ) , array( 'class'=>'form-control','rows'=>'3' , 'placeholder'=>'Nhập mô tả' ) ) }}
    </div>
</div>
<div class="form-group">
    <div class="col-lg-2">
    </div>
    <div class="col-lg-10">
        <a href="javascript:getTopicImg();" class="btn btn-info btn-sm" >Chọn ảnh chủ đề</a>
        &nbsp;
        <a id="btnRemoveTopicImg" href="javascript:removeTopicImg();" class="btn btn-danger btn-sm" >Xóa ảnh</a>
        <div style="background: #F8F8F8; margin: 0 auto; padding: 5px">
            <img id = "imgTopic" style="max-width: 180px; max-height: 180px">
        </div>
        <div>
            <span id="spnFileName" class="bg-info"></span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-2">
    </div>
    <div class="col-lg-10">
        <a href="javascript:getThumbImg();" class="btn btn-info btn-sm" >Chọn ảnh thumb</a>
        &nbsp;
        <a id="btnRemoveThumbImg" href="javascript:removeThumbImg();" class="btn btn-danger btn-sm" >Xóa ảnh</a>
        <div style="background: #F8F8F8; margin: 0 auto; padding: 5px">
            <img id = "imgThumb" style="max-width: 180px; max-height: 180px">
        </div>
        <div>
            <span id="spnThumbFileName" class="bg-info"></span>
        </div>
    </div>
</div>

{{-- Primary Category --}}
<div class="form-group">
    {{ Form::label( "cboPrimaryCategory" , 'Nhóm chính' , array( 'class'=>'col-lg-2 control-label' ) ) }}
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

{{-- Sub Category --}}
<div class="form-group">
    {{ Form::label( "cboSubCategory" , 'Nhóm phụ' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <select id="cboSubCategory" class="selectpicker show-tick" multiple="multiple" title="Chọn 1 hoặc nhiều">
            <option value="ALL">Không chọn</option>
            <?php
            unset($allSubCategory[0]);
            foreach ($allSubCategory as $aCategory) {
                echo '  <option value="'.$aCategory->id.'">'.$aCategory->name.'</option>';
            }

            ?>
        </select>
    </div>
</div>

<div class="form-group">
    {{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10" >
        <input type="checkbox" id="chkActive" checked style="margin: 10 0 0 0px" />
    </div>
</div>

<div class="form-group">
    {{Form::label("subdomain" , 'Subdomain' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('subdomain',null,array('class'=>'form-control', 'placeholder'=>'Nhập subdomain'))}}
    </div>
</div>

<div class="form-group">
    {{Form::label("tpl" , 'Template file' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('tpl',null,array('class'=>'form-control', 'placeholder'=>'Nhập tên file'))}}
    </div>
</div>

<div class="form-group">
    {{Form::label("exchange_rate" , 'Tỷ lệ tiền/vàng' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('exchange_rate',null,array('class'=>'form-control', 'placeholder'=>'Nhập tỷ lệ'))}}
    </div>
</div>


<div class="form-group">
    {{Form::label("unit" , 'Đơn vị vàng' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('unit',null,array('class'=>'form-control', 'placeholder'=>'Nhập tên coin trong game'))}}
    </div>
</div>


<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Lưu</a>


{{ Form::close() }}

<script language="javascript" type="text/javascript">
    $(function() {
        $('#txtName').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('#btnRemoveTopicImg').hide();
        $('#btnRemoveThumbImg').hide();
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
        name = $('#txtName').val();
        description = $('#txtDescription').val();
        imageFile = $('#imgTopic').attr('src');
        imageThumbFile = $('#imgThumb').attr('src');
        primaryCategory = $('#cboPrimaryCategory').val();
        subCategories = $('#cboSubCategory').val();
        active = $('#chkActive').is(':checked')?1:0;
        subdomain = $('#subdomain').val();
        tpl = $('#tpl').val();
        exchange_rate = $('#exchange_rate').val();
        unit = $('#unit').val();

        $.post('/admin/games/new',{
                name:name, description:description, imageFile:imageFile,
                imageThumbFile:imageThumbFile,
                primaryCategory:primaryCategory,
                subCategories:subCategories, active:active,
                subdomain:subdomain, tpl:tpl,
                exchange_rate:exchange_rate, unit:unit
            }
            ,function(result){
                $('#ajaxMsg').html(result.msg);
                if(result.success){
//                    window.location.assign('/admin/games/new')
                    refresh();
                }else{

                }

            },'json');
    }

    function refresh(){
        $('#txtName').val('');
        $('#txtDescription').redactor('set', '');
        $('#imgTopic').removeAttr('src');
        $('#btnRemoveTopicImg').hide();
        $('#spnFileName').html('');

        $('#imgThumb').removeAttr('src');
        $('#btnRemoveThumbImg').hide();
        $('#spnThumbFileName').html('');

        $('#cboPrimaryCategory').selectpicker('deselectAll');
        $('#cboSubCategory').selectpicker('deselectAll');

        $('#chkActive').attr('checked',true);
        $('#txtName').focus();
    }

</script>
