<?php

namespace Ca\Error\Controller;

use Ca\Framework\Core\Controller;
use Ca\Framework\Helper\FormBuilder;
use Ca\Categories\Model\Category;
use Ca\Framework\Helper\Request;
use Ca\Categories\Model\Collection\Categories;

class Index extends Controller
{
    private $post;

    public function __construct()
    {
        $request = new Request();
        $this->post = $request->getPost();
        parent::__construct('Ca/Categories');
    }

    public function index()
    {
        echo 'error';

    }

    public function create()
    {
        $categories = new Categories();
        $categoriesOptions = [];
        $categoriesOptions[0] = '--------';
        foreach ($categories->getCollection() as $category) {
            $categoriesOptions[$category->getId()] = $category->getName();
        }

        $form = new FormBuilder('post', '/categories/store', '');
        $form->input('text', 'name', '', '', 'Category name')
            ->input('text', 'slug', '', '', 'Slug')
            ->select('parent_id', $categoriesOptions, '')
            ->button('save', 'Save');

        $data['form'] = $form->get();
        $this->render('form/create', $data);

    }

    public function store()
    {
        $category = new Category();
        $category->setName($this->post['name']);
        $category->setSlug($this->post['slug']);
        $category->setParentId($this->post['parent_id']);
        $category->save();
    }

    public function edit($id)
    {
        $categories = new Categories();
        $category = new Category();
        $category->load($id);
        $categoriesOptions = [];
        $categoriesOptions[0] = '--------';
        foreach ($categories->getCollection() as $category) {
            $categoriesOptions[$category->getId()] = $category->getName();
        }

        $form = new FormBuilder('post', '/categories/store');
        $form->input('text', 'name', '', '', 'Category name','','',$category->getName())
            ->input('text', 'slug', '', '', 'Slug','','',$category->getSlug())
            ->select('parent_id', $categoriesOptions, '')
            ->button('save', 'Save');

        $data['form'] = $form->get();
        $this->render('form/create', $data);
    }

}

