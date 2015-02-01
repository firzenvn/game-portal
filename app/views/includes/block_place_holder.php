<?php
if(!Auth::user()|| !Auth::user()->hasRole('admin'))
foreach ($blockArr as $aBlock) {
    if($aBlock->div_id == $containerId){
        echo '<div id="'.$containerId.'" block_type="'.$type.'" class="'.$aBlock->wrap_class.'" style="'.$aBlock->wrap_style.'">';
        $blockName = $aBlock->name;
        echo Pingpong\Widget\Facades\Widget::$blockName();
        echo '</div>';
        break;
    }
}
else{
    echo '<h2>clamama</h2>';
}
?>
