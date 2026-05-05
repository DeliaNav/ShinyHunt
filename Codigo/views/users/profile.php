<?php
    require_once __DIR__ . '/../../views/layout/header.php';
?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/profile.css">

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
                
                <!-- Botón de mensaje-->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $user['id']): ?>
                    <a href="/TFG/Codigo/mensajes/<?= htmlspecialchars($user['username']) ?>" class="btn-message-user">
                        Enviar mensaje
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
        <span class="profile-listing-count">
            <?= count($userListings) ?> Anuncio(s)
        </span>
    </div>

    <?php if (empty($userListings)): ?>
        <div class="profile-empty-state">
            <div class="empty-icon">📦</div>
            <h3>Este usuario no tiene cartas publicadas todavía.</h3>
            <p>¡Vuelve más tarde para ver sus novedades!</p>
        </div>
    <?php else: ?>
        <div class="profile-cards-grid">
            <?php foreach ($userListings as $listing): ?>
                <a href="/TFG/Codigo/cards/<?= htmlspecialchars($listing['card_id']) ?>" class="profile-card-item">
                    <div class="card-inner">
                        <div class="card-img-container">
                            <img src="<?= htmlspecialchars($listing['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($listing['card_name']) ?>">
                        </div>
                        <div class="card-details">
                            <div class="card-title"><?= htmlspecialchars($listing['card_name']) ?></div>
                            <div class="card-price"><?= number_format($listing['price'], 2, ',', '.') ?> €</div>
                            <div class="card-status">
                                <span>Estado: <strong><?= ucfirst(htmlspecialchars($listing['condition'])) ?></strong></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
require_once __DIR__ . '/../../views/layout/footer.php';
?>
