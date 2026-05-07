<?php
$pageTitle = 'Búsqueda · ShinnyHunt';
$extraCss  = 'buscador.css';
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container">

    <div class="search-results-title">
        <?php if ($query === ''): ?>
            <h1>Busca un Pokémon desde la barra superior</h1>
        <?php else: ?>
            <h1>Resultados para <em>"<?= htmlspecialchars($query) ?>"</em></h1>
        <?php endif; ?>
    </div>

    <?php if ($query === ''): ?>
        <div class="search-empty">
            <div class="search-empty-icon">🔍</div>
            <p>Escribe el nombre de un Pokémon en la barra de búsqueda de arriba</p>
        </div>

    <?php elseif (empty($cards)): ?>
        <div class="search-empty">
            <div class="search-empty-icon">😕</div>
            <p>No se encontraron cartas para <strong>"<?= htmlspecialchars($query) ?>"</strong></p>
            <span>Prueba con otro nombre o revisa la ortografía</span>
        </div>

    <?php else: ?>
        <div class="search-results-header">
            <span class="results-count">
                <?= count($cards) ?> resultado<?= count($cards) !== 1 ? 's' : '' ?>
            </span>
        </div>

        <div class="cards-grid">
            <?php foreach ($cards as $card):
                $imgUrl = TCGApi::getImageUrl($card);
            ?>
                <a href="/TFG/Codigo/cards/<?= htmlspecialchars($card['id']) ?>" class="card-item">
                    <div class="card-img-wrap">
                        <img src="<?= $imgUrl ?>"
                             alt="<?= htmlspecialchars($card['name']) ?>"
                             loading="lazy"
                             onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                    </div>
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($card['name']) ?></span>
                        <span class="card-id"><?= htmlspecialchars($card['id']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($cards) && count($cards) >= 40): ?>
        <div class="pagination-container" style="text-align: center; margin-top: 30px;">
            <?php $nextPage = ($page ?? 1) + 1; ?>
            <a href="/TFG/Codigo/buscar?q=<?= urlencode($query) ?>&page=<?= $nextPage ?>" class="btn-next-page">
                <span>Ver siguientes 40 cartas</span>
                <i class="arrow-icon">→</i>
            </a>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>