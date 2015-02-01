<style>
    .param-form-sample{margin: 5px}

</style>
<h3>Sửa Block: {{$item->name}}</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên block (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
    {{ Form::text( "txtName" , Input::old( "txtNam", $item->name ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên' ) ) }}
    </div>
</div>

{{-- Block group --}}
<div class="form-group">
    {{ Form::label( "cboBlockGroup" , 'Loại ' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <select id="cboBlockGroup" class="selectpicker show-tick" ">
        <option value="html" @if(isset($item->html)) selected @endif>Html</option>
        <option value="view" @if(isset($item->view_file)) selected @endif>View</option>
        </select>
    </div>
</div>
{{-- Block type --}}
<div class="form-group">
    {{ Form::label( "cboBlockType" , 'Nhóm ' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{Form::select('cboBlockType', $allBlockType, $item->type, array('class'=>'selectpicker show-tick'))}}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "chkActive" , 'Active' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10" >
        {{ Form::checkbox('chkActive', null, ($item->active==1)?true:'', array('style'=>'margin: 10 0 0 0px')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "txtWrapClass" , 'Wrap class' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtWrapClass" , Input::old( "wrap_class", $item->wrap_class ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập class của thẻ div cha chứa block' ) ) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label( "txtWrapStyle" , 'Wrap css' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtWrapStyle" , Input::old( "wrap_style", $item->wrap_style ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập css của thẻ div cha chứa block' ) ) }}
    </div>
</div>


{{-- Html --}}
<div class="form-group" id="htmlCtn">
    {{ Form::label( "txtContent" , 'Nội dung' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        <div style="min-height: 250px ;border: 1px solid #CCC; background-color: #fff" id = "contentContainer">{{$item->html}}</div>
    </div>
</div>

{{-- View --}}
<div id="viewCtn">
    <div class="form-group" >
        {{ Form::label( "cboViewFile" , 'View file' , array( 'class'=>'col-lg-2 control-label' ) ) }}
        <div class="col-lg-10">
            {{Form::select('cboViewFile',$allBlockFile,Input::old( "view_file", $item->view_file ),array('class'=>'selectpicker show-tick'))}}
        </div>
    </div>
    <div class="form-group" >
        <div class="col-lg-2"> </div>
        <div class="col-lg-10 bg-info">
            <div class="text-info" style="padding:7px; font-size: 1em; ">
                <span><strong>Tham số</strong></span>&nbsp;&nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="javascript:addParameter()"><span class="glyphicon glyphicon-plus"></span></a>
            </div>
        </div>
        <div class="col-lg-2">
        </div>
        <div class="col-lg-10 bg-info" id="paramCtn">

        </div>
    </div>

</div>







<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Cập nhật</a>


{{ Form::close() }}


<script language="javascript" type="text/javascript">
    var counter = 0;
    var editMode = false;
    var paramShow = '';
    var params = <?php if($item->params) echo $item->params; else echo '{}'?>

    $(function(){
        for(var k in params)
        {
            counter++;
            if(params.hasOwnProperty(k))
            {
                paramShow = paramShow+'<div class="param-form-sample" id="formSample'+counter+'">'+
                    '<div class="param-label-ctn" style="display: none">'+
                    '<div class="col-lg-3">Key (*)</div><div class="col-lg-3">Val(*)</div><div class="clearfix"></div>'+
                    '</div>'+
                    '<div class="param-edit-val-ctn" style="display: none">'+
                    '<div class="col-lg-3"><input class="txtKeyParam" /></div><div class="col-lg-3"><input class="txtValParam"/></div>'+
                    '<div class="col-lg-2">'+
                    '<a class="btn btn-sm btn-danger" href="javascript:saveParam('+counter+')"><span class="glyphicon glyphicon-save"></span></a>'+
                    '</div>'+
                    '<div class="clearfix"></div>'+
                    '</div>'+
                    '<div class="param-show-val-ctn" >'+
                    '<div class="col-lg-3 display-key">'+k+'</div><div class="col-lg-3 display-val">'+params[k]+'</div>'+
                    '<div class="col-lg-2">'+
                    '<a class="btn btn-sm btn-danger" href="javascript:editParam('+counter+')"><span class="glyphicon glyphicon-edit"></span></a>'+
                    '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:deleteParam('+counter+')"><span class="glyphicon glyphicon-minus"></span></a>'+
                    '</div>'+
                    '<div class="clearfix"></div>'+
                    '</div>'+
                    '</div>';
            }
        }
        $('#paramCtn').append(paramShow);
    });

    function addParameter(){
        if(editMode) return;
        counter++;
        $('#paramCtn').append('<div class="param-form-sample" id="formSample'+counter+'">'+
            '<div class="param-label-ctn">'+
            '<div class="col-lg-3">Key (*)</div><div class="col-lg-3">Val(*)</div><div class="clearfix"></div>'+
            '</div>'+
            '<div class="param-edit-val-ctn">'+
            '<div class="col-lg-3"><input class="txtKeyParam" /></div><div class="col-lg-3"><input class="txtValParam"/></div>'+
            '<div class="col-lg-2">'+
            '<a class="btn btn-sm btn-danger" href="javascript:saveParam('+counter+')"><span class="glyphicon glyphicon-save"></span></a>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '</div>'+
            '<div class="param-show-val-ctn" >'+
            '<div class="col-lg-3 display-key"></div><div class="col-lg-3 display-val"></div>'+
            '<div class="col-lg-2">'+
            '<a class="btn btn-sm btn-danger" href="javascript:editParam('+counter+')"><span class="glyphicon glyphicon-edit"></span></a>'+
            '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:deleteParam('+counter+')"><span class="glyphicon glyphicon-minus"></span></a>'+
            '</div>'+
            '<div class="clearfix"></div>'+
            '</div>'+
            '</div>');
        $('#formSample'+counter +' .param-show-val-ctn').hide();
        editMode = true;
    }

    function saveParam(idx){
        $('#formSample'+idx +' .param-label-ctn').hide();
        $('#formSample'+idx +' .param-edit-val-ctn').hide();
        $('#formSample'+idx +' .param-show-val-ctn .display-key').html($('#formSample'+idx +' .param-edit-val-ctn input.txtKeyParam').val());
        $('#formSample'+idx +' .param-show-val-ctn .display-val').html($('#formSample'+idx +' .param-edit-val-ctn input.txtValParam').val());
        $('#formSample'+idx +' .param-show-val-ctn').show();
        editMode = false;
    }

    function editParam(idx){
        editMode = true;
        $('#formSample'+idx +' .param-label-ctn').show();
        $('#formSample'+idx +' .param-edit-val-ctn').show();
        $('#formSample'+idx +' .param-edit-val-ctn input.txtKeyParam').val($('#formSample'+idx +' .param-show-val-ctn .display-key').html());
        $('#formSample'+idx +' .param-edit-val-ctn input.txtValParam').val($('#formSample'+idx +' .param-show-val-ctn .display-val').html());
        $('#formSample'+idx +' .param-show-val-ctn').hide();
    }

    function deleteParam(idx){
        if(editMode) return;
        $('#formSample'+idx).remove();
        editMode = false;
    }


    $(function() {
        $('#txtName').focus();
    });

    function loadBlockEditor(){
        refresh();
        val = $('#cboBlockGroup').val();
        if(val == 'html'){
            $('#htmlCtn').show();
            $('#viewCtn').hide();
        }else{
            $('#htmlCtn').hide();
            $('#viewCtn').show();

        }
    }
    loadBlockEditor();
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('#cboBlockGroup').on('change', function(){
            loadBlockEditor();
        });


    })

    function save(){
        name = $('#txtName').val();
        wrapClass = $('#txtWrapClass').val();
        wrapStyle = $('#txtWrapStyle').val();
        group = $('#cboBlockGroup').val();
        type = $('#cboBlockType').val();
        content = editor.getData();
        active = $('#chkActive').is(':checked')?1:0;
        viewFile = $('#cboViewFile').val();

        params = [];
        $('#paramCtn .param-form-sample').each(function(){
            key = $(this).find('.display-key').html();
            val = $(this).find('.display-val').html();
            params.push(key+'$$$'+val)
        })

//        alert(name+" "+wrapClass+" "+ wrapStyle+" "+group+" "+type+" "+content+" "+active+viewFile);

        $.post('/admin/blocks/edit/{{$item->id}}',{
                name:name, wrapClass:wrapClass, wrapStyle:wrapStyle,
                group:group,type:type,content:content,
                active:active, viewFile:viewFile,params:params
            }
            ,function(result){
                $('#ajaxMsg').html(result.msg);
                if(result.success){

                }else{

                }

            },'json');
    }

    function refresh(){
        tougleEditor()
        $('#txtName').focus();
    }

    var editor;
    //    tougleEditor();

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
            customConfig : '/lib/ckeditor/my_config.js',
            language: 'vi'
        });
    }

</script>
