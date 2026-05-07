<?php
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/php_errors.log');

session_start();
require_once 'lib/Auth.php';
require_once 'lib/Router.php';

$router = new Router();

$router->add('/TFG/Codigo/login', 'LoginController@index');
$router->add('/TFG/Codigo/auth', 'LoginController@authenticate');

$router->add('/TFG/Codigo/registro', 'LoginController@showRegister');
$router->add('/TFG/Codigo/do-register', 'LoginController@register');

$router->add('/TFG/Codigo/', 'WelcomeController@index');
$router->add('/TFG/Codigo/home', 'HomeController@index');

$router->add('/TFG/Codigo/buscar', 'buscadorController@search');
$router->add('/TFG/Codigo/cards/{id}', 'CardController@show');

// Buscador de usuarios y perfil ajeno
$router->add('/TFG/Codigo/usuarios/buscar', 'UserController@search');
$router->add('/TFG/Codigo/usuario/{id}', 'UserController@show');

// Colección — específicas primero
$router->add('/TFG/Codigo/coleccion/add', 'CollectionController@add');
$router->add('/TFG/Codigo/coleccion/remove', 'CollectionController@remove');
$router->add('/TFG/Codigo/coleccion', 'CollectionController@index');

// Wishlist — específicas primero
$router->add('/TFG/Codigo/wishlist/add', 'WishListController@add');
$router->add('/TFG/Codigo/wishlist/remove', 'WishListController@remove');
$router->add('/TFG/Codigo/wishlist', 'WishListController@index');

// Tu perfil edicion y ajustes
$router->add('/TFG/Codigo/perfil/update', 'ProfileController@update');
$router->add('/TFG/Codigo/perfil/password', 'ProfileController@password');
$router->add('/TFG/Codigo/perfil/avatar/delete', 'ProfileController@avatar');
$router->add('/TFG/Codigo/perfil/avatar', 'ProfileController@avatar');
$router->add('/TFG/Codigo/perfil', 'ProfileController@index');

// Vender — específicas primero, dinámica al final
$router->add('/TFG/Codigo/vender/cancelar', 'ListingController@cancel');
$router->add('/TFG/Codigo/vender', 'ListingController@store');
$router->add('/TFG/Codigo/vender/{cardId}', 'ListingController@create');

// Carrito — específicas primero
$router->add('/TFG/Codigo/carrito/add', 'CartController@add');
$router->add('/TFG/Codigo/carrito/remove', 'CartController@remove');
$router->add('/TFG/Codigo/carrito/checkout', 'CartController@checkout');
$router->add('/TFG/Codigo/carrito', 'CartController@index');

// Ventas — específicas primero
$router->add('/TFG/Codigo/ventas/cancelar', 'SalesController@cancel');
$router->add('/TFG/Codigo/ventas',          'SalesController@index');

// Chats — especificas primero, luego dinamica
$router->add('/TFG/Codigo/mensajes/send','MessageController@send');
$router->add('/TFG/Codigo/mensajes/{username}', 'MessageController@show');
$router->add('/TFG/Codigo/mensajes', 'MessageController@index');

$router->dispatch($_SERVER['REQUEST_URI']);
