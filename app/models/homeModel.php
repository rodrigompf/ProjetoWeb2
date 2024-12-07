<?php

require_once '../app/database/connection.php';

class HomeModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getProdutosComDesconto(): array
    {
        $query = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.desconto = 1 AND p.discount_price > 0
            ORDER BY p.preco DESC
        ";

        $stat = $this->db->prepare($query);
        $stat->execute();

        $produtos = $stat->fetchAll(PDO::FETCH_ASSOC);

        foreach ($produtos as &$produto) {
            $preco = $produto['preco'];
            $descontoPercentual = $produto['discount_price'];

            if ($produto['desconto'] == 1 && $descontoPercentual > 0) {
                $produto['preco_com_desconto'] = $preco * (1 - ($descontoPercentual / 100));
            } else {
                $produto['preco_com_desconto'] = $preco;
            }
        }

        return $produtos;
    }
}
