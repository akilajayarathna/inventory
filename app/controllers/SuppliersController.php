<?php

class SuppliersController extends Controller {

    private $supplierModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->supplierModel = new Supplier();
    }

    public function index() {
        $data = [
            'suppliers' => $this->supplierModel->getAll()
        ];
        $this->view('suppliers/index', $data);
    }

    public function create() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name'           => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'phone'          => trim($_POST['phone']),
                'email'          => trim($_POST['email']),
                'address'        => trim($_POST['address'])
            ];

            if(empty($data['name'])) {
                $this->view('suppliers/create', ['error' => 'Supplier name is required']);
                return;
            }

            if($this->supplierModel->create($data)) {
                header('Location: ' . BASE_URL . 'suppliers');
                exit;
            }
        }
        $this->view('suppliers/create');
    }

    public function edit($id) {
        $supplier = $this->supplierModel->findById($id);

        if(!$supplier) {
            die('Supplier not found');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name'           => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'phone'          => trim($_POST['phone']),
                'email'          => trim($_POST['email']),
                'address'        => trim($_POST['address'])
            ];

            if(empty($data['name'])) {
                $this->view('suppliers/edit', ['error' => 'Supplier name is required', 'supplier' => $supplier]);
                return;
            }

            if($this->supplierModel->update($id, $data)) {
                header('Location: ' . BASE_URL . 'suppliers');
                exit;
            }
        }
        $this->view('suppliers/edit', ['supplier' => $supplier]);
    }

    public function delete($id) {
        $this->supplierModel->delete($id);
        header('Location: ' . BASE_URL . 'suppliers');
        exit;
    }
}