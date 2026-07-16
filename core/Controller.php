<?php

    class Controller {

        public function view( $view, $data = [] ) {
            extract($data);
            require_once __DIR__. '/../app/views/'.$view.'.php';
        }
        
        protected function json($data, $statusCode = 200) {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    }