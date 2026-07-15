<?php

class PurchaseOrdersController extends Controller {

    private $orderModel;
    private $itemModel;
    private $supplierModel;
    private $productModel;
    private $stockModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->orderModel    = new PurchaseOrder();
        $this->itemModel     = new PurchaseOrderItem();
        $this->supplierModel = new Supplier();
        $this->productModel  = new Product();
        $this->stockModel    = new StockMovement();
    }

    public function index() {
        $data = [
            'orders' => $this->orderModel->getAll()
        ];
        $this->view('purchase-orders/index', $data);
    }

    public function create() {
        $suppliers = $this->supplierModel->getAll();
        $products  = $this->productModel->getAll();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplierId = $_POST['supplier_id'];
            $productIds = $_POST['product_id'] ?? [];
            $quantities = $_POST['quantity'] ?? [];
            $unitCosts  = $_POST['unit_cost'] ?? [];

            $error = $this->validate($supplierId, $productIds, $quantities, $unitCosts);

            if($error) {
                $this->view('purchase-orders/create', [
                    'error' => $error, 'suppliers' => $suppliers, 'products' => $products
                ]);
                return;
            }

            // Calculate total
            $total = 0;
            for($i = 0; $i < count($productIds); $i++) {
                $total += $quantities[$i] * $unitCosts[$i];
            }

            // Create the order (parent record)
            $orderId = $this->orderModel->create([
                'supplier_id'  => $supplierId,
                'user_id'      => $_SESSION['user_id'],
                'total_amount' => $total
            ]);

            // Create each line item
            for($i = 0; $i < count($productIds); $i++) {
                $this->itemModel->create([
                    'purchase_order_id' => $orderId,
                    'product_id'        => $productIds[$i],
                    'quantity'          => $quantities[$i],
                    'unit_cost'         => $unitCosts[$i]
                ]);
            }

            header('Location: ' . BASE_URL . 'purchaseorders');
            exit;
        }

        $this->view('purchase-orders/create', ['suppliers' => $suppliers, 'products' => $products]);
    }

    public function viewOrder($id) {
        $order = $this->orderModel->findById($id);

        if(!$order) {
            header('Location: ' . BASE_URL . 'purchaseorders');
            exit;
        }

        $data = [
            'order' => $order,
            'items' => $this->itemModel->getByOrderId($id)
        ];
        $this->view('purchase-orders/view', $data);
    }

    public function receive($id) {
        $order = $this->orderModel->findById($id);

        if(!$order || $order->status !== 'pending') {
            header('Location: ' . BASE_URL . 'purchaseorders');
            exit;
        }

        $items = $this->itemModel->getByOrderId($id);

        // Create a stock movement for every item in this order
        foreach($items as $item) {
            $this->stockModel->create([
                'product_id'    => $item->product_id,
                'user_id'       => $_SESSION['user_id'],
                'type'          => 'purchase',
                'quantity'      => $item->quantity,
                'reference_id'  => $id,
                'note'          => 'Received from Purchase Order #' . $id
            ]);
        }

        $this->orderModel->markAsReceived($id);

        header('Location: ' . BASE_URL . 'purchaseorders/viewOrder/' . $id);
        exit;
    }

    public function cancel($id) {
        $this->orderModel->cancel($id);
        header('Location: ' . BASE_URL . 'purchaseorders');
        exit;
    }

    private function validate($supplierId, $productIds, $quantities, $unitCosts) {
        if(empty($supplierId)) return 'Supplier is required';
        if(empty($productIds) || count($productIds) == 0) return 'At least one product line is required';

        for($i = 0; $i < count($productIds); $i++) {
            if(empty($productIds[$i])) return 'All product rows must have a product selected';
            if(!is_numeric($quantities[$i]) || $quantities[$i] <= 0) return 'All quantities must be valid positive numbers';
            if(!is_numeric($unitCosts[$i]) || $unitCosts[$i] < 0) return 'All unit costs must be valid positive numbers';
        }

        return null;
    }
}