<?php
require_once __DIR__ . '/../views/layout/header.php';
?>

<div class="collection-hero">
    <div class="container">
        <div class="collection-hero-inner">
            <div>
                <h1 class="collection-title">Mi Colección</h1>
                <p class="collection-sub">
                    <?= $total ?> carta<?= $total !== 1 ? 's' : '' ?> en tu colección
                </p>
            </div>
            <a href="/TFG/Codigo/home" class="btn-explore">+ Explorar cartas</a>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($cards)): ?>
        <div class="collection-empty">
            <div class="empty-icon">🗂️</div>
            <h3>Tu colección está vacía</h3>
            <p>Busca cartas y añádelas para llevar un registro de tu colección física.</p>
            <a href="/TFG/Codigo/home" class="btn-explore">Explorar cartas</a>
        </div>
    <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($cards as $card): ?>
                <div class="card-item">
                    <a href="/TFG/Codigo/cards/<?= htmlspecialchars($card['card_id']) ?>" class="card-link">
                        <div class="card-img-wrap">
                            <img src="<?= htmlspecialchars($card['image_url']) ?>"
                                 alt="<?= htmlspecialchars($card['card_name']) ?>"
                                 loading="lazy"
                                 onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                            <?php if ($card['quantity'] > 1): ?>
                                <span class="quantity-badge">×<?= $card['quantity'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-info">
                            <span class="card-name"><?= htmlspecialchars($card['card_name']) ?></span>
                            <span class="card-id"><?= htmlspecialchars($card['card_id']) ?></span>
                        </div>
                    </a>
                    <form action="/TFG/Codigo/coleccion/remove" method="POST" class="remove-form">
                        <input type="hidden" name="card_id"  value="<?= htmlspecialchars($card['card_id']) ?>">
                        <input type="hidden" name="redirect" value="/TFG/Codigo/coleccion">
                        <button type="submit" class="btn-remove" title="Eliminar"
                                onclick="return confirm('¿Eliminar esta carta de tu colección?')">✕</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../views/layout/footer.php'; ?>