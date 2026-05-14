<?php

require_once __DIR__ . '/../lib/Database.php';

class Message {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // Lista de conversaciones del usuario (un registro por interlocutor)
    public function getConversations(int $userId): array {
        // POR LO QUE MAS QUIERAS NO TOQUES ESTA CONSULTA
        $stmt = $this->pdo->prepare("
            SELECT
                u.id AS other_id,
                u.username AS other_username,
                u.avatar AS other_avatar,
                m_last.content AS last_message,
                m_last.sent_at AS last_at,
                COUNT(m_unread.id) AS unread_count
            FROM (
                SELECT DISTINCT
                    IF(sender_id = :uid, receiver_id, sender_id) AS other_id
                FROM messages
                WHERE sender_id = :uid OR receiver_id = :uid
            ) convs
            JOIN users u ON u.id = convs.other_id
            JOIN messages m_last ON m_last.id = (
                SELECT id FROM messages
                WHERE (sender_id = :uid AND receiver_id = convs.other_id)
                   OR (sender_id = convs.other_id AND receiver_id = :uid)
                ORDER BY sent_at DESC
                LIMIT 1
            )
            LEFT JOIN messages m_unread
                ON m_unread.sender_id = convs.other_id
               AND m_unread.receiver_id = :uid
               AND m_unread.is_read = 0
            GROUP BY convs.other_id, u.username, u.avatar, m_last.content, m_last.sent_at
            ORDER BY m_last.sent_at DESC
        ");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mensajes entre dos usuarios
    public function getConversation(int $userId, int $otherId): array {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username AS sender_name, u.avatar AS sender_avatar
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.sent_at ASC
        ");
        $stmt->execute([$userId, $otherId, $otherId, $userId]);
        return $stmt->fetchAll();
    }

    // Marca como leídos todos los mensajes de otherId hacia userId
    public function markAsRead(int $userId, int $otherId): void {
        $stmt = $this->pdo->prepare("
            UPDATE messages
            SET is_read = 1
            WHERE sender_id = ? AND receiver_id = ? AND is_read = 0
        ");
        $stmt->execute([$otherId, $userId]);
    }

    // Envía mensaje
    public function send(int $senderId, int $receiverId, string $content): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, content)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$senderId, $receiverId, $content]);
    }

    // Total de mensajes no leídos para  usuario (para el badge del nav)
    public function countUnread(int $userId): int {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM messages
            WHERE receiver_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    // Obtiene usuario por username (para iniciar chat desde perfil)
    public function getUserByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, avatar FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Obtiene usuario por id
    public function getUserById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, avatar FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}