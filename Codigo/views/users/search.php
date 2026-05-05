<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>


<div class="container search-main-container">
    <div class="search-results-title">
        <h1>Resultados de usuarios para <em>"<?= htmlspecialchars($query) ?>"</em></h1>
    </div>

    <?php if (empty($users)): ?>
        <div class="search-empty">
            <div class="search-empty-icon">👤</div>
            <p>No se encontraron usuarios con ese nombre.</p>
        </div>
    <?php else: ?>
        <div class="user-cards-grid">
            <?php foreach ($users as $u): ?>
                <a href="/TFG/Codigo/usuario/<?= $u['id'] ?>" class="user-card-link">
                    <div class="user-card-inner">
                        <div class="user-avatar-wrapper">
                            <img src="<?= $u['avatar'] ?: '/TFG/Codigo/public/img/default-avatar.png' ?>" 
                                 alt="<?= htmlspecialchars($u['username']) ?>"
                                 class="user-avatar-img">
                        </div>
                        <div class="user-card-info">
                            <span class="user-card-name"><?= htmlspecialchars($u['username']) ?></span>
                            <span class="user-card-date">Miembro desde <?= date('Y', strtotime($u['create_in'])) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>