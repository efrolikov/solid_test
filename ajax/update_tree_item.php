<?php
/**
 * ajax
 * Update tree item data
 * @var int $item_id id of the tree item to update name
 * @var string $name new name of item
 */

include '../init.php';

use models\Tree;

$item_id = intval($_POST['item_id']);
$name = $_POST['name'];

$res = 0;
if ($item_id)
{
    $res = intval(Tree::updateItemData($item_id, $name));
}
echo $res;