<?php
$rutaEnv = __DIR__ . '/../.env';

if (file_exists($rutaEnv)) {
    $lineas = file($rutaEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        if (strpos(trim($linea), '#') === 0) continue;
        // Solo procesamos si la línea contiene un "="
        if (strpos($linea, '=') !== false) {
            list($nombre, $valor) = explode('=', $linea, 2);
            $_ENV[trim($nombre)] = trim($valor);
        }
    }
} else {
    $_ENV['STRIPE_SECRET_KEY'] = 'FALTA_CLAVE';
    $_ENV['STRIPE_PUBLISHABLE_KEY'] = 'FALTA_CLAVE';
}

define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY']);
define('STRIPE_PUBLISHABLE_KEY', $_ENV['STRIPE_PUBLISHABLE_KEY']);
