<?php

namespace Ca\Products\Controller;

use Ca\Framework\Core\Controller;
use Ca\Framework\Helper\FormBuilder;
use Ca\Framework\Helper\Request;
use Ca\Products\Model\Product;
use Ca\Products\Model\Collection\Requests;
use Ca\Categories\Model\Collection\Categories;

class Index extends Controller
{
    public const PRODUCT_IMPORT_XML_URL = 'https://manopora.lt/ca/spc.xml';
    public const PRODUCT_IMPORT_STOCKS_CSV_URL = 'https://manopora.lt/ca/stocks.csv';
    private $post;

    public function __construct()
    {
        $request = new Request();
        $this->post = $request->getPost();
        parent::__construct('Ca/ContactRequests');
    }

    public function index()
    {
        $productsCollection = new Requests();
        $data['products'] = $productsCollection->getCollection();
        $this->render('admin/list', $data);

    }


    public function edit($id)
    {
        $product = new Product();
        $product = $product->load($id);
        $form = new FormBuilder('POST', '/products/store', '', '');
        $categories = new Categories();

        $form->input('text', 'name', 'input-text', 'name', 'ContactRequest Name', '', '', $product->getName())
            ->input('text', 'sku', 'input-text', 'sku', 'SKU', '', '', $product->getSku())
            ->input('hidden', 'id', 'input-text', 'id', 'id', '', '', $product->getId())
            ->input('number', 'price', 'input-text', 'price', 'Price', '', '', $product->getPrice())
            ->textarea('description', 'ContactRequest description', '', $product->getDescription())
            ->input('number', 'cost', 'input-text', 'cost', 'cost', '', '', $product->getCost())
            ->input('number', 'qty', 'input-text', 'qty', 'Qty', '', '', $product->getQty());
        foreach ($categories->getCollection() as $category) {
            in_array($category->getId(), $product->getCategories()) ? $checked = true : $checked = false;

            $form->input(
                'checkbox',
                'categories[]',
                '',
                'category' . $category->getId(),
                '',
                $category->getName(),
                '',
                $category->getId(),
                $checked
            );
        }
        $form->button('save', 'save');

        $data['form'] = $form->get();
        $deleteForm = new FormBuilder('POST', '/products/remove');
        $deleteForm->button('delete', 'DELETE')
            ->input('hidden', 'id', 'input-text', 'id', 'id', '', '', $product->getId());
        $data['form2'] = $deleteForm->get();
        $this->render('form/create', $data);

    }


    public function create()
    {
        $form = new FormBuilder('POST', '/products/store', '', '');
        $form->input('text', 'name', 'input-text', 'name', 'ContactRequest Name')
            ->input('text', 'sku', 'input-text', 'sku', 'SKU')
            ->input('number', 'price', 'input-text', 'price', 'Price')
            ->textarea('description', 'ContactRequest description')
            ->input('number', 'cost', 'input-text', 'cost', 'cost')
            ->input('number', 'qty', 'input-text', 'qty', 'Number of products in your local warehouse')
            ->button('save', 'Save');

        $data['form'] = $form->get();
        $this->render('form/create', $data);
    }

    public function store()
    {

        $product = new Product();
        if (isset($this->post['id'])) {
            $product = $product->load($this->post['id']);
        }
        if ($product->getSku() === $this->post['sku'] || Product::isSkuUniq((string)$this->post['sku'])) {
            $product->setName($this->post['name']);
            $product->setDescription($this->post['description']);
            $product->setSku($this->post['sku']);
            $product->setPrice($this->post['price']);
            $product->setQty($this->post['qty']);
            $product->setCost($this->post['cost']);
            $product->setCategories($this->post['categories']);
            $product->save();
        } else {
            echo 'SKU uzimtas';
        }
    }

    public function remove()
    {
        $id = $this->post['id'];
        $product = new Product();
        $product->load($id)->delete();
    }

    public function show($id)
    {
        // s
    }

    public function import()
    {
        $pathToSave = PROJECT_ROOT_DIR . '/var/import/';
        $fileName = 'products' . date('d-m-Y') . '.xml';
        $file = $pathToSave . $fileName;
        $fp = fopen($file, 'w+');
        $curlHandler = curl_init(self::PRODUCT_IMPORT_XML_URL);
        curl_setopt($curlHandler, CURLOPT_FILE, $fp);
        curl_exec($curlHandler);

        $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
        foreach ($xml->produkt as $product) {
            $productObject = new Product();
            $name = (string)$product->nazwa;
            $sku = (string)$product->kzs;
            if (!Product::isSkuUniq($sku)) {
                continue;
            }
            $price = self::convertPrice((float)$product->cena_zewnetrzna);
            $qty = (int)$product->status;
            $description = (string)$product->dlugi_opis;
            $cost = self::convertPrice((float)$product->cena_zewnetrzna_hurt);

            $productObject->setName($name);
            $productObject->setSku($sku);
            $productObject->setPrice($price);
            $productObject->setCost($cost);
            $productObject->setQty($qty);
            $productObject->setDescription($description);

            $productObject->save();
        }
    }

    public function stocks()
    {
        $pathToSave = PROJECT_ROOT_DIR . '/var/import/stocks/';
        $fileName = 'products_stocks' . date('d-m-Y') . '.csv';
        $file = $pathToSave . $fileName;
        $fp = fopen($file, 'w+');
        $curlHandler = curl_init(self::PRODUCT_IMPORT_STOCKS_CSV_URL);
        curl_setopt($curlHandler, CURLOPT_FILE, $fp);
        curl_exec($curlHandler);

        $stocks = array_map('str_getcsv', file($file));
        array_walk($stocks, function (&$a) use ($stocks) {
            $a = array_combine($stocks[0], $a);
        });
        array_shift($stocks);

        foreach ($stocks as $stockItem) {
            if (!Product::isSkuUniq($stockItem['sku'])) {
                $product = new Product();
                $product->loadBySku($stockItem['sku']);
                $product->setQty($stockItem['qty']);
                $product->setSkipCategories(true);
                $product->save();
            }
        }
    }

    public function export()
    {
        $productCollection = new Requests();
        $productCollection = $productCollection->getCollection();
        $productsToCsv = [];
        $productsToCsv[] = ['id','name','sku','qty','price'];
        foreach ($productCollection as $product){
            $productsToCsv[] = [
                $product->getId(),
                $product->getName(),
                $product->getSku(),
                $product->getQty(),
                $product->getPrice()
            ];

        }
        $pathToSave = PROJECT_ROOT_DIR . '/var/export/';
        $fileName = 'products' . date('d-m-Y') . '.csv';
        $file = $pathToSave . $fileName;

        $fp = fopen($file, 'w');

        foreach ($productsToCsv as $line) {
            fputcsv($fp, $line);
        }

        fclose($fp);
    }


    private static function convertPrice($price)
    {
        return $price * 0.22;
    }


}