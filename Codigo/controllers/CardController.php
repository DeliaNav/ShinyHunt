<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/TCGApi.php';
require_once __DIR__ . '/../models/Collection.php';
require_once __DIR__ . '/../models/wishList.php';
require_once __DIR__ . '/../models/Listing.php';

class CardController {

    public function show(string $id) {
        Auth::require();

        $card = TCGApi::getCard($id);

        if (!$card) {
            http_response_code(404);
            echo '<div style="text-align:center;padding:4rem;font-family:sans-serif">';
            echo '<h1>404</h1><p>Carta no encontrada.</p>';
            echo '<a href="/TFG/Codigo/home">← Volver al inicio</a>';
            echo '</div>';
            return;
        }

        $userId       = Auth::userId();
        $collection   = new Collection();
        $wishlist     = new WishList();
        $listingModel = new Listing();

        $inCollection = $collection->hasCard($userId, $id);
        $inWishlist   = $wishlist->hasCard($userId, $id);
        $listings     = $listingModel->getByCard($id);

        $pageTitle = htmlspecialchars($card['name']) . ' · ShinnyHunt';
        $extraCss  = 'card-detail.css';
        require_once __DIR__ . '/../views/cards/show.php';
    }
}