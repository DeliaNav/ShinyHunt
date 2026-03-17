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

    public function showRegister() {
        require_once 'views/auth/registro_vista.php';
    }

    public function register() {
        $modelo = new Login();
        
        $data = [
            'username' => $_POST['username'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'phone'    => $_POST['phone'] ?? ''
        ];

        if ($modelo->registro($data)) {
            header("Location: /TFG/Codigo/login?registered=1");
        } else {
            header("Location: /TFG/Codigo/registro?error=1");
        }
        exit();
    }
}
