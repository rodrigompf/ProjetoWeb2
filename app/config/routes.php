<?php

return [
    '' => [
        'controller' => 'homePageController',
        'action' => 'index'
    ],
    'produtos' => [
        'controller' => 'produtosController',
        'action' => 'index'
    ],

    'produtos/create' => [
        'controller' => 'AdminController',
        'action' => 'create'  
    ],

    'adminZone' => [
        'controller' => 'adminController',
        'action' => 'index',
    ],

    'produtos/store' => [
        'controller' => 'produtosController',
        'action' => 'store'
    ],

    'login' => [
        'controller' => 'AuthController',
        'action' => 'login',
    ],

    'register' => [
        'controller' => 'AuthController',
        'action' => 'register',
    ],

    'logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
    ],

    'produtos/categoria/{categoria}' => [
        'controller' => 'produtosController',
        'action' => 'showCategoria'
    ],

    'produtos/search' => [
        'controller' => 'ProdutosController',
        'action' => 'search'
    ],
];


?>
