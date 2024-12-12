<?php

require_once './app/models/homeModel.php';

class PromocoesController
{
    public function index(): void
    {
        // Instancia do modelo
        $homeModel = new HomeModel();

        // Obter os produtos com desconto
        $produtosComDesconto = $homeModel->getProdutosComDesconto();

        // Disponibilizar os dados para a view
        require_once './app/views/todasPromocoes.php';
    }
}
?>