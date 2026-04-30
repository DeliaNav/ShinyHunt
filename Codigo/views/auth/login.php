<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TCG Market</title>
    <link rel="stylesheet" href="./public/css/login.css">
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <p style="text-align: center; font-size: 0.8rem; margin-top: 1rem;">delia 12345678</p>


    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == '1') {
            echo '<p class="error">Usuario o contraseña incorrectos.</p>';
        } elseif ($_GET['error'] == 'vacio') {
            echo '<p class="error">Por favor, rellena todos los campos.</p>';
        }
    }
    ?>
    

    <form action="/TFG/Codigo/auth" method="POST">
            <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Entrar</button>
    </form>
    
    <p style="text-align: center; font-size: 0.8rem; margin-top: 1rem;">
        ¿No tienes cuenta? <a href="/TFG/Codigo/registro">Regístrate aquí</a>
    </p>
</div>

</body>
</html>