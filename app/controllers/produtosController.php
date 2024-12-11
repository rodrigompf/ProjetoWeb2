<?php

require_once './app/models/produtosModel.php';

class ProdutosController
{
    public function index(): void
    {
        $produtosModel = new ProdutosModel();

        // Obtenha os produtos organizados por categorias
        $produtosPorCategorias = $produtosModel->getProdutosByCategorias();
        
        // Obtenha todas as categorias para exibir no menu
        $categorias = $produtosModel->getAllCategorias();

        require_once './app/views/produtosView.php';
    }

    public function create(): void
    {
        require_once '../app/views/createProdutosView.php';
    }

    public function store(): void
    {
        $nome = $_POST['nome'];
        $categoria_id = $_POST['categoria_id'];
        $imagem = $_FILES['imagem']['name'];
        $imagemPath = null;

        // Faz o upload da imagem, se houver
        if (!empty($imagem)) {
            $imagemPath = 'uploads/' . basename($imagem);
            move_uploaded_file($_FILES['imagem']['tmp_name'], $imagemPath);
        }

        $produtosModel = new ProdutosModel();
        $result = $produtosModel->insert($nome, $categoria_id, $imagemPath);

        if ($result) {
            $this->index(); // Redireciona para a lista de produtos
        } else {
            echo "Erro ao criar produto.";
        }
    }
    

    public function showCategoria(string $categoria): void
    {
        $produtosModel = new ProdutosModel();
        $produtosView = $produtosModel->getProdutosByCategoria($categoria);

        require_once './app/views/categoriaView.php';
    }

    public function search(): void
    {
        $categoria = $_GET['categoria'] ?? '';
        $query = $_GET['query'] ?? '';

        $produtosModel = new ProdutosModel();
        $produtos = $produtosModel->searchProdutosByCategoria($categoria, $query);

        // Retorne os produtos em formato JSON
        header('Content-Type: application/json');
        echo json_encode($produtos);
    }
}