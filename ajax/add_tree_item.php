<?php
/**
 * ajax
 * Add tree item as a child to this tree item
 * @var int $item_id id of the tree item for add a child
 */

$item_id = intval($_POST['item_id']);

include '../init.php';

use models\Tree;
use models\views\TreeItemView;

if ($item_id)
{
    $tree_item = Tree::addItemChild($item_id);
} else {
    $tree_item = Tree::createRoot();
}

if ($tree_item)
{
    echo TreeItemView::getView($tree_item);
}
