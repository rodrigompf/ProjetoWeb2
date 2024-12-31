<?php

require_once './app/controllers/bannerController.php'; // Include the BannerController
require_once './app/models/homeModel.php';

class HomePageController
{
    private $bannerController;

    public function __construct()
    {
        $this->bannerController = new BannerController();
    }

    public function index(): void
    {
        $homeModel = new HomeModel();
        $produtosComDesconto = $homeModel->getProdutosComDesconto();

        // Fetch the banners using the BannerController
        $banners = $this->bannerController->getBanners();
        
        // Get the current banner index
        $currentBannerIndex = $this->bannerController->getCurrentBannerIndex(0);

        // Pass the banners and current banner index to the view
        require_once './app/views/homepage.php';
    }
}
