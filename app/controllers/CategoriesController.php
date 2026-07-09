<?php

class CategoriesController extends Controller {

    private $categoryModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->categoryModel = new Category();
    }

    public function index() {
        $data = [
            'categories' => $this->categoryModel->getAll()
        ];
        $this->view('categories/index', $data);
    }

    public function create() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name'        => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            if(empty($data['name'])) {
                $this->view('categories/create', ['error' => 'Category name is required']);
                return;
            }

            if($this->categoryModel->create($data)) {
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }
        }
        $this->view('categories/create');
    }

    public function edit($id) {
        $category = $this->categoryModel->findById($id);

        if(!$category) {
            die('Category not found');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name'        => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            if(empty($data['name'])) {
                $this->view('categories/edit', ['error' => 'Category name is required', 'category' => $category]);
                return;
            }

            if($this->categoryModel->update($id, $data)) {
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }
        }
        $this->view('categories/edit', ['category' => $category]);
    }

    public function delete($id) {
        $this->categoryModel->delete($id);
        header('Location: ' . BASE_URL . 'categories');
        exit;
    }
}