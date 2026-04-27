<?php

require_once __DIR__ . '/../lib/Database.php';

class Collection {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    //devuelve todas las cartas de un usuario
    public function getByUser(int $userId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM collections WHERE user_id = ? ORDER BY added_at DESC");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function add(){
        //anadir cartas a wishlist
    }

    public function remove(){
        //adivina que hace
        //elimina la carta
    }

    public function hasCard(){
        //comprobacion de si tiene carta (mejor en metodo separado)
    }

    public function count(){
        //toal de cartas
    
    }

}