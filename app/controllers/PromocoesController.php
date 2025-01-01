<?php

require_once './app/models/homeModel.php';

class PromocoesController
{
    /**
     * Exibe todas as promoções (produtos com desconto).
     */
    public function index(): void
    {
        // Cria uma instância do modelo para acessar os dados da aplicação
        $homeModel = new HomeModel();

        // Obtém os produtos que estão com desconto
        $produtosComDesconto = $homeModel->getProdutosComDesconto();

        // Disponibiliza os dados (produtos com desconto) para a view
        require_once './app/views/todasPromocoes.php';
    }
}
?>
