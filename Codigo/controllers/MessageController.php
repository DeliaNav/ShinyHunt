<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Message.php';

class MessageController {
    private Message $model;

    public function __construct() {
        $this->model = new Message();
    }

    // GET /mensajes — lista de conversaciones
    public function index() {
        Auth::require();
        $userId       = Auth::userId();
        $conversations = $this->model->getConversations($userId);
        $totalUnread  = $this->model->countUnread($userId);

        $pageTitle = 'Mensajes · ShinnyHunt';
        $extraCss  = 'chat.css';
        require_once __DIR__ . '/../views/messages/chat_list.php';
    }

    // GET /mensajes/{username} — conversación individual
    public function show(string $username) {
        Auth::require();
        $userId = Auth::userId();

        $other = $this->model->getUserByUsername($username);
        if (!$other || $other['id'] === $userId) {
            header("Location: /TFG/Codigo/mensajes");
            exit();
        }

        // Marca leídos  mensajes entrantes
        $this->model->markAsRead($userId, $other['id']);

        $messages = $this->model->getConversation($userId, $other['id']);

        $pageTitle = 'Chat con ' . htmlspecialchars($other['username']) . ' · TCGMarket';
        $extraCss  = 'chat.css';
        require_once __DIR__ . '/../views/messages/chat.php';
    }

    // POST /mensajes/send — envía mensaje (fetch JSON)
    public function send() {
        Auth::require();
        header('Content-Type: application/json');
        $userId     = Auth::userId();
        $receiverId = (int)($_POST['receiver_id'] ?? 0);
        $content    = trim($_POST['content'] ?? '');

        if (!$receiverId || $content === '' || $receiverId === $userId) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit();
        }

        // Para que limite la longitud
        $content = mb_substr($content, 0, 1000);

        $ok = $this->model->send($userId, $receiverId, $content);

        echo json_encode([
            'success'  => $ok,
            'message'  => $ok ? $content : 'Error al enviar',
            'sent_at'  => date('H:i'),
        ]);
        exit();
    }
}