<?php
require_once 'views/layout/header.php';

$imgUrl    = TCGApi::getImageUrl($card, 'high', 'webp');
$imageUrlLow = TCGApi::getImageUrl($card, 'low', 'png');

$typeColors = [
    'Fire'      => '#FF6B35',
    'Water'     => '#4FC3F7',
    'Grass'     => '#66BB6A',
    'Lightning' => '#FFD54F',
    'Psychic'   => '#CE93D8',
    'Fighting'  => '#A1887F',
    'Darkness'  => '#78909C',
    'Metal'     => '#B0BEC5',
    'Dragon'    => '#7E57C2',
    'Colorless' => '#BDBDBD',
    'Fairy'     => '#F48FB1',
];
?>

<div class="card-detail-wrap">
    <div class="container">

        <a href="javascript:history.back()" class="back-link">← Volver</a>
     
        <div class="card-detail">

            <!-- Imagen -->
            <div class="card-detail-left">
                <div class="card-detail-img-wrap">
                    <img src="<?= $imgUrl ?>"
                         alt="<?= htmlspecialchars($card['name']) ?>"
                         onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                </div>
            </div>

            <!-- Info -->
            <div class="card-detail-right">

                <div class="card-detail-header">
                    <div class="card-detail-types">
                        <?php foreach ($card['types'] ?? [] as $type): ?>
                            <span class="type-badge" style="background:<?= $typeColors[$type] ?? '#BDBDBD' ?>22;color:<?= $typeColors[$type] ?? '#888' ?>;border-color:<?= $typeColors[$type] ?? '#ccc' ?>44">
                                <?= htmlspecialchars($type) ?>
                            </span>
                        <?php endforeach; ?>
                        <?php if (!empty($card['rarity'])): ?>
                            <span class="rarity-badge"><?= htmlspecialchars($card['rarity']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="card-detail-name"><?= htmlspecialchars($card['name']) ?></h1>
                    <p class="card-detail-set">
                        <?= htmlspecialchars($card['set']['name'] ?? '') ?> · #<?= htmlspecialchars($card['localId'] ?? '') ?>
                    </p>
                </div>

                <div class="card-stats-grid">
                    <?php if (!empty($card['hp'])): ?>
                        <div class="stat-box"><span class="stat-label">HP</span><span class="stat-value"><?= $card['hp'] ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($card['stage'])): ?>
                        <div class="stat-box"><span class="stat-label">Fase</span><span class="stat-value"><?= htmlspecialchars($card['stage']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($card['evolveFrom'])): ?>
                        <div class="stat-box"><span class="stat-label">Evoluciona de</span><span class="stat-value"><?= htmlspecialchars($card['evolveFrom']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($card['retreat'])): ?>
                        <div class="stat-box"><span class="stat-label">Retirada</span><span class="stat-value"><?= $card['retreat'] ?> ⚪</span></div>
                    <?php endif; ?>
                    <?php if (!empty($card['illustrator'])): ?>
                        <div class="stat-box"><span class="stat-label">Ilustrador</span><span class="stat-value"><?= htmlspecialchars($card['illustrator']) ?></span></div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($card['pricing']['cardmarket'])): ?>
                    <?php $price = $card['pricing']['cardmarket']; ?>
                    <div class="price-box">
                        <div class="price-header">
                            <span class="price-label">💶 Precio en Cardmarket</span>
                            <span class="price-updated">Actualizado: <?= date('d/m/Y', strtotime($price['updated'])) ?></span>
                        </div>
                        <div class="price-grid">
                            <?php if (!empty($price['avg'])): ?><div class="price-item"><span>Precio medio</span><strong><?= number_format($price['avg'], 2) ?> €</strong></div><?php endif; ?>
                            <?php if (!empty($price['low'])): ?><div class="price-item"><span>Precio mínimo</span><strong><?= number_format($price['low'], 2) ?> €</strong></div><?php endif; ?>
                            <?php if (!empty($price['trend'])): ?><div class="price-item"><span>Tendencia</span><strong><?= number_format($price['trend'], 2) ?> €</strong></div><?php endif; ?>
                            <?php if (!empty($price['avg7'])): ?><div class="price-item"><span>Media 7 días</span><strong><?= number_format($price['avg7'], 2) ?> €</strong></div><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($card['abilities'])): ?>
                    <div class="card-section">
                        <h3 class="card-section-title">Habilidades</h3>
                        <?php foreach ($card['abilities'] as $ability): ?>
                            <div class="ability-card">
                                <div class="ability-header">
                                    <span class="ability-type"><?= htmlspecialchars($ability['type']) ?></span>
                                    <strong><?= htmlspecialchars($ability['name']) ?></strong>
                                </div>
                                <?php if (!empty($ability['effect'])): ?>
                                    <p class="ability-effect"><?= htmlspecialchars($ability['effect']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($card['attacks'])): ?>
                    <div class="card-section">
                        <h3 class="card-section-title">Ataques</h3>
                        <?php foreach ($card['attacks'] as $attack): ?>
                            <div class="attack-card">
                                <div class="attack-header">
                                    <div class="attack-cost">
                                        <?php foreach ($attack['cost'] ?? [] as $cost): ?>
                                            <span class="cost-badge" style="background:<?= $typeColors[$cost] ?? '#ccc' ?>33;color:<?= $typeColors[$cost] ?? '#888' ?>"><?= htmlspecialchars($cost) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <strong class="attack-name"><?= htmlspecialchars($attack['name']) ?></strong>
                                    <?php if (!empty($attack['damage'])): ?>
                                        <span class="attack-damage"><?= htmlspecialchars($attack['damage']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($attack['effect'])): ?>
                                    <p class="attack-effect"><?= htmlspecialchars($attack['effect']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($card['weaknesses']) || !empty($card['resistances'])): ?>
                    <div class="card-wr">
                        <?php if (!empty($card['weaknesses'])): ?>
                            <div>
                                <h4 class="wr-title">Debilidades</h4>
                                <?php foreach ($card['weaknesses'] as $w): ?>
                                    <span class="wr-badge wr-weak"><?= htmlspecialchars($w['type']) ?> <?= htmlspecialchars($w['value']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($card['resistances'])): ?>
                            <div>
                                <h4 class="wr-title">Resistencias</h4>
                                <?php foreach ($card['resistances'] as $r): ?>
                                    <span class="wr-badge wr-resist"><?= htmlspecialchars($r['type']) ?> <?= htmlspecialchars($r['value']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Botones -->
                <div class="card-actions">
                    <button id="btn-collection"
                            class="btn-collection <?= $inCollection ? 'btn-collection--active' : '' ?>"
                            disabled>
                        <?= $inCollection ? '✓ En tu Colección' : '+ Añadir a Mi Colección' ?>
                    </button>
                    <button id="btn-wishlist"
                            class="btn-wishlist <?= $inWishlist ? 'btn-wishlist--active' : '' ?>"
                            disabled>
                        <?= $inWishlist ? '✓ En tu Lista de Deseos' : '☆ Añadir a Lista de Deseos' ?>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Datos para los JS -->
<script>
window.cardData = {
    cardId:       "<?= htmlspecialchars($card['id']) ?>",
    cardName:     "<?= htmlspecialchars($card['name']) ?>",
    imageUrl:     "<?= htmlspecialchars($imageUrlLow) ?>",
    inCollection: <?= $inCollection ? 'true' : 'false' ?>,
    inWishlist:   <?= $inWishlist   ? 'true' : 'false' ?>
};
</script>
<script src="/TFG/Codigo/public/js/collection.js"></script>
<script src="/TFG/Codigo/public/js/wishlist.js"></script>

<?php require_once 'views/layout/footer.php'; ?>