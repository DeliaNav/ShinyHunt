<?php

require_once __DIR__ . '/../lib/Database.php';

class WishList {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    //devuelve todas las cartas de un usuario
    public function getByUser(int $userId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM wishlists WHERE user_id = ? ORDER BY added_at DESC");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function add(int $userId, string $cardId, string $cardName, string $imageUrl): bool{
        //anadir cartas a wishlist
        $stmt = $this->pdo->prepare("INSERT INTO wishlists (user_id, card_id, card_name, image_url, quantity)
            VALUES (?, ?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1");

        return $stmt->execute([$userId, $cardId, $cardName, $imageUrl]);
    }

    public function remove(int $userId, string $cardId){
        //elimina la carta
        $stmt = $this->pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND card_id = ?");
        return $stmt->execute([$userId, $cardId]);
    }

    public function hasCard(int $userId, string $cardId): bool {
        //comprobacion de si tiene carta (mejor en metodo separado)
        $stmt = $this->pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND card_id = ?");
        $stmt->execute([$userId, $cardId]);

        return (bool) $stmt->fetch();
    }

    public function count(int $userId): int {
        //toal de cartas
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM wishlists WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

}