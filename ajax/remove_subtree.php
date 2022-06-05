<?php
/**
 * ajax
 * Remove subtree
 * @var int $item_id
 */

$item_id = intval($_POST['item_id']);

include '../init.php';

use models\Tree;

$res = 0;
if ($item_id)
{
    $res = intval(Tree::removeSubtree($item_id));
}
echo $res;