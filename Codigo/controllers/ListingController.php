<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/TCGApi.php';
require_once __DIR__ . '/../models/Listing.php';

class ListingController {
    private Listing $model;

    public function __construct() {
        $this->model = new Listing();
    }

    //GET /vender/{cardId} — formulario para poner en venta
    public function create(string $cardId) {
        Auth::require();

        $card = TCGApi::getCard($cardId);
        if (!$card) {
            header("Location: /TFG/Codigo/home");
            exit();
        }

        // Comprueba si ya tiene un listing activo para esta carta
        $existing = $this->model->getByUserAndCard(Auth::userId(), $cardId);

        $pageTitle = 'Poner en venta · ' . htmlspecialchars($card['name']);
        $extraCss  = 'listing.css';
        require_once __DIR__ . '/../views/listings/create.php';
    }

    //POST /vender — guarda el listing
    public function store() {
        Auth::require();
        $userId = Auth::userId();

        $cardId      = trim($_POST['card_id']     ?? '');
        $cardName    = trim($_POST['card_name']   ?? '');
        $imageUrl    = trim($_POST['image_url']   ?? '');
        $price       = (float) str_replace(',', '.', $_POST['price'] ?? '0');
        $quantity    = max(1, (int)($_POST['quantity']    ?? 1));
        $condition   = $_POST['condition']  ?? 'near_mint';
        $description = trim($_POST['description'] ?? '');

        $validConditions = ['mint','near_mint','excellent','good','light_played','played','poor'];
        if (!$cardId || !$cardName || $price <= 0 || !in_array($condition, $validConditions)) {
            header("Location: /TFG/Codigo/vender/{$cardId}?error=datos");
            exit();
        }

        // Solo un listing activo por carta
        $existing = $this->model->getByUserAndCard($userId, $cardId);
        if ($existing) {
            header("Location: /TFG/Codigo/vender/{$cardId}?error=duplicate");
            exit();
        }

        $this->model->create($userId, $cardId, $cardName, $imageUrl, $price, $quantity, $condition, $description);
        header("Location: /TFG/Codigo/cards/{$cardId}?sold=1");
        exit();
    }

    //POST /vender/cancelar — cancela un listing
    public function cancel() {
        Auth::require();
        $listingId = (int)($_POST['listing_id'] ?? 0);
        $cardId    = trim($_POST['card_id'] ?? '');

        if ($listingId) {
            $this->model->cancel($listingId, Auth::userId());
        }

        header("Location: /TFG/Codigo/cards/{$cardId}");
        exit();
    }
}
