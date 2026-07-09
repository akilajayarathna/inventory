<?php

    class Model {
        protected $db;

        public function __construct() {
            require_once __DIR__ . '/../config/database.php';
            try {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8";
                $this->db = new PDO($dsn, DB_USER, DB_PASS);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }
            catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }