<?php

$route = trim($_SERVER['REQUEST_URI'], '/');

$routes = require_once '../app/config/routes.php';

$route = trim(string: $_SERVER['REQUEST_URI'], characters: '/');

if (array_key_exists(key: $route, array: $routes)){
    $controllerName = $routes[$route]['controller'];
    $actionName = $routes[$route]['action'];

    require_once '../app/controllers/' . $controllerName . '.php';

    $controller = new $controllerName();
    $controller->$actionName();

} else {
    http_response_code(response_code: 404);
    echo "Página não encontrada";
}

?>