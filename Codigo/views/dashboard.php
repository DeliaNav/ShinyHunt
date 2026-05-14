<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - TCG Market</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
    <p>Has entrado correctamente al panel de control de tu TFG.</p>
    
    <a href="/TFG/Codigo/logout">Cerrar sesión</a>
</body>
</html>
