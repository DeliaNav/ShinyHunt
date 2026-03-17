<?php
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /TFG/Codigo/login");
            exit();
        }
        
        // Si llegó aquí, es que está logueado
        require_once 'views/market/index.php';
    }

