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

        $pageTitle = 'Buscar Usuarios · TCGMarket';
        // Puedes reutilizar el CSS del buscador o crear uno nuevo
        $extraCss  = 'buscador.css';
        require_once __DIR__ . '/../views/users/search.php';
    }

    public function show(int $id){
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        Auth::require();

        $user = $this->model->getById($id);
        if(!$user){
            header("Location: /TFG/Codigo/home");
            exit();
        }

        require_once __DIR__ . '/../models/Listing.php';
        $listingModel = new Listing;
        $userListing = $listingModel->getBySeller($id);

        $pageTitle = 'Perfil de ' . htmlspecialchars($user['username']);
        $extraCss = 'profile.css';
        require_once __DIR__ . '/../views/users/profile.php';
    }
}