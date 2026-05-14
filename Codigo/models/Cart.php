<?php

require_once __DIR__ . '/../lib/Database.php';

class Cart {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    //Items del carrito con info del listing y vendedor
    public function getByUser(int $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT c.*, l.card_id, l.card_name, l.image_url, l.price,
                   l.quantity AS stock, l.`condition`, l.seller_id,
                   u.username AS seller_name
            FROM cart_items c
            JOIN listings l ON l.id = c.listing_id
            JOIN users u    ON u.id = l.seller_id
            WHERE c.user_id = ? AND l.status = 'active'
            ORDER BY c.added_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    //Añade o incrementa cantidad en el carrito
    public function add(int $userId, int $listingId, int $quantity = 1): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO cart_items (user_id, listing_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        return $stmt->execute([$userId, $listingId, $quantity, $quantity]);
    }

    //Obtiene un item concreto
    public function getItem(int $userId, int $listingId): ?array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM cart_items WHERE user_id = ? AND listing_id = ?
        ");
        $stmt->execute([$userId, $listingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    //Elimina un item del carrito
    public function remove(int $userId, int $listingId): bool {
        $stmt = $this->pdo->prepare("
            DELETE FROM cart_items WHERE user_id = ? AND listing_id = ?
        ");
        return $stmt->execute([$userId, $listingId]);
    }

    //Vacía el carrito
    public function clear(int $userId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    //Total de items en el carrito
    public function count(int $userId): int {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(c.quantity), 0)
            FROM cart_items c
            JOIN listings l ON l.id = c.listing_id AND l.status = 'active'
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    //Total precio del carrito
    public function total(int $userId): float {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(c.quantity * l.price), 0)
            FROM cart_items c
            JOIN listings l ON l.id = c.listing_id AND l.status = 'active'
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }
}