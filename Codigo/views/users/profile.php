<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container" style="margin-top: 30px;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 30px; margin-bottom: 40px;">
        <div style="width: 120px; height: 120px; border-radius: 50%; background: #eee; overflow: hidden; border: 3px solid #f0f0f0;">
            <img src="<?= $user['avatar'] ?: '/TFG/Codigo/public/img/default-avatar.png' ?>" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <div>
            <h1 style="font-family: 'Cinzel', serif; margin: 0; color: #333;"><?= htmlspecialchars($user['username']) ?></h1>
            <p style="color: #666; margin: 5px 0;">Miembro desde: <?= date('d/m/Y', strtotime($user['create_in'])) ?></p>
            <?php if(!empty($user['bio'])): ?>
                <p style="margin-top: 10px; font-style: italic;">"<?= htmlspecialchars($user['bio']) ?>"</p>
            <?php endif; ?>
        </div>
    </div>

    <h2 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #333;">Cartas en venta</h2>

    <?php if (empty($userListings)): ?>
        <p style="text-align: center; padding: 50px; color: #999;">Este usuario no tiene cartas en venta ahora mismo.</p>
    <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($userListings as $l): ?>
                <a href="/TFG/Codigo/cards/<?= $l['card_id'] ?>" class="card-item">
                    <div class="card-img-wrap">
                        <img src="<?= htmlspecialchars($l['image_url']) ?>" alt="Carta">
                    </div>
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($l['card_name']) ?></span>
                        <span class="card-price" style="font-weight: bold; color: #d32f2f;"><?= number_format($l['price'], 2) ?> €</span>
                        <span class="card-id">Estado: <?= ucfirst($l['condition']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>