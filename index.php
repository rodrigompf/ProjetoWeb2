<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers segurança
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Permissions-Policy: geolocation=(), camera=()");

// CSRF Token geração
function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token validação
function validateCsrfToken($token)
{
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        logError("CSRF token validation failed.");
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}

// Obter URI e sanitizar
$route = filter_var(trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), FILTER_SANITIZE_URL);

// Busca das routes
$routes = require_once './app/config/routes.php';

// rotas dinamicas e estaticas
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
    // verificar rotas dinamicas
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

    // se não há route, erro 404
    http_response_code(404);
    echo "Page not found.";
}
// Função erros
function logError($message)
{
    error_log($message, 3, './logs/error.log');
}
