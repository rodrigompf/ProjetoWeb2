<?php

require_once './app/database/connection.php';

class ProdutosModel
{
    private $db;

    public function __construct()
    {
        // Inicializa a conexão com a base de dados através da classe Connection
        $this->db = Connection::getInstance();
    }

    /**
     * Obtém os produtos organizados por categoria.
     */
    public function getProdutosByCategorias(): array
    {
        $query = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            ORDER BY c.nome, p.nome
        ";

        // Prepara e executa a consulta
        $stat = $this->db->prepare($query);
        $stat->execute();

        $produtosPorCategorias = [];

        // Processa os produtos para calcular o preço com o desconto e organizar por categorias
        foreach ($stat->fetchAll(PDO::FETCH_ASSOC) as $produto) {
            $preco = $produto['preco'];
            $descontoPercentual = $produto['discount_price'];
            $descontoAtivo = $produto['desconto'];

            // Calcula o preço com desconto se o desconto estiver ativo
            if ($descontoAtivo == 1 && $descontoPercentual > 0) {
                $precoDescontado = $preco * (1 - ($descontoPercentual / 100));
            } else {
                $precoDescontado = $preco;
            }

            // Atribui o preço com desconto ao produto
            $produto['precoDescontado'] = $precoDescontado;

            // Organiza os produtos por categoria
            $categoriaNome = $produto['categoria_nome'];
            if (!isset($produtosPorCategorias[$categoriaNome])) {
                $produtosPorCategorias[$categoriaNome] = [];
            }
            $produtosPorCategorias[$categoriaNome][] = $produto;
        }

        // Retorna os produtos organizados por categoria
        return $produtosPorCategorias;
    }

    /**
     * Obtém um produto específico pelo ID.
     */
    public function getProdutoById($product_id)
    {
        $query = $this->db->prepare("SELECT * FROM produtos WHERE id = :id");
        $query->execute(['id' => $product_id]);
        $produto = $query->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            // Calcula o preço com desconto se houver
            $precoOriginal = (float)$produto['preco'];
            $descontoPercent = (float)$produto['discount_price'];

            if ($descontoPercent > 0) {
                $produto['desconto'] = round($precoOriginal * (1 - ($descontoPercent / 100)), 2);
            } else {
                $produto['desconto'] = $precoOriginal;
            }

            // Debug: Registra informações para verificar se estão corretas
            error_log('Produto ID: ' . $product_id);
            error_log('Preço Original: ' . $precoOriginal);
            error_log('Desconto Percentual: ' . $descontoPercent);
            error_log('Discount Price: ' . $produto['desconto']);

            return $produto;
        }

        // Retorna falso se o produto não for encontrado
        return false;
    }

    /**
     * Obtém todos os produtos de uma categoria específica.
     */
    public function getProdutosByCategoria(string $categoria): array
    {
        $query = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE c.nome = :categoria
            ORDER BY p.nome
        ";

        $stat = $this->db->prepare($query);
        $stat->execute([':categoria' => $categoria]);

        $produtos = $stat->fetchAll(PDO::FETCH_ASSOC);

        // Adiciona o cálculo do preço com desconto para cada produto
        foreach ($produtos as &$produto) {
            $preco = $produto['preco'];
            $descontoPercentual = $produto['discount_price'];
            $descontoAtivo = $produto['desconto'];

            if ($descontoAtivo == 1 && $descontoPercentual > 0) {
                $produto['precoDescontado'] = $preco * (1 - ($descontoPercentual / 100));
            } else {
                $produto['precoDescontado'] = $preco;
            }
        }

        // Retorna os produtos com o cálculo do preço com desconto
        return $produtos;
    }

    /**
     * Insere um novo produto na base de dados.
     */
    public function insert(string $nome, int $categoria_id, ?string $imagem): bool
    {
        $sql = "INSERT INTO produtos (nome, categoria_id, imagem) VALUES (:nome, :categoria_id, :imagem)";
        $stat = $this->db->prepare($sql);

        // Executa a inserção do produto na base de dados
        return $stat->execute([
            ':nome' => $nome,
            ':categoria_id' => $categoria_id,
            ':imagem' => $imagem,
        ]);
    }

    /**
     * Obtém todas as categorias de produtos.
     */
    public function getAllCategorias(): array
    {
        $query = "SELECT * FROM categorias ORDER BY nome";
        $stat = $this->db->prepare($query);
        $stat->execute();

        // Retorna todas as categorias ordenadas por nome
        return $stat->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Pesquisa produtos por nome dentro de uma categoria.
     */
    public function searchProdutosByCategoria(string $categoria, string $query): array
    {
        $query = "%$query%";
        $sql = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE c.nome = :categoria AND p.nome LIKE :query
        ";

        $stat = $this->db->prepare($sql);
        $stat->execute([
            ':categoria' => $categoria,
            ':query' => $query
        ]);

        // Retorna os produtos que correspondem à pesquisa
        return $stat->fetchAll(PDO::FETCH_ASSOC);
    }
}
