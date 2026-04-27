<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/TCGApi.php';

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

        $pageTitle = htmlspecialchars($card['name']) . ' · TCGMarket';
        $extraCss  = 'card-detail.css';
        require_once __DIR__ . '/../views/cards/show.php';
    }
}