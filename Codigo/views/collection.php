<?php
    require_once __DIR__ . '/../views/layout/header.php';
?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/collections.css">

<div class="collection-hero">
    <div class="container">
        <div class="collection-hero-inner">
            <div>
                <h1 class="collection-title">Mi Colección</h1>
                <p class="collection-sub" id="collection-count">
                    <?= $total ?> carta<?= $total !== 1 ? 's' : '' ?> en tu colección
                </p>
            </div>
            <a href="/TFG/Codigo/home" class="btn-explore">+ Explorar cartas</a>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($cards)): ?>
        <div class="collection-empty" id="collection-empty">
            <div class="empty-icon">🗂️</div>
            <h3>Tu colección está vacía</h3>
            <p>Busca cartas y añádelas para llevar un registro de tu colección física.</p>
            <a href="/TFG/Codigo/home" class="btn-explore">Explorar cartas</a>
        </div>
    <?php else: ?>
        <div class="cards-grid" id="cards-grid">
            <?php foreach ($cards as $card): ?>
                <div class="card-item" id="card-item-<?= htmlspecialchars($card['card_id']) ?>">
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
                    <button class="btn-remove"
                            title="Eliminar"
                            data-card-id="<?= htmlspecialchars($card['card_id']) ?>">Eliminar</button>
                            
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Datos para el JS -->
<script>
    window.collectionData = { total: <?= (int) $total ?> };
</script>
<script src="/TFG/Codigo/public/js/collection-page.js"></script>

<?php require_once __DIR__ . '/../views/layout/footer.php'; ?>