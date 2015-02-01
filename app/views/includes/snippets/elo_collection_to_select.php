
<?php

/*
  * ***Usage***
 * data: Eloquent Collection
 * {{("includes.snippets.elo_collection_to_select"
                         , array("data"=>$collectionOfSmt, "id"=>"cboMessageType",
  "valCol"=>"id", "displayCol"=>"name" ,  "selected"=>1, "hasAll"=>true, "hasBlank"=>true, "class"=>"my-class")) }}
 */

if($class)
    $tmpClass = 'class="'.$class.'"';
echo '<select id="'.$id.'" name="'.$id.'" '.$tmpClass.'  >';
$selected="";
if(isset($hasBlank)){
    echo '<option value=""> </option>';
}
if(isset($hasAll)){
    echo '<option value="ALL">Tất cả</option>';
}
foreach ($data as $dataItem) {
    $selectedVal = $selected;
    if($selectedVal &&  $dataItem->$valCol == $selectedVal)
        $selected = 'selected';
    else
        $selected = "";
    echo '<option value="'.$dataItem->$valCol.'" '.$selected.'   >'.$dataItem->$displayCol.'</option>';
}
echo '</select>';
?>
