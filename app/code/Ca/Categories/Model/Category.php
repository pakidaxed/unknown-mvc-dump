<?php

namespace Ca\Categories\Model;
use Ca\Framework\Helper\SqlBuilder;

class Category
{

    private $id;
    private $name;
    private $slug;
    private $parentId;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parentId = $parentId;
    }
    public function load($id)
    {
        $db = new SqlBuilder();
        $category = $db->select()->from('categories')->where('id',$id)->getOne();
        $this->id = $category['id'];
        $this->name = $category['name'];
        $this->slug = $category['slug'];
        $this->parentId = $category['parent_id'];
        return $this;
    }

    public function save()
    {
        if(isset($this->id)){
            $this->update();
        }else{
            $this->create();
        }
    }

    private function create()
    {
        $db = new SqlBuilder();
        $db->insert('categories')->values([
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parentId
        ])->exec();
    }

    private function update()
    {

    }
}