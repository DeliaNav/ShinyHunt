<?php
class HomeController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /TFG/Codigo/login");
            exit();
        }

        require_once 'views/home.php';
    }
}
