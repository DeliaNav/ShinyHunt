<?php

require_once __DIR__ . '/../lib/Database.php';

class Listing {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    //Listings activos de una carta
    public function getByCard(string $cardId): array {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.username AS seller_name, u.avatar AS seller_avatar
            FROM listings l
            JOIN users u ON u.id = l.seller_id
            WHERE l.card_id = ? AND l.status = 'active' AND l.quantity > 0
            ORDER BY l.price ASC
        ");
        $stmt->execute([$cardId]);
        return $stmt->fetchAll();
    }

    //Listing activo de un usuario para una carta
    public function getByUserAndCard(int $userId, string $cardId): ?array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM listings
            WHERE seller_id = ? AND card_id = ? AND status = 'active'
            LIMIT 1
        ");
        $stmt->execute([$userId, $cardId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Obtiene un listing por id
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM listings WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    //Crea un nuevo listing
    public function create(int $sellerId, string $cardId, string $cardName, string $imageUrl, float $price, int $quantity, string $condition, string $description = ''): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO listings (seller_id, card_id, card_name, image_url, price, quantity, `condition`, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        return $stmt->execute([$sellerId, $cardId, $cardName, $imageUrl, $price, $quantity, $condition, $description]);
    }

    //Descuenta cantidad al comprar
    public function decreaseQuantity(int $listingId, int $amount): bool {
        $stmt = $this->pdo->prepare("
            UPDATE listings
            SET quantity = quantity - ?,
                status = IF(quantity - ? <= 0, 'sold', 'active')
            WHERE id = ? AND quantity >= ?
        ");
        return $stmt->execute([$amount, $amount, $listingId, $amount]);
    }

    //Cancela un listing propio
    public function cancel(int $listingId, int $userId): bool {
        $stmt = $this->pdo->prepare("
            UPDATE listings SET status = 'cancelled'
            WHERE id = ? AND seller_id = ?
        ");
        return $stmt->execute([$listingId, $userId]);
    }

    //Listings activos de un vendedor
    public function getBySeller(int $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM listings WHERE seller_id = ? ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}