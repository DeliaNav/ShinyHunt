<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - TCG Market</title>
    <link rel="stylesheet" href="/TFG/Codigo/public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Crear cuenta</h1>
        <form action="/TFG/Codigo/do-register" method="POST">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" placeholder="Tu nombre de usuario" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="phone" placeholder="Ej: 600000000">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="adress" placeholder="Calle inventada, Ficticio, 20">
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <div class="footer-link">
            ¿Ya tienes cuenta? <a href="/TFG/Codigo/login">Inicia sesión</a>
        </div>
    </div>
</body>
</html>