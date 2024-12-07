<?php

require_once '../app/models/produtosModel.php';

class ProdutosController
{
    public function home(): void
    {
        $produtosModel = new ProdutosModel();

        // Fetch discounted products for the banner
        $produtosComDesconto = $produtosModel->getRandomDiscountedProducts(5);

        echo "<pre>";
        var_dump($produtosComDesconto); // Check what data is being returned
        echo "</pre>";

        // If no discounted products exist, fetch any 5 random products
        if (empty($produtosComDesconto)) {
            $produtosComDesconto = $produtosModel->getRandomProducts(5);
        }

        // Pass products to the view
        require_once '../app/views/homepage.php';
    }


    public function index(): void
    {
        $produtosModel = new ProdutosModel();

        // Fetch products grouped by categories
        $produtosPorCategorias = $produtosModel->getProdutosByCategorias();

        // Fetch all categories for display
        $categorias = $produtosModel->getAllCategorias();

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

        // Upload image if provided
        if (!empty($imagem)) {
            $imagemPath = 'uploads/' . basename($imagem);
            move_uploaded_file($_FILES['imagem']['tmp_name'], $imagemPath);
        }

        $produtosModel = new ProdutosModel();
        $result = $produtosModel->insert($nome, $categoria_id, $imagemPath);

        if ($result) {
            $this->index(); // Redirect to the products list
        } else {
            echo "Erro ao criar produto.";
        }
    }

    public function showCategoria(string $categoria): void
    {
        $produtosModel = new ProdutosModel();

        // Fetch products for a specific category
        $produtosView = $produtosModel->getProdutosByCategoria($categoria);

        require_once '../app/views/categoriaView.php';
    }

    public function search(): void
    {
        $categoria = $_GET['categoria'] ?? '';
        $query = $_GET['query'] ?? '';

        $produtosModel = new ProdutosModel();

        // Fetch products based on search query and category
        $produtos = $produtosModel->searchProdutosByCategoria($categoria, $query);

        // Return products in JSON format
        header('Content-Type: application/json');
        echo json_encode($produtos);
    }
}
