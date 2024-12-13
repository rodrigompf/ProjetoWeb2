<?php

require_once './app/database/connection.php';

class ProdutosModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getProdutosByCategorias(): array
{
    $query = "
        SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        JOIN categorias c ON p.categoria_id = c.id
        ORDER BY c.nome, p.nome
    ";

    $stat = $this->db->prepare($query);
    $stat->execute();

    $produtosPorCategorias = [];

    foreach ($stat->fetchAll(PDO::FETCH_ASSOC) as $produto) {
        $preco = $produto['preco'];
        $descontoPercentual = $produto['discount_price'];
        $descontoAtivo = $produto['desconto'];

        // Calculate discounted price if a discount is active
        if ($descontoAtivo == 1 && $descontoPercentual > 0) {
            $precoDescontado = $preco * (1 - ($descontoPercentual / 100));
        } else {
            $precoDescontado = $preco;
        }

        // Debug: Output precoDescontado to check if it's being set correctly
        $produto['precoDescontado'] = $precoDescontado;

        $categoriaNome = $produto['categoria_nome'];
        if (!isset($produtosPorCategorias[$categoriaNome])) {
            $produtosPorCategorias[$categoriaNome] = [];
        }
        $produtosPorCategorias[$categoriaNome][] = $produto;
    }

    return $produtosPorCategorias;
}
public function getProdutoById($product_id) {
    $query = $this->db->prepare("SELECT * FROM produtos WHERE id = :id");
    $query->execute(['id' => $product_id]);
    $produto = $query->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        // Handle discount price fallback
        if (!isset($produto['discount_price']) || $produto['discount_price'] === NULL) {
            $produto['discount_price'] = $produto['preco'];
        }

        return $produto;
    }

    return false;
}







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

    // Adicione o cálculo do precoDescontado
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

    return $produtos;
}

    public function insert(string $nome, int $categoria_id, ?string $imagem): bool
    {
        $sql = "INSERT INTO produtos (nome, categoria_id, imagem) VALUES (:nome, :categoria_id, :imagem)";
        $stat = $this->db->prepare($sql);

        return $stat->execute([
            ':nome' => $nome,
            ':categoria_id' => $categoria_id,
            ':imagem' => $imagem,
        ]);
    }

    public function getAllCategorias(): array
    {
        $query = "SELECT * FROM categorias ORDER BY nome";
        $stat = $this->db->prepare($query);
        $stat->execute();

        return $stat->fetchAll(PDO::FETCH_ASSOC);
    }

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

        return $stat->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>