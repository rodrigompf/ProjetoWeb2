<?php

return [
    // Página Inicial
    '' => [
        'controller' => 'homePageController', // Controlador responsável pela página inicial
        'action' => 'index' // Ação para exibir a página inicial
    ],

    // Produtos
    'produtos' => [
        'controller' => 'produtosController', // Controlador para gestão de produtos
        'action' => 'index' // Ação para listar todos os produtos
    ],
    'produtos/create' => [
        'controller' => 'AdminController', // Controlador de administração
        'action' => 'create' // Ação para criar um novo produto
    ],
    'produtos/store' => [
        'controller' => 'produtosController', // Controlador para salvar produtos
        'action' => 'store' // Ação para guardar os dados do novo produto
    ],
    'produtos/categoria/{categoria}' => [
        'controller' => 'produtosController', // Controlador para categorias de produtos
        'action' => 'showCategoria' // Ação para listar produtos de uma categoria específica
    ],
    'produtos/search' => [
        'controller' => 'ProdutosController', // Controlador responsável pela pesquisa
        'action' => 'search' // Ação para realizar a pesquisa de produtos
    ],
    'produtos/edit' => [
        'controller' => 'AdminController', // Controlador para administração
        'action' => 'editList' // Ação para listar produtos editáveis
    ],
    'produtos/edit/{id}' => [
        'controller' => 'AdminController', // Controlador para edição de um produto específico
        'action' => 'editForm' // Ação para mostrar o formulário de edição
    ],
    'produtos/delete/{id}' => [
        'controller' => 'AdminController', // Controlador para administração
        'action' => 'delete' // Ação para apagar um produto específico
    ],

    // Gestão de Stock
    'stock-management' => [
        'controller' => 'StockManagementController', // Controlador responsável pela gestão de stock
        'action' => 'index' // Ação para exibir o painel de gestão de stock
    ],
    'stock-management/add' => [
        'controller' => 'StockManagementController', // Controlador responsável por adicionar stock
        'action' => 'addStock' // Ação para adicionar novos itens ao stock
    ],

    // Categorias
    'categorias' => [
        'controller' => 'CategoriasController', // Controlador para gerir categorias
        'action' => 'index' // Ação para listar todas as categorias
    ],
    'categorias/create' => [
        'controller' => 'CategoriasController', // Controlador para criar categorias
        'action' => 'create' // Ação para adicionar uma nova categoria
    ],
    'categorias/delete/{id}' => [
        'controller' => 'CategoriasController', // Controlador para eliminar categorias
        'action' => 'delete' // Ação para apagar uma categoria específica
    ],
    'categorias/edit/{id}' => [
        'controller' => 'CategoriasController', // Controlador para editar categorias
        'action' => 'edit' // Ação para alterar uma categoria específica
    ],

    // Zona de Administração
    'adminZone' => [
        'controller' => 'adminController', // Controlador da área de administração
        'action' => 'index' // Ação para exibir o painel de administração
    ],

    // Autenticação
    'login' => [
        'controller' => 'AuthController', // Controlador de autenticação
        'action' => 'login' // Ação para iniciar sessão
    ],
    'register' => [
        'controller' => 'AuthController', // Controlador para registo de utilizadores
        'action' => 'register' // Ação para registar um novo utilizador
    ],
    'logout' => [
        'controller' => 'AuthController', // Controlador de autenticação
        'action' => 'logout' // Ação para terminar a sessão
    ],

    // Promoções
    'todasPromocoes' => [
        'controller' => 'PromocoesController', // Controlador para promoções
        'action' => 'index' // Ação para listar todas as promoções disponíveis
    ],

    // Carrinho de Compras
    'cart' => [
        'controller' => 'CartController', // Controlador para gerir o carrinho de compras
        'action' => 'index' // Ação para exibir o carrinho
    ],
    'cart/add/{id}' => [
        'controller' => 'CartController', // Controlador para adicionar produtos ao carrinho
        'action' => 'add' // Ação para adicionar um produto ao carrinho
    ],
    'cart/remove/{id}' => [
        'controller' => 'CartController', // Controlador para remover produtos do carrinho
        'action' => 'remove' // Ação para retirar um produto do carrinho
    ],
    'cart/buy' => [
        'controller' => 'CartController', // Controlador para finalizar a compra
        'action' => 'buy' // Ação para efetuar a compra dos itens no carrinho
    ],
    'cart/history' => [
        'controller' => 'CartController', // Controlador para o histórico de compras
        'action' => 'history' // Ação para exibir o histórico de compras do utilizador
    ],
    'cart/history/details/{id}' => [
        'controller' => 'CartController', // Controlador para detalhes de uma compra no histórico
        'action' => 'details' // Ação para exibir detalhes de uma compra específica
    ],
];
