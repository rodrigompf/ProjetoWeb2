<?php

// Obter a URI atual da página
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Carregar as rotas definidas no arquivo de configuração
$routes = require_once './app/config/routes.php';

// Verificar se a rota solicitada existe nas rotas estáticas
if (array_key_exists($route, $routes)) {
    // A rota é estática (ex: "home", "contactos")
    $controllerName = $routes[$route]['controller'];  // Nome do controlador associado
    $actionName = $routes[$route]['action'];  // Nome da ação no controlador

    // Carregar o arquivo do controlador
    require_once './app/controllers/' . $controllerName . '.php';

    // Instanciar o controlador e chamar a ação correspondente
    $controller = new $controllerName();
    $controller->$actionName();  // Chamar a função definida em $actionName
} else {
    // Caso a rota não seja estática, verificar rotas dinâmicas (ex: "produtos/categoria/{categoria}")
    foreach ($routes as $pattern => $routeInfo) {
        // Converter a rota dinâmica para uma expressão regular
        $patternRegex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);  // Substitui {param} por um grupo de captura
        $patternRegex = str_replace('/', '\/', $patternRegex);  // Escapa as barras para a regex

        // Verificar se a URI atual corresponde ao padrão da rota dinâmica
        if (preg_match('/^' . $patternRegex . '$/', $route, $matches)) {
            // Capturar os parâmetros da rota dinâmica (ex: {categoria} => valor)
            array_shift($matches); // Remove o primeiro elemento, que é a rota completa

            // Obter o controlador e ação correspondentes
            $controllerName = $routeInfo['controller'];
            $actionName = $routeInfo['action'];

            // Carregar o arquivo do controlador
            require_once './app/controllers/' . $controllerName . '.php';

            // Instanciar o controlador e chamar a ação com os parâmetros capturados da rota
            $controller = new $controllerName();
            $controller->$actionName(...$matches);  // Passar os parâmetros para a ação do controlador
            exit;  // Finaliza o processamento caso a rota dinâmica tenha sido encontrada
        }
    }

    // Se nenhuma rota foi encontrada, exibir um erro 404 (Página não encontrada)
    http_response_code(404);  // Define o código de resposta HTTP como 404
    echo "Página não encontrada";  // Exibe mensagem de erro
}
