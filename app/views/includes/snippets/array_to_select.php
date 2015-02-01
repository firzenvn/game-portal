
<?php

/*
  * ***Usage***
 * data: array(array("id"=>1, "name" = "first"), array("id"=>2, "name" = "second"))
 * <?php echo $this->partial("select_tag.php"
                         , array("data"=>array(), "id"=>"cboMessageType",
  "valCol"=>"id", "displayCol"=>"name" ,  "selected"=>1, "hasAll"=>true, "class"=>"my-class")) ?>
 */

?>
<?php
    if($class)
        $tmpClass = 'class="'.$class.'"';
    echo '<select id="'.$id.'" name="'.$id.' '.$tmpClass.' " >';
    $selected="";
    if($hasAll){
        echo '<option value="ALL">Tất cả</option>';
    }
    foreach ($data as $dataItem) {
        $selectedVal = $selected;
        if($selectedVal &&  $dataItem[$valCol] == $selectedVal)
            $selected = 'selected';
        else
            $selected = "";
        echo '<option value="'.$dataItem[$valCol].'" '.$selected.'   >'.$dataItem[$displayCol].'</option>';
    }
    echo '</select>';
    ?>
