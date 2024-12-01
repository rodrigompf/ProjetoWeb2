<?php

require_once '../app/models/produtosModel.php';

class produtosController
{

    public function index(): void
    {
        // Check if a category is passed in the URL
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        $produtosModel = new ProdutosModel();
        $produtosView = $produtosModel->getAllProdutos($categoria); // Pass category to the model

        require_once '../app/views/produtosView.php';
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
}
