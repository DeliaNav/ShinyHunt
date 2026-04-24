<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/TCGApi.php';

class CardController {

    public function show(string $id) {
        Auth::require();

        $card = TCGApi::getCard($id);

        if (!$card) {
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
            return;
        }

        $pageTitle = htmlspecialchars($card['name']) . ' · TCGMarket';
        $extraCss  = 'card-detail.css';
        require_once __DIR__ . '/../views/cards/show.php';
    }
}