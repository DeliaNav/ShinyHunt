<?php

require_once __DIR__ . '/../lib/TCGApi.php';

class Buscador {
    public function buscar(string $query): array {
        return TCGApi::searchCards($query);
    }
}