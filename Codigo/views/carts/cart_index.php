<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="cart-wrap">
    <div class="container">

        <h1 class="cart-title">Mi Carrito</h1>

        <?php if (empty($items)): ?>
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div><!--Puede que se cambie-->
                <h3>Tu carrito está vacío</h3>
                <p>Explora las cartas en venta y añade las que te interesen.</p>
                <a href="/TFG/Codigo/home" class="btn-go-home">Explorar cartas</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">

                <!-- Items -->
                <div class="cart-items" id="cart-items">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item" id="cart-item-<?= $item['listing_id'] ?>">
                            <a href="/TFG/Codigo/cards/<?= htmlspecialchars($item['card_id']) ?>" class="cart-item-img">
                                <img src="<?= htmlspecialchars($item['image_url']) ?>"
                                     alt="<?= htmlspecialchars($item['card_name']) ?>"
                                     onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                            </a>
                            <div class="cart-item-info">
                                <p class="cart-item-name"><?= htmlspecialchars($item['card_name']) ?></p>
                                <p class="cart-item-seller">Vendido por <a href="/TFG/Codigo/perfil/<?= htmlspecialchars($item['seller_name']) ?>"><?= htmlspecialchars($item['seller_name']) ?></a></p>
                                <p class="cart-item-condition"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $item['condition']))) ?></p>
                                <p class="cart-item-price"><?= number_format($item['price'], 2) ?> € / ud</p>
                            </div>
                            <div class="cart-item-qty">
                                <span class="qty-label">Cantidad: <strong><?= $item['quantity'] ?></strong></span>
                                <span class="qty-stock">Stock: <?= $item['stock'] ?></span>
                            </div>
                            <div class="cart-item-subtotal">
                                <span><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
                            </div>
                            <button class="cart-item-remove"
                                    data-listing-id="<?= $item['listing_id'] ?>"
                                    title="Eliminar">✕</button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Resumen -->
                <div class="cart-summary">
                    <h2 class="summary-title">Resumen</h2>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong id="cart-total"><?= number_format($total, 2) ?> €</strong>
                    </div>
                    <div class="summary-row summary-row--note">
                        <span>Gastos de envío</span>
                        <span>A acordar con cada vendedor</span>
                    </div>
                    <a href="/TFG/Codigo/carrito/checkout" class="btn-checkout"><!-- revisar css de este boton-->
                        Proceder al pago →
                    </a>
                    <p class="summary-note">Serás redirigido al formulario de pago</p>
                </div>

            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/TFG/Codigo/public/js/cart.js"></script>
<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>
