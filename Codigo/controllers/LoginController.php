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
            header("Location: /TFG/Codigo/home");
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

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $phone    = trim($_POST['phone']    ?? '');
        $adress   = trim($_POST['adress']   ?? '');

        // Para que no se tenga que escribir todo otra vez
        if (!$username || !$email || !$password) {
            header("Location: /TFG/Codigo/registro?error=campos_vacios"
                . "&username=" . urlencode($username)
                . "&email="    . urlencode($email)
                . "&phone="    . urlencode($phone)
                . "&adress="   . urlencode($adress));
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /TFG/Codigo/registro?error=email_invalido"
                . "&username=" . urlencode($username)
                . "&email="    . urlencode($email)
                . "&phone="    . urlencode($phone)
                . "&adress="   . urlencode($adress));
            exit();
        }

        if ($modelo->usernameExists($username)) {
            header("Location: /TFG/Codigo/registro?error=username_duplicado"
                . "&username=" . urlencode($username)
                . "&email="    . urlencode($email)
                . "&phone="    . urlencode($phone)
                . "&adress="   . urlencode($adress));
            exit();
        }

        if ($modelo->emailExists($email)) {
            header("Location: /TFG/Codigo/registro?error=email_duplicado"
                . "&username=" . urlencode($username)
                . "&email="    . urlencode($email)
                . "&phone="    . urlencode($phone)
                . "&adress="   . urlencode($adress));
            exit();
        }

        $data = [
            'username' => $username,
            'email'    => $email,
            'password' => $password,
            'phone'    => $phone,
            'adress'   => $adress,
        ];

        if ($modelo->registro($data)) {
            header("Location: /TFG/Codigo/login?registered=1");
        } else {
            header("Location: /TFG/Codigo/registro?error=error_general");
        }
        exit();
    }
}