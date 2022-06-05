<?php

namespace models\views;

use models\TreeItem;
use models\Helper;

/**
 * Represent html view of a tree item
 */
class TreeItemView
{
    /**
     * @param TreeItem $tree_item
     * @param bool $with_children
     * @return string
     */
    public static function getView(TreeItem $tree_item, bool $with_children = true) : string
    {
        $children_str = '';
        if ($with_children)
        {
            $children = $tree_item->getChildren();
            foreach ($children as $child)
            {
                $children_str .= self::getView($child, $with_children);
            }
        }

        $arrow = '<span class="arrow opened '.($children_str? '': 'hidden').'"><img src="img/arrow.png"></span>';

        return <<<STR
        <li class="tree_item" item_id="{$tree_item->id}">
            {$arrow}
            <span class="item_name">{$tree_item->name}</span><span> (id: {$tree_item->id})</span>
            <span class="item_btn add_child">+</span>
            <span class="item_btn remove_child">-</span>
            <ul class="subtree">{$children_str}</ul>
        </li>
STR;
    }
}