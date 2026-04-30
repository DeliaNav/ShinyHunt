<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

session_start();
require_once 'lib/Auth.php';
require_once 'lib/Router.php';

$router = new Router();

$router->add('/TFG/Codigo/login', 'LoginController@index');
$router->add('/TFG/Codigo/auth', 'LoginController@authenticate');

$router->add('/TFG/Codigo/registro', 'LoginController@showRegister');
$router->add('/TFG/Codigo/do-register', 'LoginController@register');

$router->add('/TFG/Codigo/welcome', 'WelcomeController@index');
$router->add('/TFG/Codigo/home', 'HomeController@index');

$router->add('/TFG/Codigo/buscar', 'buscadorController@search');
$router->add('/TFG/Codigo/cards/{id}', 'CardController@show');

// Específicas antes que la genérica
$router->add('/TFG/Codigo/coleccion/add',    'CollectionController@add');
$router->add('/TFG/Codigo/coleccion/remove', 'CollectionController@remove');
$router->add('/TFG/Codigo/coleccion',        'CollectionController@index');

// Específicas antes que la genérica
$router->add('/TFG/Codigo/wishlist/add',    'WishListController@add');
$router->add('/TFG/Codigo/wishlist/remove', 'WishListController@remove');
$router->add('/TFG/Codigo/wishlist',        'WishListController@index');

// Perfil — específicas antes que la genérica
$router->add('/TFG/Codigo/perfil/update',   'ProfileController@update');
$router->add('/TFG/Codigo/perfil/password', 'ProfileController@password');
$router->add('/TFG/Codigo/perfil/avatar',   'ProfileController@avatar');
$router->add('/TFG/Codigo/perfil',          'ProfileController@index');

$router->dispatch($_SERVER['REQUEST_URI']);