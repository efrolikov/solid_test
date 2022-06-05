<?php

namespace models;

/**
 * Class to work with whole tree
 */
class Tree
{
    /**
     * @return TreeItem|null
     */
    public static function createRoot() : ?TreeItem
    {
        if (self::getRootItem()) return null; // root already exist

        $tree_item = new TreeItem();
        $tree_item->name = 'root';
        $tree_item->parent_id = 0;
        if ($tree_item->saveToDb()) return $tree_item;
        return null;
    }

    /**
     * @param int $item_id
     * @param string $name
     * @return TreeItem|null
     */
    public static function addItemChild(int $item_id, string $name='item') : ?TreeItem
    {
        $item = TreeItem::loadFromDb($item_id);
        if ($item)
        {
            return $item->createChild($name);
        }
        return null;
    }

    /**
     * @param int $item_id tree item from which we delete all its subtree
     * @return bool
     */
    public static function removeSubtree(int $item_id) : bool
    {
        $item = TreeItem::loadFromDb($item_id);
        if ($item)
        {
            try {
                App::$db->beginTransaction();
                $item->remove();
                App::$db->commit();
                return true;
            } catch(\Exception $e) {
                App::$db->rollBack();
                return false;
            }
        }
        return false;
    }

    /**
     * @param int $item_id
     * @param string $name
     * @return bool
     */
    public static function updateItemData(int $item_id, string $name) : bool
    {
        $item = TreeItem::loadFromDb($item_id);
        if ($item)
        {
            $item->name = $name;
            if ($item->saveToDb()) return true;
        }
        return false;
    }

    /**
     * @return TreeItem|null
     */
    public static function getRootItem() : ?TreeItem
    {
        $qwr = App::$db->query("SELECT id FROM tree_item WHERE parent_id=0");
        $tree_item = null;
        if ($item_data = $qwr->fetch())
        {
            $item_id = $item_data['id'];
            $tree_item = TreeItem::loadFromDb($item_id);
        }
        return $tree_item;
    }

}