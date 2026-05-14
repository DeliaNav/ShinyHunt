<?php

require_once __DIR__ . '/../lib/TCGApi.php';

class Buscador {
    public function buscar(string $query, int $page): array {
        $limit = 40;

        $totalNeeded= $page * $limit;
        $allCards = TCGApi::searchCards($query, $totalNeeded);
        return array_slice($allCards, ($page -1)*$limit, $limit);
    }
}