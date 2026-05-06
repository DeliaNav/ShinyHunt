<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/TCGApi.php';
require_once __DIR__ . '/../models/Buscador.php';

class BuscadorController {
    private Buscador $model;

    public function __construct() {
        $this->model = new Buscador();
    }

    public function search() {
        Auth::require();

        $query = trim($_GET['q'] ?? '');
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $cards = [];

        if ($query !== '') {
            $cards = $this->model->buscar(ucfirst(strtolower($query)), $page);
        }

        $pageTitle = 'Búsqueda · TCGMarket';
        $extraCss  = 'buscador.css';
        require_once __DIR__ . '/../views/buscador/search.php';
    }
}