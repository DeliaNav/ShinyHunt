<?php
require 'lib/Router.php';//aconseja reemplazar el require por require_once

$uri_dir = "./Codigo";

// Crear instancia del Router
$router = new Router();

// Definir rutas simples
$router->add("${uri_dir}/index.php", 'MainController@index');
$router->add("${uri_dir}/api/empleados", 'EmpleadosController@apiEmpleados');
$router->add("${uri_dir}/api/empleados/{id}", 'EmpleadosController@apiEmpleados');

$router->add("${uri_dir}/api/departamentos", 'DepartamentoController@apiDepartamento');
$router->add("${uri_dir}/api/departamentos/{id}", 'DepartamentoController@apiDepartamento');

$router->add("${uri_dir}/api/skill", 'SkillController@apiSkill');
$router->add("${uri_dir}/api/skill/{id}", 'SkillController@apiSkill');

// Procesar la ruta actual
$router->dispatch($_SERVER['REQUEST_URI']);
