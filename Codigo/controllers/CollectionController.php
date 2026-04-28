<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Collection.php';

class CollectionController {
    private Collection $model;

    public function __construct() {
        $this->model = new Collection();
    }

    public function index() {
        Auth::require();
        $userId = Auth::userId();
        $cards  = $this->model->getByUser($userId);
        $total  = $this->model->count($userId);

        $pageTitle = 'Mi Colección · TCGMarket';
        require_once __DIR__ . '/../views/collection.php';
    }

    //Llamado por fetch desde show.php — devuelve JSON
    public function add() {
        Auth::require();
        header('Content-Type: application/json');

        $userId   = Auth::userId();
        $cardId   = trim($_POST['card_id']   ?? '');
        $cardName = trim($_POST['card_name'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        if (!$cardId || !$cardName) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }

        $ok = $this->model->add($userId, $cardId, $cardName, $imageUrl);
        echo json_encode([
            'success'  => $ok,
            'inCollection' => true,
            'message'  => $ok ? 'Carta añadida a tu colección' : 'Error al añadir'
        ]);
        exit();
    }

    // Llamado por fetch desde show.php — devuelve JSON
    public function remove() {
        Auth::require();
        header('Content-Type: application/json');

        $userId = Auth::userId();
        $cardId = trim($_POST['card_id'] ?? '');

        if (!$cardId) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }

        $ok = $this->model->remove($userId, $cardId);
        echo json_encode([
            'success'      => $ok,
            'inCollection' => false,
            'message'      => $ok ? 'Carta eliminada de tu colección' : 'Error al eliminar'
        ]);
        exit();
    }

    private function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
 
    /**
     * PARA RUTAS AJAX: comprueba sesión y devuelve JSON 401
     * en lugar de redirigir al login (que rompe el fetch).
     */
    private function requireAjax(): void {
        header('Content-Type: application/json');   // primero, antes de cualquier salida
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Sesión expirada. Recarga la página.']);
        }
    }
}