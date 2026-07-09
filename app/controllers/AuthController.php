<?php

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        session_start();
        $this->userModel = new User();
    }

    public function index() {
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        $this->view('auth/login');
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        if(empty($email) || empty($password)) {
            $error = 'All fields are required';
            $this->view('auth/login', ['error' => $error]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if(!$user || !password_verify($password, $user->password)) {
            $error = 'Invalid email or password';
            $this->view('auth/login', ['error' => $error]);
            return;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;

        header('Location: ' . BASE_URL . 'dashboard');
        exit;
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'auth');
        exit;
    }
}