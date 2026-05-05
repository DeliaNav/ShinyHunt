<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="container">
    <div class="search-results-title">
        <h1>Resultados de usuarios para <em>"<?= htmlspecialchars($query) ?>"</em></h1>
    </div>

    <?php if (empty($users)): ?>
        <div class="search-empty">
            <div class="search-empty-icon">👤</div>
            <p>No se encontraron usuarios con ese nombre.</p>
        </div>
    <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($users as $u): ?>
                <a href="/TFG/Codigo/usuario/<?= $u['id'] ?>" class="card-item">
                    <div class="card-img-wrap" style="border-radius: 50%; overflow: hidden; width: 100px; height: 100px; margin: 0 auto;">
                        <img src="<?= $u['avatar'] ?: '/TFG/Codigo/public/img/default-avatar.png' ?>" 
                             alt="<?= htmlspecialchars($u['username']) ?>"
                             style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                    <div class="card-info" style="text-align: center;">
                        <span class="card-name"><?= htmlspecialchars($u['username']) ?></span>
                        <span class="card-id">Miembro desde <?= date('Y', strtotime($u['create_in'])) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>
