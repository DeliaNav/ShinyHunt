<?php require_once __DIR__ . '/../views/layout/header.php'; ?>

<link rel="stylesheet" href="/TFG/Codigo/public/css/market.css">
<main class="market-page">
    <header class="market-header">
        <h2>Marketplace TCG</h2>
        <button class="btn-primary" onclick="toggleForm()">+ Publicar Anuncio</button>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <section id="sell-form-container" style="display: none;" class="card-form">
        <form action="/TFG/Codigo/market/sell" method="POST">
            <h3>Vender una carta</h3>
            <div class="form-group">
                <input type="text" name="card_name" value="<?= htmlspecialchars($card_name) ?>" readonly>
                <input type="hidden" name="card_id" value="<?= htmlspecialchars($card_id) ?>">
                <input type="number" step="0.01" name="price" placeholder="Precio (€)" required>
                <input type="number" name="quantity" value="1" min="1" placeholder="Cantidad">
                <select name="condition">
                    <option value="near_mint">Near Mint</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="played">Played</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Publicar anuncio</button>
                <button type="button" class="btn-cancel" onclick="toggleForm()">Cancelar</button>
            </div>
        </form>
    </section>

    <section class="listings-grid">
        <?php if (empty($listings)): ?>
            <p>No hay cartas a la venta en este momento.</p>
        <?php else: ?>
            <?php foreach ($listings as $l): ?>
                <div class="listing-card">
                    <div class="card-header">
                        <h4><?= htmlspecialchars($l['card_name']) ?></h4>
                        <span class="badge <?= $l['condition'] ?>"><?= $l['condition'] ?></span>
                    </div>
                    <div class="card-body">
                        <p class="price"><?= number_format($l['price'], 2) ?> €</p>
                        <p class="seller">Vendido por: <strong><?= htmlspecialchars($l['seller_username']) ?></strong></p>
                        <p class="stock">Disponibles: <?= $l['quantity'] ?></p>
                    </div>
                    <div class="card-footer">
                        <form action="/TFG/Codigo/market/buy" method="POST">
                            <input type="hidden" name="listing_id" value="<?= $l['id'] ?>">
                            <button type="submit" class="btn-buy" <?= ($l['seller_id'] == Auth::userId()) ? 'disabled' : '' ?>>
                                <?= ($l['seller_id'] == Auth::userId()) ? 'Tu anuncio' : 'Comprar' ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<script>
function toggleForm() {
    const form = document.getElementById('sell-form-container');
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>
