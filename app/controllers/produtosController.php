<?php

require_once './app/models/produtosModel.php';

class ProdutosController
{
    /**
     * Exibe todos os produtos organizados por categorias.
     */
    public function index(): void
    {
        $produtosModel = new ProdutosModel();

        // Obter os produtos organizados por categorias
        $produtosPorCategorias = $produtosModel->getProdutosByCategorias();
        
        // Obter todas as categorias para exibir no menu de categorias
        $categorias = $produtosModel->getAllCategorias();

        // Incluir a vista para exibir os produtos
        require_once './app/views/produtosView.php';
    }

    /**
     * Exibe o formulário para criar um novo produto.
     */
    public function create(): void
    {
        // Incluir a vista de criação de produto
        require_once '../app/views/createProdutosView.php';
    }

    /**
     * Armazena um novo produto no banco de dados.
     */
    public function store(): void
    {
        // Obtém os dados do formulário
        $nome = $_POST['nome'];
        $categoria_id = $_POST['categoria_id'];
        $imagem = $_FILES['imagem']['name'];
        $imagemPath = null;

        // Faz o upload da imagem, se foi fornecida
        if (!empty($imagem)) {
            $imagemPath = 'uploads/' . basename($imagem);
            // Move o arquivo da imagem para o diretório de uploads
            move_uploaded_file($_FILES['imagem']['tmp_name'], $imagemPath);
        }

        // Cria uma instância do modelo de produtos
        $produtosModel = new ProdutosModel();
        // Insere o novo produto no banco de dados
        $result = $produtosModel->insert($nome, $categoria_id, $imagemPath);

        // Se a inserção for bem-sucedida, redireciona para a lista de produtos
        if ($result) {
            $this->index(); // Redireciona para a lista de produtos
        } else {
            // Se ocorrer um erro, exibe uma mensagem de erro
            echo "Erro ao criar produto.";
        }
    }
    
    /**
     * Exibe os produtos de uma categoria específica.
     */
    public function showCategoria(string $categoria): void
    {
        $produtosModel = new ProdutosModel();
        // Obtém os produtos filtrados pela categoria
        $produtosView = $produtosModel->getProdutosByCategoria($categoria);

        // Exibe a vista de produtos filtrados pela categoria
        require_once './app/views/categoriaView.php';
    }

    /**
     * Realiza uma pesquisa de produtos, com filtro por categoria e/ou nome.
     */
    public function search(): void
    {
        // Obtém os parâmetros de categoria e consulta da URL
        $categoria = $_GET['categoria'] ?? '';
        $query = $_GET['query'] ?? '';

        // Cria uma instância do modelo de produtos
        $produtosModel = new ProdutosModel();
        // Realiza a pesquisa de produtos com base na categoria e na consulta
        $produtos = $produtosModel->searchProdutosByCategoria($categoria, $query);

        // Retorna os produtos encontrados em formato JSON
        header('Content-Type: application/json');
        echo json_encode($produtos);
    }
}
