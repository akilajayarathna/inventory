<?php

class DashboardController extends Controller {

    private $dashboardModel;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth');
            exit;
        }
        $this->dashboardModel = new Dashboard();
    }

    public function index() {
        $data = [
            'totalProducts'    => $this->dashboardModel->getTotalProducts(),
            'totalCategories'  => $this->dashboardModel->getTotalCategories(),
            'totalSuppliers'   => $this->dashboardModel->getTotalSuppliers(),
            'lowStockProducts' => $this->dashboardModel->getLowStockProducts()
        ];
        $this->view('dashboard/index', $data);
    }
}