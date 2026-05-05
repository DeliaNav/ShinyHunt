<?php

require_once __DIR__ . '/../lib/Database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, string $username, string $email, string $bio, string $phone): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, bio = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $bio, $phone, $id]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function updateAvatar(int $id, string $avatarPath): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        return $stmt->execute([$avatarPath, $id]);
    }

    public function emailExists(string $email, int $excludeId): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excludeId]);
        return (bool) $stmt->fetch();
    }

    public function usernameExists(string $username, int $excludeId): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $excludeId]);
        return (bool) $stmt->fetch();
    }

    public function search(string $user): array{
        $stmt = $this->pdo->prepare("SELECT id, username, avatar, create_in FROM users WHERE username LIKE ? LIMIT 20");
        $stmt ->execute(['%' . $user. '%']);
        return $stmt->fetchAll();
    }
}
