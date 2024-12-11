<?php

// Obter a URI atual
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Carregar as rotas
$routes = require_once './app/config/routes.php';

// Verificar se a rota existe
if (array_key_exists($route, $routes)) {
    // Rota estática
    $controllerName = $routes[$route]['controller'];
    $actionName = $routes[$route]['action'];

    // Carregar o controlador
    require_once './app/controllers/' . $controllerName . '.php';

    // Instanciar o controlador e chamar a ação
    $controller = new $controllerName();
    $controller->$actionName();
} else {
    // Verificar rotas dinâmicas, como 'produtos/categoria/{categoria}'
    foreach ($routes as $pattern => $routeInfo) {
        $patternRegex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);
        $patternRegex = str_replace('/', '\/', $patternRegex);

        if (preg_match('/^' . $patternRegex . '$/', $route, $matches)) {
            // Capturar os parâmetros da rota dinâmica
            array_shift($matches); // Remove o primeiro elemento (rota completa)
            $controllerName = $routeInfo['controller'];
            $actionName = $routeInfo['action'];

            // Carregar o controlador
            require_once './app/controllers/' . $controllerName . '.php';

            // Instanciar o controlador e chamar a ação com os parâmetros
            $controller = new $controllerName();
            $controller->$actionName(...$matches);
            exit;
        }
    }

    // Se nenhuma rota foi encontrada, exibir erro 404
    http_response_code(404);
    echo "Página não encontrada";
}
