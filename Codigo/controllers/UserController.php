<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Listing.php';

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

        $pageTitle = 'Buscar Usuarios · ShinnyHunt';
        $extraCss  = 'buscador.css';
        require_once __DIR__ . '/../views/users/search.php';
    }

    public function show(int $id) {
        Auth::require();

        $user = $this->model->getById($id);

        if (!$user) {
            header("Location: /TFG/Codigo/home");
            exit();
        }

        $listingModel = new Listing();
        $all          = $listingModel->getBySeller($id);
        $userListings = array_values(array_filter($all, fn($l) => $l['status'] === 'active'));

        $pageTitle = 'Perfil de ' . htmlspecialchars($user['username']);
        $extraCss  = 'profile.css';
        require_once __DIR__ . '/../views/users/profile.php';
    }
}