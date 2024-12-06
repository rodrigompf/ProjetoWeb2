<?php

require_once '../app/database/connection.php';

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
            $categoriaNome = $produto['categoria_nome'];
            if (!isset($produtosPorCategorias[$categoriaNome])) {
                $produtosPorCategorias[$categoriaNome] = [];
            }
            $produtosPorCategorias[$categoriaNome][] = $produto;
        }

        return $produtosPorCategorias;
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

        return $stat->fetchAll(PDO::FETCH_ASSOC);
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
        $stat->bindParam(':categoria', $categoria);
        $stat->bindParam(':query', $query);
        $stat->execute();

        return $stat->fetchAll(PDO::FETCH_ASSOC);
    }
}
