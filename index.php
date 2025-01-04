<?php
// Get current URI and sanitize it
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Load routes from configuration
$routes = require_once './app/config/routes.php';

// Match static or dynamic routes
if (array_key_exists($route, $routes)) {
    // Static route handling
    $controllerName = basename($routes[$route]['controller']);
    $actionName = $routes[$route]['action'];

    $controllerPath = './app/controllers/' . $controllerName . '.php';
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            http_response_code(404);
            echo "Action not found.";
        }
    } else {
        http_response_code(404);
        echo "Controller not found.";
    }
} else {
    // Check dynamic routes
    foreach ($routes as $pattern => $routeInfo) {
        $patternRegex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);
        $patternRegex = str_replace('/', '\/', $patternRegex);

        if (preg_match('/^' . $patternRegex . '$/', $route, $matches)) {
            array_shift($matches); // Remove the full match

            $controllerName = basename($routeInfo['controller']);
            $actionName = $routeInfo['action'];

            $controllerPath = './app/controllers/' . $controllerName . '.php';
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    $controller->$actionName(...$matches);
                } else {
                    http_response_code(404);
                    echo "Action not found.";
                }
            } else {
                http_response_code(404);
                echo "Controller not found.";
            }
            exit;
        }
    }

    // If no route matches, return 404
    http_response_code(404);
    echo "Page not found.";
}
