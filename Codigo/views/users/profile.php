<?php
    require_once __DIR__ . '/../../views/layout/header.php';
?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/user.css">

<div class="container profile-main-container">

    <!-- TARJETA DE PERFIL -->
    <div class="profile-header-card">
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar-circle">
                <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : '/TFG/Codigo/public/img/default-avatar.png' ?>"
                     alt="Avatar de <?= htmlspecialchars($user['username']) ?>">
            </div>
        </div>
        
        <div class="profile-info-content">
            <div class="profile-name-row">
                <h1 class="profile-username"><?= htmlspecialchars($user['username']) ?></h1>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user['id']): ?>
                    <a href="/TFG/Codigo/mensajes/<?= $user['username'] ?>" class="btn-message-user">
                        ✉ Enviar mensaje
                    </a>
                <?php endif; ?>
            </div>

            <p class="profile-date">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Miembro desde: <?= date('d/m/Y', strtotime($user['create_in'])) ?>
            </p>
            
            <?php if (!empty($user['bio'])): ?>
                <div class="profile-bio-box">
                    "<?= htmlspecialchars($user['bio']) ?>"
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SECCIÓN DE VENTAS -->
    <div class="profile-section-header">
        <h2 class="profile-section-title">Cartas en venta</h2>
        <span class="profile-listing-count"><?= count($userListings) ?> Anuncio(s)</span>
    </div>

    <?php if (empty($userListings)): ?>
        <div class="profile-empty-state">
            <div class="empty-icon">📦</div>
            <h3>Este usuario no tiene cartas publicadas todavía.</h3>
            <p>¡Vuelve más tarde para ver sus novedades!</p>
        </div>
    <?php else: ?>

        <?php
        $conditionLabels = [
            'mint'         => 'Mint',
            'near_mint'    => 'Near Mint',
            'excellent'    => 'Excellent',
            'good'         => 'Good',
            'light_played' => 'Light Played',
            'played'       => 'Played',
            'poor'         => 'Poor',
        ];
        ?>

        <div class="listings-table">
            <div class="listings-head">
                <span>Carta</span>
                <span>Estado</span>
                <span>Descripción</span>
                <span>Cantidad</span>
                <span>Precio</span>
                <span></span>
            </div>

            <?php foreach ($userListings as $listing): ?>
                <div class="listing-row" id="listing-row-<?= $listing['id'] ?>">

                    <!-- Imagen + nombre -->
                    <span class="listing-card-info">
                        <a href="/TFG/Codigo/cards/<?= htmlspecialchars($listing['card_id']) ?>" class="listing-card-link">
                            <img src="<?= htmlspecialchars($listing['image_url']) ?>"
                                 alt="<?= htmlspecialchars($listing['card_name']) ?>"
                                 class="listing-card-thumb"
                                 onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                            <span><?= htmlspecialchars($listing['card_name']) ?></span>
                        </a>
                    </span>

                    <!-- Condición -->
                    <span class="listing-condition">
                        <?= htmlspecialchars($conditionLabels[$listing['condition']] ?? $listing['condition']) ?>
                    </span>

                    <!-- Descripción -->
                    <span class="listing-description">
                        <?= !empty($listing['description']) ? htmlspecialchars($listing['description']) : '<em style="color:#bbb">—</em>' ?>
                    </span>

                    <!-- Cantidad -->
                    <span class="listing-qty" id="listing-qty-<?= $listing['id'] ?>">
                        <?= $listing['quantity'] ?>
                    </span>

                    <!-- Precio -->
                    <span class="listing-price">
                        <?= number_format($listing['price'], 2) ?> €
                    </span>

                    <!-- Acción -->
                    <span class="listing-actions">
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $listing['seller_id']): ?>
                            <span class="listing-own-badge">Tu anuncio</span>
                        <?php else: ?>
                            <button class="btn-add-cart"
                                    data-listing-id="<?= $listing['id'] ?>"
                                    data-stock="<?= $listing['quantity'] ?>">
                                + Añadir
                            </button>
                            <span class="cart-qty-badge" id="cart-qty-<?= $listing['id'] ?>"></span>
                        <?php endif; ?>
                    </span>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<script src="/TFG/Codigo/public/js/cart-detail.js"></script>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>