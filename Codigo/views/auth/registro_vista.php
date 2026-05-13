<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - TCG Market</title>
    <link rel="stylesheet" href="/TFG/Codigo/public/css/registro.css">
</head>
<body>
    <div class="container">
        <h1>Crear cuenta</h1>

        <?php
        // Lee el error de los datos
        $error    = $_GET['error']    ?? '';
        $username = $_GET['username'] ?? '';
        $email    = $_GET['email']    ?? '';
        $phone    = $_GET['phone']    ?? '';
        $adress   = $_GET['adress']   ?? '';

        // Mensaje segun error
        $mensajes = [
            'campos_vacios'      => 'El nombre de usuario, email y contraseña son obligatorios.',
            'email_invalido'     => 'El formato del email no es válido.',
            'username_duplicado' => 'Ese nombre de usuario ya está en uso. Elige otro.',
            'email_duplicado'    => 'Ese email ya está registrado. ¿Ya tienes cuenta?',
            'error_general'      => 'Ha ocurrido un error al crear la cuenta. Inténtalo de nuevo.',
        ];

        if ($error && isset($mensajes[$error])):
        ?>
            <div class="alert-error"><?= $mensajes[$error] ?></div>
        <?php endif; ?>

        <form action="/TFG/Codigo/do-register" method="POST">

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username"
                       value="<?= htmlspecialchars($username) ?>"
                       placeholder="Tu nombre de usuario"
                       class="<?= $error === 'username_duplicado' ? 'input-error' : '' ?>"
                       required>
                <?php if ($error === 'username_duplicado'): ?>
                    <span class="field-error">Este nombre de usuario ya existe.</span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($email) ?>"
                       placeholder="correo@ejemplo.com"
                       class="<?= in_array($error, ['email_duplicado', 'email_invalido']) ? 'input-error' : '' ?>"
                       required>
                <?php if ($error === 'email_duplicado'): ?>
                    <span class="field-error">Este email ya está registrado.</span>
                <?php elseif ($error === 'email_invalido'): ?>
                    <span class="field-error">El formato del email no es válido.</span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="phone"
                       value="<?= htmlspecialchars($phone) ?>"
                       placeholder="Ej: 600000000">
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="adress"
                       value="<?= htmlspecialchars($adress) ?>"
                       placeholder="Calle inventada, Ficticio, 20">
            </div>

            <button type="submit">Registrarse</button>
        </form>

        <div class="footer-link">
            ¿Ya tienes cuenta? <a href="/TFG/Codigo/login">Inicia sesión</a>
        </div>
    </div>
</body>
</html>