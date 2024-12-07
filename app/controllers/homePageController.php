<?php

require_once '../app/models/homeModel.php';

class HomePageController
{
    public function index(): void
    {
        $homeModel = new HomeModel();

        $produtosComDesconto = $homeModel->getProdutosComDesconto();

        require_once '../app/views/homepage.php';
    }
}
?>
