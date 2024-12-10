<?php

require_once '../app/database/connection.php';

class HomeModel
{
    private $db;

    public function __construct()
    {
        // Assuming Connection::getInstance() returns a PDO instance
        $this->db = Connection::getInstance();
    }

    public function getProdutosComDesconto(): array
    {
        // SQL query to select products with discounts
        $query = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.desconto = 1 AND p.discount_price > 0
            ORDER BY p.preco DESC
        ";

        // Prepare and execute the query
        $stat = $this->db->prepare($query);
        $stat->execute();

        // Fetch all the results
        $produtos = $stat->fetchAll(PDO::FETCH_ASSOC);

        // Process each product to calculate the discounted price
        foreach ($produtos as &$produto) {
            $preco = $produto['preco'];
            $descontoPercentual = $produto['discount_price'];

            // Check if there's a discount and apply it
            if ($produto['desconto'] == 1 && $descontoPercentual > 0) {
                // Calculate price after discount
                $produto['preco_com_desconto'] = $preco * (1 - ($descontoPercentual / 100));
            } else {
                // If no discount, price with discount is the same as the original
                $produto['preco_com_desconto'] = $preco;
            }
        }

        return $produtos;
    }
}
?>
