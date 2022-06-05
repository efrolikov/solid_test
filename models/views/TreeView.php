<?php

namespace models\views;

use models\Tree;

/**
 * Represent html view ot the tree
 */
class TreeView
{
    /**
     * @return string
     */
    public static function getView() : string
    {
        $item = Tree::getRootItem();
        if ($item) return TreeItemView::getView($item, true);
        return '';
    }

}