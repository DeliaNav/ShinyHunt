<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/wishList.php';

class WishListController {
    private WishList $model;

    public function __construct() {
        $this->model = new WishList();
    }

    public function index() {
        Auth::require();
        $userId = Auth::userId();
        $cards  = $this->model->getByUser($userId);
        $total  = $this->model->count($userId);

        $pageTitle = 'Mi Lista de Deseos · TCGMarket';
        require_once __DIR__ . '/../views/wishlist.php';
    }

    /** Llamado por fetch desde show.php — devuelve JSON */
    public function add() {
        $this->requireAjax();

        $userId   = Auth::userId();
        $cardId   = trim($_POST['card_id']   ?? '');
        $cardName = trim($_POST['card_name'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        if (!$cardId || !$cardName) {
            $this->json(['success' => false, 'message' => 'Datos incompletos']);
        }

        $ok = $this->model->add($userId, $cardId, $cardName, $imageUrl);
        $this->json([
            'success'    => $ok,
            'inWishlist' => true,
            'message'    => $ok ? 'Carta añadida a tu lista de deseos' : 'Error al añadir',
        ]);
    }

    /** Llamado por fetch desde show.php — devuelve JSON */
    public function remove() {
        $this->requireAjax();

        $userId = Auth::userId();
        $cardId = trim($_POST['card_id'] ?? '');

        if (!$cardId) {
            $this->json(['success' => false, 'message' => 'Datos incompletos']);
        }

        $ok = $this->model->remove($userId, $cardId);
        $this->json([
            'success'    => $ok,
            'inWishlist' => false,
            'message'    => $ok ? 'Carta eliminada de tu lista de deseos' : 'Error al eliminar',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    private function requireAjax(): void {
        header('Content-Type: application/json');
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Sesión expirada. Recarga la página.']);
        }
    }
}