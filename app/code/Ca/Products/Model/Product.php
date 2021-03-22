<?php

namespace Ca\Products\Model;

use Ca\Framework\Helper\SqlBuilder;
use Ca\Framework\Helper\Validation;

class Product
{
    private $id;
    private $name;
    private $sku;
    private $qty;
    private $description;
    private $price;
    private $cost;
    private $skipCategories = false;
    private $categories;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setSkipCategories($value)
    {
        $this->skipCategories = $value;
    }

    public function setCategories($ids)
    {
        $this->categories = $ids;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
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
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param mixed $qty
     */
    public function setQty($qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost): void
    {
        $this->cost = $cost;
    }

    public function load($id)
    {
        $db = new SqlBuilder();
        $product = $db->select()->from('products')->where('id', Validation::validInteger($id))->getOne();
        $this->id = $product['id'];
        $this->name = $product['name'];
        $this->description = $product['description'];
        $this->sku = $product['sku'];
        $this->price = $product['price'];
        $this->cost = $product['cost'];
        $this->qty = $product['qty'];
        $this->categories = $this->getRelationships();
        return $this;
    }

    public function loadBySku($sku)
    {
        $db = new SqlBuilder();
        $product = $db->select()->from('products')->where('sku', Validation::validString($sku))->getOne();
        $this->id = $product['id'];
        $this->name = $product['name'];
        $this->description = $product['description'];
        $this->sku = $product['sku'];
        $this->price = $product['price'];
        $this->cost = $product['cost'];
        $this->qty = $product['qty'];
        return $this;
    }

    public function save()
    {
        if ($this->id) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create()
    {
        $values = [
            'name' => Validation::validEmail($this->name),
            'sku' => Validation::validString($this->sku),
            'price' => $this->price,
            'qty' => Validation::validInteger($this->qty),
            'cost' => $this->cost,
            'description' => Validation::validString($this->description)
        ];
        $db = new SqlBuilder();
        $db->insert('products')->values($values)->exec();
    }

    public function update()
    {
        $values = [
            'name' => Validation::validEmail($this->name),
            'sku' => Validation::validString($this->sku),
            'price' => $this->price,
            'qty' => Validation::validInteger($this->qty),
            'cost' => $this->cost,
            'description' => Validation::validString($this->description)
        ];
        if(!$this->skipCategories){
            $this->clearRelationsToCategories();
            $this->assignCategories();
        }
        $db = new SqlBuilder();
        $db->update('products')->set($values)->where('id', $this->id)->exec();
    }

    public function delete()
    {
        $db = new SqlBuilder();
        $db->delete()->from('products')->where('id', $this->id)->exec();
    }

    private function assignCategories()
    {
        foreach ($this->categories as $categoryId) {
            $db = new SqlBuilder();

            $db->insert('category_products')->values([
                'category_id' => $categoryId,
                'product_id' => $this->id
            ])->exec();
        }
    }

    public function clearRelationsToCategories()
    {
        $db = new SqlBuilder();
        $db->delete()->from('category_products')->where('product_id', $this->id)->exec();
    }
    // check relationships how work,
    private function getRelationships()
    {
        $categoryIds = [];
        $db = new SqlBuilder();
        $categories = $db->select()->from('category_products')->where('product_id', $this->id)->get();
        foreach ($categories as $id) {
            $categoryIds[] = $id['category_id'];
        }
        return $categoryIds;
    }

    public static function isSkuUniq($sku)
    {
        $db = new SqlBuilder();
        $result = $db->select()->from('products')->where('sku', $sku)->get();
        return empty($result) ? true : false;
    }
}