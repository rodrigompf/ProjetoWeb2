<?php

// Configurar cabeçalhos de segurança
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Iniciar sessão (necessário para CSRF e autenticação)
session_start();

// Função para gerar token CSRF
function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Função para verificar token CSRF
function verifyCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Verificar CSRF em requisições POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        http_response_code(403);
        die("Requisição inválida (CSRF detectado).");
    }
}

// Obter a URI atual da página
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Sanitizar a rota
$route = filter_var($route, FILTER_SANITIZE_URL);

// Carregar as rotas definidas no arquivo de configuração
$routes = require_once './app/config/routes.php';

// Verificar se a rota solicitada existe nas rotas estáticas
if (array_key_exists($route, $routes)) {
    // A rota é estática
    $controllerName = basename($routes[$route]['controller']);  // Proteção básica contra path traversal
    $actionName = $routes[$route]['action'];

    // Carregar o arquivo do controlador
    $controllerPath = './app/controllers/' . $controllerName . '.php';
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            http_response_code(404);
            echo "Ação não encontrada.";
        }
    } else {
        http_response_code(404);
        echo "Controlador não encontrado.";
    }
} else {
    // Verificar rotas dinâmicas
    foreach ($routes as $pattern => $routeInfo) {
        $patternRegex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);
        $patternRegex = str_replace('/', '\/', $patternRegex);

        if (preg_match('/^' . $patternRegex . '$/', $route, $matches)) {
            array_shift($matches);  // Remove o primeiro elemento (rota completa)

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
                    echo "Ação não encontrada.";
                }
            } else {
                http_response_code(404);
                echo "Controlador não encontrado.";
            }
            exit;
        }
    }

    // Se nenhuma rota foi encontrada, exibir erro 404
    http_response_code(404);
    echo "Página não encontrada";
}
