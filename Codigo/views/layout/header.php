<?php
// views/layout/header.php
require_once 'lib/Auth.php';
// require_once 'models/Message.php';

$unread = 0;
// if (Auth::check()) {
//     $msgModel = new Message();
//     $unread   = $msgModel->unreadCount(Auth::userId());
// }
?>  
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'TCG Market') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TFG/Codigo/public/css/header.css">
    <?php if (!empty($extraCss)): ?>
        <link rel="stylesheet" href="/TFG/Codigo/public/css/<?= htmlspecialchars($extraCss) ?>">
    <?php endif; ?>
</head>
<body>

<?php if (Auth::check()): ?>
<nav class="navbar">
    <div class="nav-inner">
        <a href="/TFG/Codigo/home" class="nav-logo">
            <span class="logo-icon">◆</span>
            <span class="logo-text">TCG<em>Market</em></span>
        </a>

        <div class="nav-search desktop-only">
            <form action="/TFG/Codigo/buscar" method="GET">
                <div class="search-wrap">
                    <input type="text" name="q" placeholder="Buscar Pokémon..." autocomplete="off"
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </div>
            </form>
            <form action="/TFG/Codigo/usuarios/buscar" method="GET" class="search-bar">
                <div class="search-wrap">
                    <input type="text" name="q" placeholder="Buscar usuarios..." required>
                    <button type="submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </form>
        </div>

        <ul class="nav-links">
            <li class="mobile-only mobile-search-container"><!--Para que desde un movil tambien se pueda buscar-->
                <form action="/TFG/Codigo/buscar" method="GET" class="mobile-form">
                    <div class="search-wrap">
                        <input type="text" name="q" placeholder="Pokémon...">
                        <button type="submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </button>
                    </div>
                </form>
                <form action="/TFG/Codigo/usuarios/buscar" method="GET" class="mobile-form">
                    <div class="search-wrap">
                        <input type="text" name="q" placeholder="Usuarios...">
                        <button type="submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                        </button>
                    </div>
                </form>
            </li>

            <li><a href="/TFG/Codigo/home" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'home') ? 'active' : '' ?>">Inicio</a></li>
            <li><a href="/TFG/Codigo/wishlist" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'market') ? 'active' : '' ?>">Mi lista de deseos</a></li>
            <li><a href="/TFG/Codigo/coleccion" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'coleccion') ? 'active' : '' ?>">Mi Colección</a></li>
            
            <li>
                <a href="/TFG/Codigo/carrito" class="nav-link nav-link--icon <?= str_contains($_SERVER['REQUEST_URI'], 'carrito') ? 'active' : '' ?>" title="Carrito">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <?php 
                    // Si el CartController pasa el conteo de items, lo mostramos
                    if (isset($cartCount) && $cartCount > 0): ?>
                        <span class="badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="/TFG/Codigo/mensajes" class="nav-link nav-link--icon <?= str_contains($_SERVER['REQUEST_URI'], 'mensajes') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <?php if ($unread > 0): ?>
                        <span class="badge"><?= $unread ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="/TFG/Codigo/ventas" class="nav-link nav-link--icon <?= str_contains($_SERVER['REQUEST_URI'], 'ventas') ? 'active' : '' ?>" title="Mis Ventas">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                </a>
            </li>
            <li>
                <a href="/TFG/Codigo/perfil" class="nav-link nav-avatar">
                    <span class="avatar-circle"><?= strtoupper(substr(Auth::username(), 0, 1)) ?></span>
                    <span><?= htmlspecialchars(Auth::username()) ?></span>
                </a>
            </li>
            <li>
                <a href="/TFG/Codigo/logout" class="nav-link nav-link--logout">Salir</a>
            </li>
            
        </ul>

        <button class="nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
    </div>
</nav>
<?php endif; ?>

<main class="main-content">