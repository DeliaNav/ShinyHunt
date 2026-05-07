<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    
    // Cerrar sesión
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /TFG/Codigo/");
        exit();
    }

    // Borrar usuario
    public function deleteAccount(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(!isset($_SESSION['user_id'])){
            header("Location: /TFG/Codigo/login");
            exit();
        }

        $userId = $_SESSION['user_id'];

        $userModel = new User();
        $success = $userModel->delete($userId);

        if ($success) {
            $_SESSION = array();
            session_destroy();
            
            header("Location: /TFG/Codigo/");
            exit();
        } else {
            header("Location: /TFG/Codigo/profile?error=cannot_delete");
            exit();
        }
    }

}
