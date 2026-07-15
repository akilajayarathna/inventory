<?php

class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $this->parseUrl();
        $this->loadController();
        $this->callAction();
    }

    private function parseUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        if(isset($url[0]) && $url[0] != '') {
            $this->controller = ucfirst($url[0]) . 'Controller';
        }
        if(isset($url[1])) {
            $this->method = $url[1];
        }
        if(isset($url[2])) {
            $this->params = array_slice($url, 2);
        }
    }

    private function loadController() {
        $filePath = __DIR__ . '/../app/controllers/' . $this->controller . '.php';
        if(file_exists($filePath)) {
            require_once __DIR__ . '/../core/Model.php';
            require_once __DIR__ . '/../core/Controller.php';
            require_once __DIR__ . '/../app/models/User.php';
            require_once __DIR__ . '/../app/models/Dashboard.php';
            require_once __DIR__ . '/../app/models/Category.php';
            require_once __DIR__ . '/../app/models/Supplier.php';
            require_once __DIR__ . '/../app/models/Product.php';
            require_once __DIR__ . '/../app/models/StockMovement.php';
            require_once __DIR__ . '/../app/models/PurchaseOrder.php';
            require_once __DIR__ . '/../app/models/PurchaseOrderItem.php';
            require_once $filePath;
            $this->controller = new $this->controller();
        } else {
            die("Controller not found");
        }
    }

    private function callAction() {
        if(method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            die("Method not found");
        }
    }
}