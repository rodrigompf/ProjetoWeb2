<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cabeçalhos de segurança
header("X-Frame-Options: SAMEORIGIN"); // Impede que o conteúdo do site seja carregado em um iframe em outros sites.
header("X-Content-Type-Options: nosniff"); // Impede que o navegador adivinhe o tipo de conteúdo, garantindo que o tipo de conteúdo seja verificado corretamente.
header("Referrer-Policy: no-referrer"); // Impede que o cabeçalho de referência seja enviado para outros sites.
header("Permissions-Policy: geolocation=(), camera=()"); // Restringe o uso de funcionalidades como geolocalização e câmera.

// **Novos cabeçalhos de segurança**

header("Strict-Transport-Security: max-age=31536000; includeSubDomains"); // Se eventualmente se usar HTTPS, este cabeçalho garante que o navegador só aceda ao seu site por HTTPS.
header("Access-Control-Allow-Origin: https://seusite.com"); // Restringe o CORS a um domínio específico.
header("Access-Control-Allow-Methods: GET, POST"); // Permite apenas os métodos GET e POST para requisições cross-origin.

// Geração de token CSRF
function generateCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Gera um token CSRF seguro e armazena-o na sessão.
    }
    return $_SESSION['csrf_token'];
}

// Validação do token CSRF
function validateCsrfToken($token)
{
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) { // Compara o token enviado com o armazenado na sessão de forma segura
        logError("Falha na validação do token CSRF.");
        http_response_code(403); // Se falhar a validação, retorna erro 403
        die('Token CSRF inválido.');
    }
}

// Obter URI e sanitizar
$route = filter_var(trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), FILTER_SANITIZE_URL); // Filtra a URI para evitar injeção de código ou caracteres não seguros

// Busca das rotas
$routes = require_once './app/config/routes.php';

// **Limitação de taxa simples**
// Monitora as requisições para limitar o número de requisições feitas em um curto período.
if (!isset($_SESSION['request_count'])) {
    $_SESSION['request_count'] = 0;
    $_SESSION['request_time'] = time(); // Regista o tempo da primeira requisição
}

$_SESSION['request_count']++;

if ($_SESSION['request_count'] > 100 && (time() - $_SESSION['request_time']) < 60) {
    http_response_code(429); // Retorna erro 429 se o limite de requisições for excedido
    die('Muitas requisições.');
}

if ((time() - $_SESSION['request_time']) >= 60) {
    $_SESSION['request_count'] = 0; // Reseta o contador a cada minuto
    $_SESSION['request_time'] = time(); // Regista o tempo da nova janela
}

// Roteamento dinâmico e estático
if (array_key_exists($route, $routes)) {
    // Roteamento estático
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
            array_shift($matches); // Remove o match completo

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

    // Se não há rota, erro 404
    http_response_code(404);
    echo "Página não encontrada.";
}

// Função para registar erros
function logError($message)
{
    error_log($message, 3, './logs/error.log'); // Grava os erros no ficheiro de log
}

// **Segurança adicional de sessão**
// Regenera o ID da sessão para evitar ataques de sessão fixa
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(); // Regenera o ID da sessão para evitar roubo de sessão
    $_SESSION['initiated'] = true; // Marca a sessão como iniciada
}