<?php

require_once './app/database/connection.php';

class HomeModel
{
    private $db;

    /**
     * Construtor que inicializa a conexão com a base de dados
     */
    public function __construct()
    {
        // Supõe-se que Connection::getInstance() retorna uma instância de PDO
        $this->db = Connection::getInstance();
    }

    /**
     * Obtém os produtos com desconto e calcula o preço com o desconto aplicado.
     */
    public function getProdutosComDesconto($limite = 0): array
    {
        // Se não for passado um limite, retornamos todos os produtos
        $limiteQuery = ($limite === 0) ? "" : "LIMIT " . (int)$limite;

        $query = "
            SELECT p.*, c.nome AS categoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.desconto = 1 AND p.discount_price > 0
            ORDER BY RAND() " . $limiteQuery;  // Garante que os produtos sejam aleatórios

        // Prepara e executa a consulta
        $stat = $this->db->prepare($query);
        $stat->execute();

        // Obtém todos os resultados
        $produtos = $stat->fetchAll(PDO::FETCH_ASSOC);

        // Processa cada produto para calcular o preço com o desconto
        foreach ($produtos as &$produto) {
            $preco = $produto['preco'];
            $descontoPercentual = $produto['discount_price'];

            // Verifica se há desconto e aplica-o
            if ($produto['desconto'] == 1 && $descontoPercentual > 0) {
                // Calcula o preço após o desconto
                $produto['preco_com_desconto'] = $preco * (1 - ($descontoPercentual / 100));
            } else {
                // Se não houver desconto, o preço com desconto é o mesmo que o preço original
                $produto['preco_com_desconto'] = $preco;
            }
        }

        // Retorna a lista de produtos com o preço com desconto calculado
        return $produtos;
    }

    /**
     * Obtém todos os banners da base de dados.
     */
    public function getBanners(): array
    {
        $query = "SELECT image_url FROM banners";
        $stat = $this->db->prepare($query);
        $stat->execute();
        // Retorna apenas os URLs das imagens
        return $stat->fetchAll(PDO::FETCH_COLUMN);
    }
}
