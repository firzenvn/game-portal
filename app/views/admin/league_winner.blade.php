<h3><a href="/{{$urlSegment}}/league">Quản lý giải đấu</a> &raquo; Người thắng cuộc giải đấu: {{$item->name}}</h3>

@include('includes.messaging')
<div id="ajaxMsg"></div>
<?php
foreach ($allLevelRange as $key => $aRange) {
    $content = '';
    foreach ($allWinners as $aWinner) {
        if($aWinner->level_range == $key)
            $content = $aWinner->content;
    }

    echo('<form class="form-horizontal form-top-margin">
<div class="bg-primary" style="padding: 10px; margin-bottom: 5px">
    '.$aRange.'&nbsp;&nbsp;&nbsp;<a style="color:#fff" href="javascript:tougleEditor(\''.$aRange.'\')">Edit</a>
</div>
<div class="form-group">
    <div class="col-lg-2 control-label">Nội dung </div>
    <div class="col-lg-10">
        <div style="min-height: 100px ;border: 1px solid #CCC; background-color: #fff" id = "contentContainer_'.$aRange.'">'.$content.'</div>
    </div>
</div>
</form>
<a class="btn btn-large btn-primary pull-right" href="javascript:save(\''.$aRange.'\')">Lưu</a>
<div class="clearfix"></div>
');
}

?>
<div class="clearfix"></div>

<script language="javascript" type="text/javascript">

    var editorArr = [];

    <?php
        foreach ($allLevelRange as $key => $aRange) {
            echo('editorArr["'.$key.'"] = "";');
        }

    ?>



    function save(level){
        $.blockUI({ message: 'Vui lòng chờ' });
        if(editorArr[level])
            content = editorArr[level].getData();
        else
            content = $('#contentContainer_'+level).html();

        $.post('/admin/league/winner/{{$item->id}}',{
                content:content,  level:level
            }
            ,function(result){

                $.unblockUI();
                $('#ajaxMsg').html(result.msg);
                if(result.success){

                }else{

                }

            },'json');
    }




    function tougleEditor(id){
        if (  editorArr[id] ){
            editorArr[id].destroy();
            editorArr[id] = null;
        }

        else
        editorArr[id] = CKEDITOR.replace( "contentContainer_"+id ,{
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
