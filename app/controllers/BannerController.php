<?php

require_once './app/models/homeModel.php';

class BannerController
{
    private $homeModel;

    // Construtor para inicializar o modelo da página inicial
    public function __construct()
    {
        $this->homeModel = new HomeModel();
    }

    // Obtém todos os banners do modelo
    public function getBanners(): array
    {
        // Recupera os banners a partir do modelo
        return $this->homeModel->getBanners();
    }

    // Obtém o índice atual do banner, com validação
    public function getCurrentBannerIndex($currentIndex): int
    {
        // Obtemos a lista de banners
        $banners = $this->getBanners();

        // Verifica se existe um índice de banner passado via query string; caso contrário, usa o índice atual
        $currentIndex = isset($_GET['banner']) ? (int)$_GET['banner'] : $currentIndex;

        // Garante que o índice atual é válido (dentro dos limites do array de banners)
        return max(0, min($currentIndex, count($banners) - 1));
    }

    // Renderiza os banners e passa os dados para a vista
    public function renderBanners()
    {
        // Obtém a lista de banners
        $banners = $this->getBanners();

        // Inclui a vista correspondente para exibir os banners
        include './app/views/bannerView.php';
    }
}
