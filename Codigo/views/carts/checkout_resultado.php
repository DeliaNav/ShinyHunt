<?php
$pago_exitoso = isset($_GET['payment_intent_client_secret']);
$items = $items ?? [];
$total = $total ?? 0.00;

require_once __DIR__ . '/../../views/layout/header.php';
?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/checkout.css">
<script src="https://js.stripe.com/v3/"></script>

<div class="cart-wrap">
    <div class="container">
        
        <?php if ($pago_exitoso): ?>
            <div class="checkout-success">
                <div class="success-icon">✅</div>
                <h1 class="cart-title">¡Pago realizado con éxito!</h1>
                <p>Tu pedido ha sido procesado correctamente.</p>
                <p>Pronto recibirás un correo de confirmación con los detalles de tus cartas.</p>
                <a href="/TFG/Codigo/home" class="btn-checkout btn-return">Volver a la tienda</a>
            </div>

        <?php else: ?>
            <h1 class="cart-title">Finalizar compra</h1>

            <div class="cart-layout">
                <div class="cart-items">
                    <?php if (empty($items)): ?>
                        <div class="cart-empty-state">
                            <p>No hay productos en el carrito.</p>
                            <a href="/TFG/Codigo/home" class="link-store">Volver a la tienda</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <div class="cart-item">
                                <a href="/TFG/Codigo/cards/<?= htmlspecialchars($item['card_id']) ?>" class="cart-item-img">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['card_name']) ?>" onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                                </a>
                                <div class="cart-item-info">
                                    <p class="cart-item-name"><?= htmlspecialchars($item['card_name']) ?></p>
                                    <p class="cart-item-seller">
                                        Vendido por <a href="/TFG/Codigo/usuario/<?= $item['seller_id'] ?>"><?= htmlspecialchars($item['seller_name']) ?></a>
                                    </p>
                                    <p class="cart-item-condition"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $item['condition']))) ?></p>
                                    <p class="cart-item-price"><?= number_format($item['price'], 2) ?> € / ud</p>
                                </div>
                                <div class="cart-item-qty">
                                    <span class="qty-label">Cantidad: <strong><?= $item['quantity'] ?></strong></span>
                                </div>
                                <div class="cart-item-subtotal">
                                    <span><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cart-summary">
                    <h2 class="summary-title">Pago seguro</h2>
                    <div class="summary-row">
                        <span>Total</span>
                        <strong><?= number_format($total, 2) ?> €</strong>
                    </div>
                    <div class="summary-row summary-row--note">
                        <span>Gastos de envío</span>
                        <span>A acordar con cada vendedor</span>
                    </div>

                    <div id="payment-message"></div>
                    <div id="payment-element"></div>

                    <button id="btn-pagar" class="btn-checkout" <?= ($total <= 0) ? 'disabled' : '' ?>>
                        <span id="btn-texto">Pagar <?= number_format($total, 2) ?> €</span>
                        <span id="btn-cargando">Procesando…</span>
                    </button>

                    <p class="summary-note">Pago cifrado con Stripe</p>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<script>
    const stripePublishableKey = '<?= defined('STRIPE_PUBLISHABLE_KEY') ? STRIPE_PUBLISHABLE_KEY : '' ?>';
    const stripeReturnUrl      = '<?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ?>/TFG/Codigo/carrito/resultado';
</script>

<script src="/TFG/Codigo/public/js/checkout.js"></script>
<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>