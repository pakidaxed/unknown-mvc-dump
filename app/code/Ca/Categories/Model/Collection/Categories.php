<?php

namespace Ca\Categories\Model\Collection;

use Ca\Framework\Helper\SqlBuilder;
use Ca\Categories\Model\Category;

class Categories
{
    private $collection = [];

    public function getCollection()
    {
        $db = new SqlBuilder();
        $categoriesIds = $db->select('id')->from('categories')->get();

        foreach ($categoriesIds as $row) {
            $category = new Category();
            $this->collection[] = $category->load($row['id']);
        }
        return $this->collection;
    }
}