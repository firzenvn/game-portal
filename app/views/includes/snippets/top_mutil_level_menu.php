<div class="collapse navbar-left navbar-collapse">
<?php
if($menu_items){
    printMenu($menu_items);

}

function printMenu($parent_element, $level = 0) {

    // This is the array that contains all the list items.
    // We start an unordered list.

    if($level == 0)
        echo '<ul class="nav navbar-nav">';
    else
        echo '<ul class="dropdown-menu multi-level">';

    // We loop through each sub-array.
    foreach ($parent_element as $item_id => $list_item) {
        if(isset($list_item['url']))
            $url = $list_item['url'];
        else
            $url = '#';

        // We display the item.
        if (isset($list_item['items']) && $level == 0)
            echo '<li ><a class="dropdown-toggle" data-toggle="dropdown" href="'.$url.'">' . $list_item['name'] .'<b class="caret"></b>';
        if (isset($list_item['items']) && $level > 0)
            echo '<li class="dropdown-submenu"><a href="'.$url.'" class="dropdown-toggle" data-toggle="dropdown">'. $list_item['name'];
        if (!isset($list_item['items']))
            echo '<li><a href="'.$url.'">' . $list_item['name'];

        // Now we have to check for sub-items.
        if (isset($list_item['items'])) {
            $mylevel =  $level + 1;
            // If TRUE, then this function calls itself (recursive function)
            // in order to create the structure of the navigation menu.
            printMenu($list_item['items'],$mylevel);

        }

        // We complete the list item.
        echo '</a></li>';

    } // End of foreach().

    // We close the unordered list.
    echo '</ul>';


}

?>
</div>

