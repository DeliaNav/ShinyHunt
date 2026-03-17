<?php
// controllers/LoginController.php
require_once 'models/Login.php';

class LoginController {
    public function index() {
        require_once 'views/auth/login.php';
    }

    public function authenticate() {
        $usuarioModelo = new Login();
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';

        $datosUsuario = $usuarioModelo->login($user, $pass);

        if ($datosUsuario) {
            $_SESSION['user_id'] = $datosUsuario['id'];
            $_SESSION['nombre']  = $datosUsuario['username'];
            header("Location: /TFG/Codigo/dashboard");
            exit();
        } else {
            header("Location: /TFG/Codigo/login?error=1");
            exit();
        }
    }
}
