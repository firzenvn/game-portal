<style>
    .imageCtn{
        padding: 5px;
        border: 1px solid #ccc;
        width: 200px;
        height: 240px;
        margin: 10px;
        float: left;
        position: relative;
    }


    .imageCtn img{
        max-width: 180px;
        max-height: 180px;
    }

    .imageCtn span{
        padding: 3px;
        background-color: #eed3d7;
    }

    .imageCtn a{
        visibility: hidden;
        position: absolute;
        top: 10px;
        left: 150px;
    }
    .imageCtn:hover a{
        visibility: visible;
    }

    .imageCtn:hover{
        background-color: rgba(0,0,0,0.75);
        transition: background .2s linear, color .2s linear;
        -moz-transition: background .2s linear, color .2s linear;
        -webkit-transition: background .2s linear, color .2s linear;
        -o-transition: background .2s linear, color .2s linear;
    }


</style>
<?php
?>

<div class="form-group">
    <div class="col-lg-10">
        <h3>Quản lý game gallery</h3>
    </div>
</div>
<div style="clear: both"></div>

{{-- The error / success messaging partial --}}
@include('includes.messaging')
{{Form::open(array('url'=>'/'.$urlSegment.'/galleries', 'method'=>'get', 'role'=>'form'))}}
<div class="form-group">
    <div class="row" style="margin: 10px; padding: 10px; background: #efefef">
        <div class="col-xs-1">
        </div>
        {{ Form::label( "cboGame" , 'Chọn game' , array( 'class'=>'col-xs-2 control-label' ) ) }}
        <div class="col-xs-8">

            <select id="cboGame" class="selectpicker show-tick">
                <?php
                foreach ($allGame as $aGame) {
                    echo '  <option value="'.$aGame->id.'">'.$aGame->name.'</option>';
                }

                ?>
            </select>
        </div>

    </div>
</div>
{{Form::close()}}

<div class="dis_admin_ctn">
    <?php
    foreach ($subGalleriesCat as $aCat) {
        echo '<div class="panel panel-primary" id="galleryCat'.$aCat->id.'">
        <div class="panel-heading">
            <span class="panel-title">'.$aCat->name.'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addImage('.$aCat->id.')" class="btn btn-default btn-sm">Thêm mới</a>
            <span id="spnLoading_'.$aCat->id.'" style=" float:right" ><img src="/images/ajax_loading.gif"></span>
        </div>
        <div class="panel-body">
        </div>
    </div>';
    }
    ?>
    <div class="panel panel-primary" id="galleryVideo">
        <div class="panel-heading">
            <span class="panel-title">Video</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addVideo()" class="btn btn-default btn-sm">Thêm mới</a>
            <span id="spnLoadingVideo" style=" float:right" ><img src="/images/ajax_loading.gif"></span>
        </div>

            <table class="table" id="tblVideo">
                <th>Id</th><th>Mã Youtobe</th>

            </table>

    </div>
</div>


<div id="wndAddVideo"></div>
<script type="text/x-kendo-template" id="addVideoTemplate">
    <div id="add-video-container">
        <dl>


            <dt style="padding: 5px"><div style="width: 100px;float:left"> Mã Youtobe(*):</div>
            <input  id='txtGameVideoId'  name="txtGameVideoId" type="hidden"    value='#= id #'>
            <input  style="width:200px" class="k-textbox"  required  id='txtCode' value='#= code #'>


            </dt>

        </dl>
        <div style="padding: 10px 0px" >
            <a id="btnSaveVideo" class="k-button k-button-icontext k-grid-update" >
                <span class="k-icon k-update"></span>
                Lưu
            </a>
            <a id="btnCloseVideo" class="k-button k-button-icontext k-grid-cancel">
                <span class="k-icon k-cancel"></span>
                Cancel
            </a>
        </div>
    </div>
</script>

<script>
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
        $('#cboGame').selectpicker('val', '{{$firstGame?$firstGame->id:''}}');
        $('.selectpicker').selectpicker('refresh');
        <?php
          foreach ($subGalleriesCat as $aCat) {
          echo 'loadGallery('.$aCat->id.');';
          }

         ?>
        loadVideo();
        $('#cboGame').change(function(){
            loadVideo();
            <?php
         foreach ($subGalleriesCat as $aCat) {
         echo 'loadGallery('.$aCat->id.');';
         }

        ?>
        })
    })

    function loadVideo(){
        gameId = $('#cboGame').val();
        $('#spnLoadingVideo').show();
        $("#tblVideo").find("tr:gt(0)").remove();
        $.post('<?php echo '/admin/galleries/load-video'  ?>',{gameId:gameId}
            ,function(result){
                $('#spnLoadingVideo').hide();
                if (result.success){
                    allVideo = result.data
                    for (i = 0; i < allVideo.length; i++) {
                        aVideo = allVideo[i];
                        $('#tblVideo tr:last').after('<tr id=""><td>'+aVideo.id+'</td><td>'+aVideo.youtobe_code
                            +'</td><td><a class="btn btn-primary btn-sm" href="javascript:editVideo('+aVideo.id+',\''+aVideo.youtobe_code+'\')">Sửa</a>' +
                            '&nbsp;<a class="btn btn-primary btn-sm" href="javascript:deleteVideo('+aVideo.id+')">Xóa</a></td>'+
                            '</tr>');
                    }
                }else{
                    alert(result.msg)
                }
            },'json');
    }

    var addVideoTemplate = kendo.template($("#addVideoTemplate").html());
    var wndAddVideo = $("#wndAddVideo")
        .kendoWindow({
            title: "Thêm video",
            modal: true,
            visible: false,
            resizable: false,
            width: 400,
            activate: onActivateAddVideo
        }).data("kendoWindow");

    function onActivateAddVideo(){
        $("#txtCode").focus();
        $('#btnSaveVideo').click(function(){
            saveVideo();
        });

        $('#btnCloseVideo').click(function(){
            $("#wndAddVideo").data("kendoWindow").close();
        });
    }
    $("#wndAddVideo").keypress(function(event){
        //if the key press is ESC
        if (event.keyCode === 27) {
            //close the KendoUI window
            $("#wndAddVideo").data("kendoWindow").close();
        }

        if(event.keyCode === 13) {
            saveVideo();
        }
    });

    $('#btnCloseVideo').click(function(){
        $("#wndAddVideo").data("kendoWindow").close();
    });

    function saveVideo(){
        videoId = $('#txtGameVideoId').val();
        code = $('#txtCode').val();
        gameId = $('#cboGame').val();
        $.blockUI({ message: 'Vui lòng chờ' });
        $("#wndAddVideo").data("kendoWindow").close();
        $.post('<?php echo '/admin/galleries/save-video'  ?>',
            {videoId:videoId,code:code,gameId:gameId }
            ,function(result){
                $.unblockUI();
                if (result.success){
                    loadVideo();
                }else{
                    alert(result.msg);
                }
            },'json');
    }

    function addVideo(){
        wndAddVideo.content(addVideoTemplate({"code":"","id":""}));
        wndAddVideo.center().open();
    }

    function editVideo(videoId, code){
        wndAddVideo.content(addVideoTemplate({"code":code,"id":videoId}));
        wndAddVideo.center().open();
    }

    function deleteVideo(videoId){
        bootbox.confirm("Bạn chắc chắn muốn xóa?", function(result) {
            if(result){
                $.blockUI({ message: 'Vui lòng chờ' });
                $.post('<?php echo '/admin/galleries/delete-video'  ?>',{videoId:videoId}
                    ,function(result){
                        $.unblockUI();
                        if (result.success){
                            loadVideo();
                        }else{
                            alert(result.msg)
                        }
                    },'json');
            }
        });
    }

    function loadGallery(catId){
        gameId = $('#cboGame').val();
        $('#spnLoading_'+catId).show();
        $('#galleryCat'+catId + ' div.panel-body').html('');
        $.post('<?php echo '/admin/galleries/load'  ?>',{gameId:gameId,catId:catId}
            ,function(result){
                $('#spnLoading_'+catId).hide();
                if (result.success){
                    allImage = result.data
                    for (i = 0; i < allImage.length; i++) {
                        aGallery = allImage[i];
                        $('#galleryCat'+catId + ' div.panel-body').append(
                            '<div class="imageCtn" id="imageCtn_'+aGallery.id+'">' +
                                '<img src="'+aGallery.path +'"/><br>' +
                                '<span>'+aGallery.path+'</span>'+
                                '<a class="btn btn-primary btn-sm" href="javascript:deleteImage('+aGallery.id+','+catId+');">Xóa</a>'+
                             '</div>'
                        )
                    }
                }else{
                    alert(result.msg)
                }
            },'json');

    }

    var currentCategory;
    function setGalleryFile( fileUrl , data , allFiles )
    {
        $.blockUI({ message: 'Vui lòng chờ' });
        tmpArr = [];
        for (obj in allFiles) {
            tmpArr.push(allFiles[obj].url);
        }
        gameId = $('#cboGame').val();
        $.post('<?php echo '/admin/galleries/add'  ?>',{gameId:gameId, images:tmpArr, catId:currentCategory}
            ,function(result){
                $.unblockUI();
                if (result.success){
                    allImage = result.data
                    for (i = 0; i < allImage.length; i++) {
                         aGallery = allImage[i];
                        $('#galleryCat'+currentCategory + ' div.panel-body').append(
                            '<div class="imageCtn" id="imageCtn_'+aGallery.id+'">' +
                                '<img src="'+aGallery.path +'"/><br>' +
                                '<span>'+aGallery.path+'</span>'+
                                '<a class="btn btn-primary btn-sm" href="javascript:deleteImage('+aGallery.id+','+currentCategory+');">Xóa</a>'+
                                '</div>')
                    }

                }else{
                    alert(result.msg)
                }
            },'json');
    }


    function addImage(catId){
        try{
            currentCategory = catId;
            var finder = new CKFinder();
            finder.basePath = '/lib/ckfinder/';
            finder.selectActionFunction = setGalleryFile;
            finder.popup();
        }catch(err){

        }
    }

    function deleteImage(galleryId, catId){
        bootbox.confirm("Bạn chắc chắn muốn xóa?", function(result) {
            if(result){
                $.blockUI({ message: 'Vui lòng chờ' });
                $.post('<?php echo '/admin/galleries/delete-gallery'  ?>',{galleryId:galleryId}
                    ,function(result){
                        $.unblockUI();
                        if (result.success){

                            $('#imageCtn_'+galleryId).remove();
                        }else{
                            alert(result.msg)
                        }
                    },'json');
            }
        });
    }

</script>