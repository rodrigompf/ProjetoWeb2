<?php

require_once '../app/database/connection.php';

class ProdutosModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAllProdutos($categoria = null): array
    {
        // Iniciar a query
        $query = "
        SELECT p.*, c.nome AS categoria_nome
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
    ";

        // Adicionar filtro de categoria, se especificado
        if ($categoria) {
            $query .= " WHERE c.nome = :categoria";
        }

        $stat = $this->db->prepare($query);

        // Passar o parâmetro de categoria, se necessário
        if ($categoria) {
            $stat->bindParam(':categoria', $categoria);
        }

        $stat->execute();

        return $stat->fetchAll();
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

    public function getProdutosByCategoria(string $categoria): array
    {
        $query = "
        SELECT p.*, c.nome AS categoria
        FROM produtos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        WHERE c.nome = :categoria
        ORDER BY p.nome
    ";
        $stat = $this->db->prepare($query);
        $stat->execute([':categoria' => $categoria]);
        return $stat->fetchAll();
    }
}
