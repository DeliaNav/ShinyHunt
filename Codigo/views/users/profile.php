<?php 
// Requerimos el header (Asegúrate de que la ruta sea correcta según tu estructura)
require_once __DIR__ . '/../../views/layout/header.php'; 
?>

<div class="container" style="margin-top: 30px; min-height: 80vh;">
    <div class="profile-card" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 40px; margin-bottom: 50px;">
        <div class="profile-avatar-container" style="flex-shrink: 0;">
            <div style="width: 150px; height: 150px; border-radius: 50%; background: #f0f0f0; overflow: hidden; border: 4px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : '/TFG/Codigo/public/img/default-avatar.png' ?>" 
                     alt="Avatar de <?= htmlspecialchars($user['username']) ?>" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
        
        <div class="profile-info">
            <h1 style="font-family: 'Cinzel', serif; margin: 0; font-size: 2.5rem; color: #222;">
                <?= htmlspecialchars($user['username']) ?>
            </h1>
            <p style="color: #777; font-size: 0.95rem; margin: 8px 0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 5px;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Miembro desde: <?= date('d/m/Y', strtotime($user['create_in'])) ?>
            </p>
            
            <?php if (!empty($user['bio'])): ?>
                <div style="margin-top: 15px; padding: 15px; background: #fdfdfd; border-left: 4px solid #333; border-radius: 4px; font-style: italic; color: #444;">
                    "<?= htmlspecialchars($user['bio']) ?>"
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="section-title" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
        <h2 style="font-family: 'Cinzel', serif; margin: 0;">Cartas en venta</h2>
        <span style="background: #333; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">
            <?= count($userListings) ?> Anuncio(s)
        </span>
    </div>

    <?php if (empty($userListings)): ?>
        <div style="text-align: center; padding: 80px 20px; background: #f9f9f9; border-radius: 12px; border: 2px dashed #ddd;">
            <div style="font-size: 40px; margin-bottom: 15px;">🃏</div>
            <h3 style="color: #888;">Este usuario no tiene cartas publicadas todavía.</h3>
            <p style="color: #aaa;">¡Vuelve más tarde para ver sus novedades!</p>
        </div>
    <?php else: ?>
        <div class="cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px;">
            <?php foreach ($userListings as $listing): ?>
                <a href="/TFG/Codigo/cards/<?= htmlspecialchars($listing['card_id']) ?>" class="card-item" style="text-decoration: none; color: inherit; transition: transform 0.2s;">
                    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); height: 100%; border: 1px solid #eee;">
                        <div class="card-img-wrap" style="padding: 10px; background: #fcfcfc;">
                            <img src="<?= htmlspecialchars($listing['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($listing['card_name']) ?>" 
                                 style="width: 100%; height: auto; border-radius: 5px; display: block;">
                        </div>
                        <div class="card-info" style="padding: 15px; border-top: 1px solid #f5f5f5;">
                            <div style="font-weight: 600; font-size: 1rem; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($listing['card_name']) ?>
                            </div>
                            <div style="color: #2e7d32; font-weight: 700; font-size: 1.2rem; margin: 8px 0;">
                                <?= number_format($listing['price'], 2, ',', '.') ?> €
                            </div>
                            <div style="font-size: 0.8rem; color: #777; display: flex; justify-content: space-between;">
                                <span>Estado: <strong style="color: #444;"><?= ucfirst(htmlspecialchars($listing['condition'])) ?></strong></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Efecto hover para las cartas */
    .card-item:hover {
        transform: translateY(-5px);
    }
    .card-item:hover div {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
</style>

<?php 
// Requerimos el footer
require_once __DIR__ . '/../../views/layout/footer.php'; 
?>