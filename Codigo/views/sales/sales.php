<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/sales.css">

<div class="sales-wrap">
    <div class="container">

        <div class="sales-hero">
            <div>
                <h1 class="sales-title">Mis Ventas</h1>
                <p class="sales-sub"><?= $total ?> carta<?= $total !== 1 ? 's' : '' ?> vendida<?= $total !== 1 ? 's' : '' ?></p>
            </div>
            <div class="sales-stats">
                <div class="sales-stat">
                    <span class="sales-stat-number"><?= $activeCount ?></span>
                    <span class="sales-stat-label">En venta</span>
                </div>
                <div class="sales-stat">
                    <span class="sales-stat-number"><?= $soldCount ?></span>
                    <span class="sales-stat-label">Vendidas</span>
                </div>
                <div class="sales-stat">
                    <span class="sales-stat-number"><?= number_format($totalEarned, 2) ?> €</span>
                    <span class="sales-stat-label">Total ganado</span>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="sales-tabs">
            <button class="sales-tab <?= $tab === 'active' ? 'sales-tab--active' : '' ?>"
                    onclick="switchTab('active')">En venta (<?= $activeCount ?>)</button>
            <button class="sales-tab <?= $tab === 'sold' ? 'sales-tab--active' : '' ?>"
                    onclick="switchTab('sold')">Vendidas (<?= $soldCount ?>)</button>
        </div>

        <?php if (empty($listings)): ?>
            <div class="sales-empty">
                <div class="sales-empty-icon">📦</div>
                <h3>No hay listings en esta categoría</h3>
            </div>
        <?php else: ?>
            <div class="sales-grid">
                <?php foreach ($listings as $l): ?>
                    <div class="sale-card" id="sale-<?= $l['id'] ?>">
                        <a href="/TFG/Codigo/cards/<?= htmlspecialchars($l['card_id']) ?>" class="sale-card-img">
                            <img src="<?= htmlspecialchars($l['image_url']) ?>"
                                 alt="<?= htmlspecialchars($l['card_name']) ?>"
                                 onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                        </a>
                        <div class="sale-card-info">
                            <p class="sale-card-name"><?= htmlspecialchars($l['card_name']) ?></p>
                            <p class="sale-card-id"><?= htmlspecialchars($l['card_id']) ?></p>
                            <p class="sale-card-condition"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $l['condition']))) ?></p>
                            <?php if (!empty($l['description'])): ?>
                                <p class="sale-card-desc"><?= htmlspecialchars($l['description']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="sale-card-meta">
                            <span class="sale-card-price"><?= number_format($l['price'], 2) ?> €</span>
                            <span class="sale-card-qty">×<?= $l['quantity'] ?> disponibles</span>
                            <span class="sale-card-date"><?= date('d/m/Y', strtotime($l['created_at'])) ?></span>
                            <span class="sale-status sale-status--<?= $l['status'] ?>">
                                <?= ['active' => 'En venta', 'sold' => 'Vendida'][$l['status']] ?? $l['status'] ?>
                            </span>
                        </div>
                        <?php if ($l['status'] === 'active'): ?>
                            <form action="/TFG/Codigo/ventas/cancelar" method="POST">
                                <input type="hidden" name="listing_id" value="<?= $l['id'] ?>">
                                <button type="submit" class="btn-danger">Eliminar anuncio</button><!--btn-danger porque pensaba que bootstrap hacia algo-->
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
function switchTab(tab) {
    window.location.href = '/TFG/Codigo/ventas?tab=' + tab;
}
</script>
<script src="/TFG/Codigo/public/js/sales.js"></script>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>