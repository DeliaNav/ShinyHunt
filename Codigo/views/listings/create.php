<?php require_once __DIR__ . '/../../views/layout/header.php'; ?>

<div class="listing-wrap">
    <div class="container">
        <a href="/TFG/Codigo/cards/<?= htmlspecialchars($card['id']) ?>" class="back-link">← Volver</a>

        <div class="listing-layout">

            <!-- Imagen carta -->
            <div class="listing-card-preview">
                <img src="<?= TCGApi::getImageUrl($card, 'high', 'webp') ?>"
                     alt="<?= htmlspecialchars($card['name']) ?>"
                     onerror="this.src='/TFG/Codigo/public/img/card-placeholder.png'">
                <p class="listing-card-name"><?= htmlspecialchars($card['name']) ?></p>
                <p class="listing-card-set"><?= htmlspecialchars($card['set']['name'] ?? $card['id']) ?></p>
            </div>

            <!-- Formulario -->
            <div class="listing-form-wrap">
                <h1 class="listing-title">Poner en venta</h1>

                <?php if (isset($_GET['error'])): ?>
                    <div class="listing-alert listing-alert--error">
                        <?php if ($_GET['error'] === 'duplicate'): ?>
                            Ya tienes un anuncio activo para esta carta. Cancélalo primero.
                        <?php else: ?>
                            Revisa los datos del formulario.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($existing): ?>
                    <div class="listing-alert listing-alert--warning">
                        Ya tienes esta carta en venta por <strong><?= number_format($existing['price'], 2) ?> €</strong>.
                        Para crear un nuevo anuncio, cancela el actual primero.
                    </div>
                    <form action="/TFG/Codigo/vender/cancelar" method="POST">
                        <input type="hidden" name="listing_id" value="<?= $existing['id'] ?>">
                        <input type="hidden" name="card_id"    value="<?= htmlspecialchars($card['id']) ?>">
                        <button type="submit" class="btn-cancel-listing">Cancelar anuncio actual</button>
                    </form>
                <?php else: ?>
                    <form action="/TFG/Codigo/vender" method="POST" class="listing-form">
                        <input type="hidden" name="card_id"   value="<?= htmlspecialchars($card['id']) ?>">
                        <input type="hidden" name="card_name" value="<?= htmlspecialchars($card['name']) ?>">
                        <input type="hidden" name="image_url" value="<?= TCGApi::getImageUrl($card) ?>">

                        <div class="form-group">
                            <label for="price">Precio por unidad (€)</label>
                            <div class="price-input-wrap">
                                <span class="price-symbol">€</span>
                                <input type="number" id="price" name="price"
                                       min="0.01" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" id="quantity" name="quantity"
                                   min="1" max="99" value="1" required>
                        </div>

                        <div class="form-group">
                            <label for="condition">Estado de la carta</label>
                            <select id="condition" name="condition" required>
                                <option value="mint">Mint (M) — Perfecta</option>
                                <option value="near_mint" selected>Near Mint (NM) — Casi perfecta</option>
                                <option value="excellent">Excellent (EX) — Excelente</option>
                                <option value="good">Good (GD) — Buena</option>
                                <option value="light_played">Light Played (LP) — Poco jugada</option>
                                <option value="played">Played (PL) — Jugada</option>
                                <option value="poor">Poor (PR) — Mal estado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción <span class="optional">(opcional)</span></label>
                            <textarea id="description" name="description" rows="3"
                                      placeholder="Añade detalles sobre el estado, si tiene marca, etc."></textarea>
                        </div>

                        <button type="submit" class="btn-publish">Publicar anuncio</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>
