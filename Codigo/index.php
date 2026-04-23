<?php

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

$router->dispatch($_SERVER['REQUEST_URI']);
