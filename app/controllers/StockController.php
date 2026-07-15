<?php

class StockController extends Controller {

    private $stockModel;
    private $productModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->stockModel   = new StockMovement();
        $this->productModel = new Product();
    }

    public function index() {
        $data = [
            'movements' => $this->stockModel->getAll()
        ];
        $this->view('stock/index', $data);
    }

    public function adjust() {
        $products = $this->productModel->getAll();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $type      = $_POST['type'];
            $quantity  = (int)$_POST['quantity'];
            $note      = trim($_POST['note']);

            $error = $this->validate($productId, $type, $quantity);

            if($error) {
                $this->view('stock/adjust', ['error' => $error, 'products' => $products]);
                return;
            }

            // 'out' type means reducing stock, so quantity should be negative
            if($type == 'adjustment_out') {
                $quantity = -abs($quantity);
                $type = 'adjustment';
            } else {
                $quantity = abs($quantity);
                $type = 'adjustment';
            }

            $data = [
                'product_id' => $productId,
                'user_id'    => $_SESSION['user_id'],
                'type'       => $type,
                'quantity'   => $quantity,
                'note'       => $note
            ];

            if($this->stockModel->create($data)) {
                header('Location: ' . BASE_URL . 'stock');
                exit;
            }
        }

        $this->view('stock/adjust', ['products' => $products]);
    }

    private function validate($productId, $type, $quantity) {
        if(empty($productId)) return 'Product is required';
        if(empty($type)) return 'Movement type is required';
        if($quantity <= 0) return 'Quantity must be greater than zero';

        if($type == 'adjustment_out') {
            $currentStock = $this->stockModel->getCurrentStock($productId);
            if($quantity > $currentStock) {
                return "Cannot remove {$quantity} units. Only {$currentStock} in stock.";
            }
        }

        return null;
    }
}