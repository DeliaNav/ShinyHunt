<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    public function search() {
        Auth::require();

        $query = trim($_GET['q'] ?? '');
        $users = [];

        if ($query !== '') {
            $users = $this->model->search($query);
        }

        $pageTitle = 'Buscar Usuarios · TCGMarket';
        // Puedes reutilizar el CSS del buscador o crear uno nuevo
        $extraCss  = 'buscador.css';
        require_once __DIR__ . '/../views/users/search.php';
    }
}