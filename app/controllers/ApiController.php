<?php

class ApiController extends Controller {

    private $productModel;
    private $categoryModel;
    private $saleModel;
    private $stockModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->saleModel     = new Sale();
        $this->stockModel    = new StockMovement();

    }

    // ----------------------------------------

    public function products($id = null) {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createProduct();
            return;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if($id) {
            $product = $this->productModel->findById($id);
            if(!$product) {
                $this->json(['error' => 'Product not found'], 404);
                return;
            }
            $this->json($product);
        } else {
            $products = $this->productModel->getAll();
            $this->json($products);
        }
    }

    // ----------------------------------------

    public function categories() {
    if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->json(['error' => 'Method not allowed'], 405);
        return;
    }

    $categories = $this->categoryModel->getAll();
    $this->json($categories);
}

    // ----------------------------------------

    public function createSale() {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->json(['error' => 'Method not allowed'], 405);
        return;
    }

    if(!$this->authenticate()) {
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $items = $input['items'] ?? [];
    $paymentMethod = $input['payment_method'] ?? 'cash';

    if(empty($items)) {
        $this->json(['error' => 'At least one item is required'], 400);
        return;
    }

    // Validate each item and check stock availability
    foreach($items as $item) {
        if(empty($item['product_id']) || empty($item['quantity']) || $item['quantity'] <= 0) {
            $this->json(['error' => 'Each item requires a valid product_id and quantity'], 400);
            return;
        }

        $currentStock = $this->stockModel->getCurrentStock($item['product_id']);
        if($item['quantity'] > $currentStock) {
            $this->json(['error' => "Insufficient stock for product ID {$item['product_id']}. Available: {$currentStock}"], 400);
            return;
        }
    }

    // Calculate total
    $total = 0;
    foreach($items as $item) {
        $product = $this->productModel->findById($item['product_id']);
        $total += $product->unit_price * $item['quantity'];
    }

    // Create the sale (parent record)
    $saleId = $this->saleModel->create($this->authUser->id, $total, $paymentMethod);

    // Create sale items and reduce stock for each
    foreach($items as $item) {
        $product = $this->productModel->findById($item['product_id']);

        $this->saleModel->addItem($saleId, $item['product_id'], $item['quantity'], $product->unit_price);

        $this->stockModel->create([
            'product_id'   => $item['product_id'],
            'user_id'      => $this->authUser->id,
            'type'         => 'sale',
            'quantity'     => -abs($item['quantity']),
            'reference_id' => $saleId,
            'note'         => 'Sold via POS - Sale #' . $saleId
        ]);
    }

    $this->json([
        'message'     => 'Sale completed successfully',
        'sale_id'     => $saleId,
        'total_amount' => $total
    ], 201);
}

    // ---------------------------------------------------

    public function sales() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->createSale();
        return;
    }

    $this->json(['error' => 'Method not allowed'], 405);
}

    // -------------------------------------------------------

    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $email    = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if(empty($email) || empty($password)) {
        $this->json(['error' => 'Email and password are required'], 400);
        return;
    }

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if(!$user || !password_verify($password, $user->password)) {
        $this->json(['error' => 'Invalid credentials'], 401);
        return;
    }

    $token = $userModel->createToken($user->id);

    $this->json([
            'token' => $token,
            'user'  => [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }

    // ---- Authentication Helper ----

    private $authUser = null;

    private function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if(!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $this->json(['error' => 'Authorization token required'], 401);
            return false;
        }

        $token = $matches[1];
        $userModel = new User();
        $user = $userModel->findByToken($token);

        if(!$user) {
            $this->json(['error' => 'Invalid or expired token'], 401);
            return false;
        }

        $this->authUser = $user;
        return true;
    }

    // ---- Protected API Endpoints ----

    public function createProduct() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if(!$this->authenticate()) {
            return; // authenticate() already sent the error response
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if(empty($input['name']) || empty($input['sku']) || empty($input['category_id'])) {
            $this->json(['error' => 'name, sku, and category_id are required'], 400);
            return;
        }

        if($this->productModel->skuExists($input['sku'])) {
            $this->json(['error' => 'SKU already exists'], 400);
            return;
        }

        $data = [
            'category_id'   => $input['category_id'],
            'supplier_id'   => $input['supplier_id'] ?? null,
            'name'          => $input['name'],
            'sku'           => $input['sku'],
            'description'   => $input['description'] ?? '',
            'unit_price'    => $input['unit_price'] ?? 0,
            'reorder_level' => $input['reorder_level'] ?? 10
        ];

        if($this->productModel->create($data)) {
            $this->json(['message' => 'Product created successfully'], 201);
        } else {
            $this->json(['error' => 'Failed to create product'], 500);
        }
    }
}