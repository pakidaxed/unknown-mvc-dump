<?php

namespace Ca\Products\Model\Collection;

use Ca\Framework\Helper\SqlBuilder;


use Ca\Products\Model\Product;

class Products
{
    private $collection = [];

    public function getCollection()
    {
        $db = new SqlBuilder();
        $productsIds = $db->select('id')->from('products')->get();

        foreach ($productsIds as $row) {
            $product = new Product();
            $this->collection[] = $product->load($row['id']);
        }
        return $this->collection;
    }
}
