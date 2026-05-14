<?php

require_once 'lib/Auth.php';

class WelcomeController {
    public function index() {
        if (Auth::check()) {
            header("Location: /TFG/Codigo/home");
            exit();
        }
        require_once 'views/welcome.php';
    }
}
