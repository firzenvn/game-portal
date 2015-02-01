<h3><a href="/{{$urlSegment}}/games">Quản lý game</a> &raquo; Sửa game: {{$item->name}}</h3>
<?php
$subCategoriesStr = '';
foreach ($subCategories as $aCat) {
    $subCategoriesStr = $subCategoriesStr.','.$aCat->id;
}
if(strlen($subCategoriesStr) > 0)
$subCategoriesStr = substr($subCategoriesStr, 1);

$allServers = $item->servers?$item->servers:array();

?>
@include('includes.messaging')
<div id="ajaxMsg"></div>
{{ Form::open( array( 'url'=>'#' , 'class'=>'form-horizontal form-top-margin' , 'role'=>'form' ) ) }}
{{-- Title --}}
<div class="form-group">
    {{ Form::label( "txtName" , 'Tên (*)' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::text( "txtName" , Input::old( "title", $item->name ) , array( 'class'=>'form-control' , 'placeholder'=>'Nhập tên' ) ) }}
    </div>
</div>


{{-- Description --}}
<div class="form-group">
    {{ Form::label( "txtDescription" , 'Mô tả' , array( 'class'=>'col-lg-2 control-label' ) ) }}
    <div class="col-lg-10">
        {{ Form::textarea( "txtDescription" , Input::old( "description" , $item->description) , array( 'class'=>'form-control','rows'=>'3' , 'placeholder'=>'Nhập mô tả' ) ) }}
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
            <img id = "imgTopic" style="max-width: 180px; max-height: 180px" src="{{{$upload?$upload->path:'' }}}">
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
            <img id = "imgThumb" style="max-width: 180px; max-height: 180px" src="{{{$uploadThumb?$uploadThumb->path:'' }}}">
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
        <input type="checkbox" id="chkActive" {{$item->active==1?'checked':''}} style="margin: 10 0 0 0px" />
    </div>
</div>

<div class="form-group">
    {{Form::label("subdomain" , 'Subdomain' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('subdomain',Input::old( "title", $item->subdomain ),array('class'=>'form-control', 'placeholder'=>'Nhập subdomain'))}}
    </div>
</div>

<div class="form-group">
    {{Form::label("tpl" , 'Template file' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('tpl',Input::old( "title", $item->tpl ),array('class'=>'form-control', 'placeholder'=>'Nhập tên file'))}}
    </div>
</div>

<div class="form-group">
    {{Form::label("exchange_rate" , 'Tỷ lệ tiền/vàng' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('exchange_rate',Input::old( "title", $item->exchange_rate ),array('class'=>'form-control', 'placeholder'=>'Nhập tỷ lệ'))}}
    </div>
</div>


<div class="form-group">
    {{Form::label("unit" , 'Đơn vị vàng' , array('class'=>'col-lg-2 control-label'))}}
    <div class="col-lg-6">
        {{Form::text('unit',Input::old( "title", $item->unit ),array('class'=>'form-control', 'placeholder'=>'Nhập tên coin trong game'))}}
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 bg-info" >
        <div class="text-info" style="padding:7px; font-size: 1.2em; ">
            <span><strong>Danh sách server</strong></span>&nbsp;&nbsp;&nbsp;
            <a class="btn btn-sm btn-danger" href="javascript:addServer()"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 bg-info" >

        <table class="table table-condensed" id="tblServer">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Thứ tự</th>
                <th>Ip</th>
                <th>Url</th>
                <th>Key</th>
                <th>Server id</th>
                <th>Dùng cho</th>
                <th>Active</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($allServers as $aServer) {

                echo '<tr id="serverRow'.$aServer->id.'">
                            <td>'.$aServer->id.'</td>
                            <td>'.$aServer->name.'</td>
                            <td>'.$aServer->order_number.'</td>
                            <td>'. $aServer->ip.'</td>
                            <td>'. $aServer->url.'</td>
                            <td>'. $aServer->secret_key.'</td>
                            <td>'. $aServer->sid.'</td>
                            <td>'. $aServer->apply_for.'</td>
                            <td>'. $aServer->active.'</td>
                            <td>
                                <div class="pull-right">
                                    <a href="javascript:editServer('.$aServer->id.')" class="btn btn-sm btn-primary">Sửa</a>
                                    <a href="javascript:deleteServer('.$aServer->id.')" class="btn btn-sm btn-danger">Xóa</a>
                                </div>
                            </td>
                       </tr>';
            }

            ?>
            </tbody>
        </table>
    </div>
</div>

<a class="btn btn-large btn-primary pull-right" href="javascript:save()">Lưu</a>


{{ Form::close() }}

<div id="wndAddServer"></div>
<script type="text/x-kendo-template" id="addServerTemplate">
    <div id="add-server-container">
        <dl>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Tên(*):</div>
            <input  id='txtServerId'  name="txtServerId" type="hidden" value='#= id #'>
            <input  id='txtServerName'  name="txtName" class="k-textbox"  required  value='#= name #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px;float:left"> Thứ tự(*):</div>
            <input  style="width:400px" class="k-textbox"  required  id='txtServerOrderNumber' value='#= order_number #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px;float:left"> Ip(*):</div>
            <input  style="width:400px" class="k-textbox"  required  id='txtServerIp' value='#= ip #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Url (*):</div>
            <input style="width:400px"  class="k-textbox"  required  id='txtServerUrl' value='#= url #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Key (*):</div>
            <input style="width:400px"  class="k-textbox"  required  id='txtServerKey' value='#= key #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Server id (*):</div>
            <input style="width:400px"  class="k-textbox"  required  id='txtGameServerId' value='#= sid #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Dùng cho (*):</div>
            <input style="width:400px"  class="k-textbox"  required  id='txtApplyFor' value='#= apply_for #'>
            </dt>
            <dt style="padding: 5px"><div style="width: 70px; float:left"> Active:</div>
            <input type="checkbox" id='chkServerActive' # if (serverActive == 1){ # checked # } #  />
            </dt>
            <dt style="padding: 5px">
                <span style="color:red" id="spnStatus"></span>
            </dt>
        </dl>
        <div style="padding: 10px 0px" >
            <a id="btnSaveServer" class="k-button k-button-icontext k-grid-update" >
                <span class="k-icon k-update"></span>
                Lưu
            </a>
            <a id="btnClose" class="k-button k-button-icontext k-grid-cancel">
                <span class="k-icon k-cancel"></span>
                Cancel
            </a>
        </div>
    </div>
</script>


<script language="javascript" type="text/javascript">
    $(function() {
        $('#txtName').focus();
    });
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        {{$upload?"":"$('#btnRemoveTopicImg').hide();"}}
        {{$uploadThumb?"":"$('#btnRemoveThumbImg').hide();"}}
        $('#cboPrimaryCategory').selectpicker('val', '{{$primaryCategory?$primaryCategory->id:''}}');
        $('#cboSubCategory').selectpicker('val', [{{$subCategoriesStr}}]);
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

        $.post('/admin/games/edit/{{$item->id}}',{
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
//                    refresh();


                }

            },'json');
    }

    var addServerTemplate = kendo.template($("#addServerTemplate").html());
    var wndAddServer = $("#wndAddServer")
        .kendoWindow({
            title: "Thêm server",
            modal: true,
            visible: false,
            resizable: false,
            width: 500,
            activate: onActivateAddServer

        }).data("kendoWindow");

    $("#wndAddServer").keypress(function(event){
        //if the key press is ESC
        if (event.keyCode === 27) {
            //close the KendoUI window
            $("#wndAddServer").data("kendoWindow").close();
        }

        if(event.keyCode === 13) {
            saveServer();
        }
    });


    function onActivateAddServer(){
        $("#txtServerName").focus();
        $('#btnSaveServer').click(function(){
            saveServer();
        });

        $('#btnClose').click(function(){
            closeServerWindow();
        });
    }


    function saveServer(){
        if(!validateServerSave())
            return;
        id = $("#txtServerId").val();
        name = $("#txtServerName").val();
        order_number = $('#txtServerOrderNumber').val();
        ip = $("#txtServerIp").val();
        url = $("#txtServerUrl").val();
        key = $("#txtServerKey").val();
        serverId = $("#txtGameServerId").val();
        apply_for = $("#txtApplyFor").val();
        active = $("#chkServerActive").is(':checked')?1:0;

        if(!id){
            $.post('<?php echo '/admin/games/add-server'  ?>',
                {name:name,order_number:order_number,ip:ip,url:url, key:key,sid:serverId,apply_for:apply_for,active:active, game_id:<?php echo $item->id?>}
                ,function(result){
                    if(result.success){
                        closeServerWindow();
                        $("#spnStatus").html('');
                        $('#tblServer tr:last').after(result.data);
                    }else{
                        $('#spnStatus').html(result.msg);
                    }

                },'json');
        }else{
            $.post('<?php echo '/admin/games/edit-server'  ?>',
                {id:id, name:name,order_number:order_number,ip:ip,url:url, key:key,sid:serverId,apply_for:apply_for,active:active, game_id:<?php echo $item->id?>}
                ,function(result){
                    if(result.success){
                        closeServerWindow();
                        $("#spnStatus").html('');
//                        $('#tblServer tr:last').after(result.data);
                        $('#serverRow' + id +' td:eq(1)').html(result.data.name);
                        $('#serverRow' + id +' td:eq(2)').html(result.data.order_number);
                        $('#serverRow' + id +' td:eq(3)').html(result.data.ip);
                        $('#serverRow' + id +' td:eq(4)').html(result.data.url);
                        $('#serverRow' + id +' td:eq(5)').html(result.data.secret_key);
                        $('#serverRow' + id +' td:eq(6)').html(result.data.sid);
                        $('#serverRow' + id +' td:eq(7)').html(result.data.apply_for);
                        $('#serverRow' + id +' td:eq(8)').html(result.data.active);

                    }else{
                        $('#spnStatus').html(result.msg);
                    }

                },'json');
        }

    }

    function validateServerSave(){
        if(!$("#txtServerName").val()){
            $("#spnStatus").html('Vui lòng nhập Tên.');
            $("#txtServerName").focus();
            return false;
        }

        if(!$("#txtServerOrderNumber").val()){
            $("#spnStatus").html('Vui lòng nhập Thứ tự.');
            $("#txtServerIp").focus();
            return false;
        }

        if(!$("#txtServerIp").val()){
            $("#spnStatus").html('Vui lòng nhập Ip.');
            $("#txtServerIp").focus();
            return false;
        }

        if(!$("#txtServerUrl").val()){
            $("#spnStatus").html('Vui lòng nhập Url.');
            $("#txtServerUrl").focus();
            return false;
        }

        if(!$("#txtServerKey").val()){
            $("#spnStatus").html('Vui lòng nhập Key.');
            $("#txtServerKey").focus();
            return false;
        }
        if(!$("#txtGameServerId").val()){
            $("#spnStatus").html('Vui lòng nhập Server id.');
            $("#txtGameServerId").focus();
            return false;
        }
        if(!$("#txtApplyFor").val()){
            $("#spnStatus").html('Vui lòng nhập nơi dùng.');
            $("#txtApplyFor").focus();
            return false;
        }



        return true;
    }

    function closeServerWindow(){
        wndAddServer.close();
    }

    function addServer(){
        wndAddServer.content(addServerTemplate({
            "id":'',"name":'',"order_number":'',"ip":'',"url":"","key":"", "sid":"", "apply_for":"",'serverActive': 1}));
        wndAddServer.center().open();
    }

    function deleteServer(id){
        bootbox.confirm("Bạn chắc chắn muỗn xóa?", function(data) {
            if(data){

                $.post('/admin/games/delete-server',
                    {id:id}
                    ,function(result){
                        if(result.success){
                            $('#serverRow'+id).remove();
                        }else{
                            $('#ajaxMsg').html(result.msg);
                        }

                    },'json');
            }
        });
    }

    function editServer(id){

        name = $('#serverRow' + id +' td:eq(1)').html();
        order_number = $('#serverRow' + id +' td:eq(2)').html();
        ip = $('#serverRow' + id +' td:eq(3)').html();
        url = $('#serverRow' + id +' td:eq(4)').html();
        key = $('#serverRow' + id +' td:eq(5)').html();
        sid = $('#serverRow' + id +' td:eq(6)').html();
        apply_for = $('#serverRow' + id +' td:eq(7)').html();
        active = $('#serverRow' + id +' td:eq(8)').html();

        wndAddServer.content(addServerTemplate({
            "id":id,"name":name,"order_number":order_number,"ip":ip,"url":url,"key":key,'sid':sid ,"apply_for":apply_for, 'serverActive': active}));
        wnd = $("#wndAddServer").data("kendoWindow");
        wnd.title("Sửa server: " + name);

        wndAddServer.center().open();
    }

</script>
