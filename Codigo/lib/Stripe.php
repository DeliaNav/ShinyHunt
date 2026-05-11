<?php
$rutaEnv = __DIR__ . '/../.env';

if (file_exists($rutaEnv)) {
    $lineas = file($rutaEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        if (strpos(trim($linea), '#') === 0) continue;
        if (strpos($linea, '=') !== false) {
            list($nombre, $valor) = explode('=', $linea, 2);
            $_ENV[trim($nombre)] = trim($valor);
        }
    }
}

define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY'] ?? 'clave_no_encontrada');
define('STRIPE_PUBLISHABLE_KEY', $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? 'clave_no_encontrada');
