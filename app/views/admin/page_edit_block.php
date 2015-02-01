<h4 class="bg-info"><?php echo $pageItem->route?></h4>
<div id="ctn">

</div>
<script>
    $(document).ready(function(){
        $.post('/admin/pages/load-page-content/<?php echo $pageItem->id?>',{}
            ,function(result){
                $('#ctn').html(result);
            });
    })

</script>