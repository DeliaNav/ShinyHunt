<?php
$pageTitle = 'Inicio · TCGMarket';
require_once 'views/layout/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="/TFG/Codigo/public/css/home.css">
</head>
<body>
    


<div class="page-hero">
    <div class="page-hero-content">
        <h1>Hola, <?= htmlspecialchars(Auth::username()) ?></h1>
        <p>Explora las últimas cartas o busca tu Pokémon favorito</p>
    </div>
</div>

<div class="container">
    <div class="section-header">
        <h2 class="section-title">Cartas destacadas</h2>
        <p class="section-sub">Selección aleatoria del catálogo</p>
    </div>

    <?php if (empty($cards)): ?>
        <div class="empty-state">
            <p>No se pudieron cargar las cartas. Comprueba la conexión con la API.</p>
        </div>
    <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($cards as $card):
                $imgUrl = "https://assets.tcgdex.net/en/{$card['set']['id']}/{$card['localId']}/low.png";
            ?>
                <a href="/TFG/Codigo/cards/<?= htmlspecialchars($card['set']['id'] . '-' . $card['localId']) ?>" class="card-item">
                    <div class="card-img-wrap">
                        <img src="<?= $imgUrl ?>"
                             alt="<?= htmlspecialchars($card['name']) ?>"
                             loading="lazy"
                             onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                    </div>
                    <div class="card-info">
                        <span class="card-name"><?= htmlspecialchars($card['name']) ?></span>
                        <span class="card-set"><?= htmlspecialchars($card['set']['name'] ?? '') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

<?php require_once 'views/layout/footer.php'; ?>
