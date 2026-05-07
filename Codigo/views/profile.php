<?php require_once __DIR__ . '/../views/layout/header.php'; ?>
<link rel="stylesheet" href="/TFG/Codigo/public/css/profile.css">

<div class="profile-wrap">
    <div class="container">

        <!-- Cabecera del perfil -->
        <div class="profile-hero">
            <div class="profile-avatar-wrap">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="profile-avatar">
                    
                    <form action="/TFG/Codigo/perfil/avatar" method="POST"> <input type="hidden" name="action" value="delete">
                        <button type="submit" class="avatar-delete-btn" onclick="return confirm('¿Borrar foto?')">
                            &times;
                        </button>
                    </form>
                <?php else: ?>
                    <div class="profile-avatar-placeholder">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <label class="avatar-edit-btn" title="Cambiar avatar">
                    ✎
                    <input type="file" name="avatar" accept="image/*" id="avatar-input" hidden>
                </label>
            </div>

            <div class="profile-hero-info">
                <h1 class="profile-username"><?= htmlspecialchars($user['username']) ?></h1>
                <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                <?php if (!empty($user['bio'])): ?>
                    <p class="profile-bio"><?= htmlspecialchars($user['bio']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Estadísticas -->
            <div class="profile-stats">
                <a href="/TFG/Codigo/coleccion" class="stat-card">
                    <span class="stat-number"><?= $totalCollection ?></span>
                    <span class="stat-label">En colección</span>
                </a>
                <a href="/TFG/Codigo/wishlist" class="stat-card">
                    <span class="stat-number"><?= $totalWishlist ?></span>
                    <span class="stat-label">En wishlist</span>
                </a>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if ($error): ?>
            <div class="profile-alert profile-alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="profile-alert profile-alert--success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="profile-grid">

            <!-- Editar datos personales -->
            <section class="profile-card">
                <h2 class="profile-card-title">Datos personales</h2>
                <form action="/TFG/Codigo/perfil/update" method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" id="username" name="username"
                               value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" id="phone" name="phone"
                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="bio">Biografía</label>
                        <textarea id="bio" name="bio" rows="3"
                                  placeholder="Cuéntanos algo sobre ti..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn-save">Guardar cambios</button>
                </form>
            </section>

            <!-- Cambiar contraseña -->
            <section class="profile-card">
                <h2 class="profile-card-title">Cambiar contraseña</h2>
                <form action="/TFG/Codigo/perfil/password" method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="current_password">Contraseña actual</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nueva contraseña</label>
                        <input type="password" id="new_password" name="new_password"
                               minlength="8" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar nueva contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-save">Cambiar contraseña</button>
                </form>
            </section>

        </div>
        <div class="profile-card" style="border-color: #f9d2d2;">
            <h3 class="profile-card-title" style="color: #9A0002;">Zona Peligrosa</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 1.5rem;">
                Una vez que elimines tu cuenta, no hay marcha atrás. Por favor, asegúrate.
            </p>
            
            <form action="/TFG/Codigo/delete-account" method="POST" onsubmit="return confirm('¿Estás COMPLETAMENTE seguro? Esta acción no se puede deshacer.');">
                <button type="submit" class="btn-danger" style="background-color: #9A0002; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    Eliminar mi cuenta permanentemente
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Avatar upload via fetch -->
<form id="avatar-form" action="/TFG/Codigo/perfil/avatar" method="POST"
      enctype="multipart/form-data" hidden></form>

<script src="/TFG/Codigo/public/js/profile.js"></script>
<?php require_once __DIR__ . '/../views/layout/footer.php'; ?>