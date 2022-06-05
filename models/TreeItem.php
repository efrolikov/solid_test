<?php

namespace models;

use models\views\TreeItemView;
use models\Helper;

/**
 * Tree item
 *
 * @property int $id
 * @property int $name
 * @property int $parent_id
 */
class TreeItem
{
    /**
     * @var int
     */
    private $id = 0;
    /**
     * @var string name of tree item
     */
    private $name;
    /**
     * @var int id of parent item
     */
    private $parent_id = null;

    /**
     * @var bool is this object represent new instance of tree item
     */
    private $is_new = true;

    /**
     * Save tree item to db
     *
     * @return bool
     */
    public function saveToDb() : bool
    {
        $res = false;
        if ($this->is_new)
        {
            $qwr = App::$db->prepare("INSERT INTO tree_item (name, parent_id) VALUES (:name, :parent_id)");
            $res = $qwr->execute(['name'=>$this->name, 'parent_id'=>$this->parent_id]);
            if ($res) $this->id = App::$db->lastInsertId();
        } else {
            $qwr = App::$db->prepare("UPDATE tree_item SET name=:name, parent_id=:parent_id WHERE id=:id");
            $res = $qwr->execute(['name'=>$this->name, 'parent_id'=>$this->parent_id, 'id'=>$this->id]);
        }
        if ($res) return true; else return false;
    }

    /**
     * Load item data from db
     *
     * @param int $id
     * @return TreeItem|null
     */
    public static function loadFromDb(int $id): ?TreeItem
    {
        // find out if is where tree item with idd $item_id
        $qwr = App::$db->prepare("SELECT id, name, parent_id FROM tree_item WHERE id=:id");
        $qwr->execute(['id'=>$id]);
        if ($item = $qwr->fetch()) {
            $tree_item = new TreeItem();
            $tree_item->id = $item['id'];
            $tree_item->name = $item['name'];
            $tree_item->parent_id = $item['parent_id'];
            $tree_item->is_new = false;
            return $tree_item;
        }
        return null;
    }

    /**
     * Create new child of this tree item and save it to db
     *
     * @param string $name
     * @return TreeItem|null
     */
    public function createChild(string $name) : ?TreeItem
    {
        $child = new TreeItem();
        $child->name = $name;
        $child->parent_id = $this->id;
        if ($child->saveToDb()) return $child;
        return null;
    }

    /**
     * Remove item of the tree
     *
     * @return void
     * @throws \Exception
     */
    public function remove()
    {
        $chds = $this->getChildren();
        foreach ($chds as $ch) $ch->remove();

        $qwr = App::$db->prepare("DELETE FROM tree_item WHERE id=:id");
        if (!$qwr->execute(['id'=>$this->id])) throw new Exception('Ошибка удаления узла');
    }

    public function __set($name, $val)
    {
        $this->$name = $val;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Return array of the children of this item
     *
     * @return TreeItem[] set of children of this item
     */
    public function getChildren() : array
    {
        $res = [];
        $qwr = App::$db->prepare("SELECT id FROM tree_item WHERE parent_id=:parent_id");

        $qwr->execute(['parent_id'=>$this->id]);
        $items = $qwr->fetchAll();
        foreach($items as $item) {
            $res[] = TreeItem::loadFromDb($item['id']);
        }
        return $res;
    }

}