<?php

return [
    // Home
    '' => [
        'controller' => 'homePageController',
        'action' => 'index'
    ],

    // Produtos
    'produtos' => [
        'controller' => 'produtosController',
        'action' => 'index'
    ],
    'produtos/create' => [
        'controller' => 'AdminController',
        'action' => 'create'
    ],
    'produtos/store' => [
        'controller' => 'produtosController',
        'action' => 'store'
    ],
    'produtos/categoria/{categoria}' => [
        'controller' => 'produtosController',
        'action' => 'showCategoria'
    ],
    'produtos/search' => [
        'controller' => 'ProdutosController',
        'action' => 'search'
    ],
    'produtos/edit' => [
        'controller' => 'AdminController',
        'action' => 'editList'
    ],
    'produtos/edit/{id}' => [
        'controller' => 'AdminController',
        'action' => 'editForm'
    ],
    'produtos/delete/{id}' => [
        'controller' => 'AdminController',
        'action' => 'delete'
    ],

    // Stock Management
    'stock-management' => [
        'controller' => 'StockManagementController',
        'action' => 'index'
    ],
    'stock-management/add' => [
        'controller' => 'StockManagementController',
        'action' => 'addStock'
    ],

    // Categorias
    'categorias' => [
        'controller' => 'CategoriasController',
        'action' => 'index'
    ],
    'categorias/create' => [
        'controller' => 'CategoriasController',
        'action' => 'create'
    ],
    'categorias/delete/{id}' => [
        'controller' => 'CategoriasController',
        'action' => 'delete'
    ],
    'categorias/edit/{id}' => [
        'controller' => 'CategoriasController',
        'action' => 'edit'
    ],

    // Admin Zone
    'adminZone' => [
        'controller' => 'adminController',
        'action' => 'index'
    ],

    // Authentication
    'login' => [
        'controller' => 'AuthController',
        'action' => 'login'
    ],
    'register' => [
        'controller' => 'AuthController',
        'action' => 'register'
    ],
    'logout' => [
        'controller' => 'AuthController',
        'action' => 'logout'
    ],

    // Promoções
    'todasPromocoes' => [
        'controller' => 'PromocoesController',
        'action' => 'index'
    ],

    // Cart
    'cart' => [
        'controller' => 'CartController',
        'action' => 'index'
    ],
    'cart/add/{id}' => [
        'controller' => 'CartController',
        'action' => 'add'
    ],
    'cart/remove/{id}' => [
        'controller' => 'CartController',
        'action' => 'remove'
    ],
    'cart/buy' => [
        'controller' => 'CartController',
        'action' => 'buy'
    ],
    'cart/history' => [
        'controller' => 'CartController',
        'action' => 'history'
    ],
    'cart/history/details/{id}' => [
        'controller' => 'CartController',
        'action' => 'details'
    ],
];
