<?php

class ProductsController extends Controller {

    private $productModel;
    private $categoryModel;
    private $supplierModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->productModel  = new Product();
        $this->categoryModel = new Category();
        $this->supplierModel = new Supplier();
    }

    public function index() {
        $data = [
            'products' => $this->productModel->getAll()
        ];
        $this->view('products/index', $data);
    }

    public function create() {
        $categories = $this->categoryModel->getAll();
        $suppliers  = $this->supplierModel->getAll();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id'   => $_POST['category_id'],
                'supplier_id'   => $_POST['supplier_id'],
                'name'          => trim($_POST['name']),
                'sku'           => trim($_POST['sku']),
                'description'   => trim($_POST['description']),
                'unit_price'    => $_POST['unit_price'],
                'reorder_level' => $_POST['reorder_level']
            ];

            $error = $this->validate($data);

            if($error) {
                $this->view('products/create', [
                    'error' => $error, 'categories' => $categories, 'suppliers' => $suppliers
                ]);
                return;
            }

            if($this->productModel->skuExists($data['sku'])) {
                $this->view('products/create', [
                    'error' => 'SKU already exists', 'categories' => $categories, 'suppliers' => $suppliers
                ]);
                return;
            }

            if($this->productModel->create($data)) {
                header('Location: ' . BASE_URL . 'products');
                exit;
            }
        }

        $this->view('products/create', ['categories' => $categories, 'suppliers' => $suppliers]);
    }

    public function edit($id) {
        $product = $this->productModel->findById($id);

        if(!$product) {
            header('Location: ' . BASE_URL . 'products');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $suppliers  = $this->supplierModel->getAll();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id'   => $_POST['category_id'],
                'supplier_id'   => $_POST['supplier_id'],
                'name'          => trim($_POST['name']),
                'sku'           => trim($_POST['sku']),
                'description'   => trim($_POST['description']),
                'unit_price'    => $_POST['unit_price'],
                'reorder_level' => $_POST['reorder_level']
            ];

            $error = $this->validate($data);

            if($error) {
                $this->view('products/edit', [
                    'error' => $error, 'product' => $product, 'categories' => $categories, 'suppliers' => $suppliers
                ]);
                return;
            }

            if($this->productModel->skuExists($data['sku'], $id)) {
                $this->view('products/edit', [
                    'error' => 'SKU already exists', 'product' => $product, 'categories' => $categories, 'suppliers' => $suppliers
                ]);
                return;
            }

            if($this->productModel->update($id, $data)) {
                header('Location: ' . BASE_URL . 'products');
                exit;
            }
        }

        $this->view('products/edit', ['product' => $product, 'categories' => $categories, 'suppliers' => $suppliers]);
    }

    public function delete($id) {
        $this->productModel->delete($id);
        header('Location: ' . BASE_URL . 'products');
        exit;
    }

    private function validate($data) {
        if(empty($data['name'])) return 'Product name is required';
        if(empty($data['sku'])) return 'SKU is required';
        if(empty($data['category_id'])) return 'Category is required';
        if(!is_numeric($data['unit_price']) || $data['unit_price'] < 0) return 'Unit price must be a valid positive number';
        if(!is_numeric($data['reorder_level']) || $data['reorder_level'] < 0) return 'Reorder level must be a valid positive number';
        return null;
    }
}