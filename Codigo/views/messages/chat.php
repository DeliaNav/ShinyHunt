<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/chat.css">

<div class="chat-wrap">
    <div class="container">

        <div class="chat-window">

            <!-- Cabecera -->
            <div class="chat-header">
                <a href="/TFG/Codigo/mensajes" class="chat-back">← Volver</a>
                <div class="chat-header-user">
                    <?php if ($other['avatar']): ?>
                        <img src="<?= htmlspecialchars($other['avatar']) ?>"
                             alt="<?= htmlspecialchars($other['username']) ?>"
                             class="chat-header-avatar">
                    <?php else: ?>
                        <div class="chat-avatar-placeholder chat-avatar-placeholder--sm">
                            <?= strtoupper(mb_substr($other['username'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <span class="chat-header-name"><?= htmlspecialchars($other['username']) ?></span>
                </div>
            </div>

            <!-- Mensajes -->
            <div class="chat-messages" id="chat-messages">
                <?php if (empty($messages)): ?>
                    <p class="chat-no-messages">Sé el primero en escribir 👋</p>
                <?php else: ?>
                    <?php
                    $prevDate = null;
                    foreach ($messages as $msg):
                        $msgDate = date('d/m/Y', strtotime($msg['sent_at']));
                        $isOwn   = $msg['sender_id'] == $userId;
                    ?>
                        <?php if ($msgDate !== $prevDate): ?>
                            <div class="chat-date-sep"><span><?= $msgDate ?></span></div>
                            <?php $prevDate = $msgDate; ?>
                        <?php endif; ?>

                        <div class="chat-bubble-wrap <?= $isOwn ? 'chat-bubble-wrap--own' : '' ?>">
                            <div class="chat-bubble <?= $isOwn ? 'chat-bubble--own' : 'chat-bubble--other' ?>">
                                <p class="chat-bubble-text"><?= nl2br(htmlspecialchars($msg['content'])) ?></p>
                                <span class="chat-bubble-time"><?= date('H:i', strtotime($msg['sent_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Input -->
            <div class="chat-input-area">
                <input type="hidden" id="receiver-id" value="<?= $other['id'] ?>">
                <textarea id="chat-input"
                          class="chat-input"
                          placeholder="Escribe un mensaje…"
                          rows="1"
                          maxlength="1000"></textarea>
                <button id="chat-send" class="chat-send-btn" title="Enviar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</div>

<script src="/TFG/Codigo/public/js/chat.js"></script>
<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>