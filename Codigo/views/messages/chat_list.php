<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/chat.css">

<div class="chat-wrap">
    <div class="container">

        <div class="chat-list-header">
            <h1 class="chat-list-title">Mensajes</h1>
            <?php if ($totalUnread > 0): ?>
                <span class="chat-unread-badge"><?= $totalUnread ?> no leído<?= $totalUnread !== 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </div>

        <?php if (empty($conversations)): ?>
            <div class="chat-empty">
                <div class="chat-empty-icon">💬</div>
                <h3>No tienes conversaciones todavía</h3>
                <p>Puedes iniciar un chat desde el perfil de cualquier vendedor.</p>
            </div>
        <?php else: ?>
            <ul class="chat-list">
                <?php foreach ($conversations as $conv): ?>
                    <li class="chat-list-item <?= $conv['unread_count'] > 0 ? 'chat-list-item--unread' : '' ?>">
                        <a href="/TFG/Codigo/mensajes/<?= htmlspecialchars($conv['other_username']) ?>" class="chat-list-link">
                            <div class="chat-avatar">
                                <?php if ($conv['other_avatar']): ?>
                                    <img src="<?= htmlspecialchars($conv['other_avatar']) ?>"
                                         alt="<?= htmlspecialchars($conv['other_username']) ?>">
                                <?php else: ?>
                                    <div class="chat-avatar-placeholder">
                                        <?= strtoupper(mb_substr($conv['other_username'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="chat-list-info">
                                <div class="chat-list-top">
                                    <span class="chat-list-name"><?= htmlspecialchars($conv['other_username']) ?></span>
                                    <span class="chat-list-time"><?= date('d/m/Y', strtotime($conv['last_at'])) ?></span>
                                </div>
                                <div class="chat-list-bottom">
                                    <span class="chat-list-preview"><?= htmlspecialchars(mb_substr($conv['last_message'], 0, 60)) ?><?= mb_strlen($conv['last_message']) > 60 ? '…' : '' ?></span>
                                    <?php if ($conv['unread_count'] > 0): ?>
                                        <span class="chat-count-badge"><?= $conv['unread_count'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>