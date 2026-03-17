<?php
class Router {
    private $routes = [];

    public function add($path, $controllerAction) {
        // Guardamos la ruta y la acción
        $this->routes[$path] = $controllerAction;
    }

    public function dispatch($uri) {
        $path = parse_url($uri, PHP_URL_PATH);
        $controllerFile = "UNKNOWN";

        foreach ($this->routes as $route => $controllerAction) {
            // Convertir {param} a regex
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([\w-]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                list($controllerName, $action) = explode('@', $controllerAction);
                $controllerFile = "controllers/{$controllerName}.php";

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $controllerName();
                    // Pasar parámetros capturados (si hay)
                    $params = array_slice($matches, 1);
                    if (method_exists($controller, $action)) {
                        call_user_func_array([$controller, $action], $params);
                    } else {
                        $this->error404($controllerFile);
                    }
                } else {
                    $this->error404($controllerFile);
                }
                return; // ruta encontrada, salimos
            }
        }

        // No se encontró ninguna ruta
        $this->error404($controllerFile);
    }

    private function error404($controllerFile) {
        header("HTTP/1.0 404 Not Found");
        echo "404 Página no encontrada: " . $controllerFile;
        exit;
    }
}
