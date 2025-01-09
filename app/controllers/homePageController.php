<?php

require_once './app/controllers/bannerController.php'; // Incluir o controlador de Banners
require_once './app/models/homeModel.php';

class HomePageController
{
    private $bannerController;

    public function __construct()
    {
        // Inicializar o controlador de Banners
        $this->bannerController = new BannerController();
    }

    /**
     * Exibir a página inicial.
     */
    public function index(): void
    {
        // Criar uma instância do modelo HomeModel para interagir com os dados da página inicial
        $homeModel = new HomeModel();

        // Obter os 12 produtos com desconto aleatórios para exibir na homepage
        $produtosComDesconto = $homeModel->getProdutosComDesconto(12);

        // Obter os banners utilizando o BannerController
        $banners = $this->bannerController->getBanners();

        // Determinar o índice atual do banner para exibição
        $currentBannerIndex = $this->bannerController->getCurrentBannerIndex(0);

        // Passar os produtos, banners e o índice atual do banner para a vista
        require_once './app/views/homepage/homepage.php';
    }
}
