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

    public function getRandomDiscountedProducts(int $limit): array
    {
        // Get the timestamp of the last refresh
        $lastRefresh = $this->getLastRefreshTime();
        $currentTime = time();

        echo "Last refresh timestamp: " . date('Y-m-d H:i:s', $lastRefresh) . "<br>";
        echo "Current time: " . date('Y-m-d H:i:s', $currentTime) . "<br>";

        // If it's been 24 hours, refresh the discounted products
        if (($currentTime - $lastRefresh) > 86400) {
            echo "Refreshing discounted products...<br>";

            // Fetch 5 random discounted products
            $query = "SELECT * FROM produtos WHERE desconto_ativo = 1 ORDER BY RAND() LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Cache the products and update the refresh timestamp
            $this->cacheDiscountedProducts($products);
            $this->updateRefreshTimestamp($currentTime);

            echo "Discounted products cached.<br>";
        } else {
            echo "Using cached discounted products.<br>";
            // If within 24 hours, fetch the cached products
            $products = $this->getCachedDiscountedProducts();
        }

        return $products;
    }


    private function getLastRefreshTime(): int
    {
        $query = "SELECT timestamp FROM refresh_log ORDER BY timestamp DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? strtotime($result['timestamp']) : 0;
    }

    private function cacheDiscountedProducts(array $products): void
    {
        $this->db->exec("DELETE FROM produtos_cache"); // Clear old cache

        $query = "
            INSERT INTO produtos_cache (id, nome, preco, preco_com_desconto, imagem, categoria_id, timestamp) 
            VALUES (:id, :nome, :preco, :preco_com_desconto, :imagem, :categoria_id, NOW())
        ";
        $stmt = $this->db->prepare($query);

        foreach ($products as $product) {
            $discountedPrice = $product['preco'] * 0.75;  // 25% discount
            $stmt->execute([
                ':id' => $product['id'],
                ':nome' => $product['nome'],
                ':preco' => $product['preco'],
                ':preco_com_desconto' => $discountedPrice,
                ':imagem' => $product['imagem'],
                ':categoria_id' => $product['categoria_id'],
            ]);
        }
    }

    private function updateRefreshTimestamp(int $timestamp): void
    {
        $query = "INSERT INTO refresh_log (timestamp) VALUES (FROM_UNIXTIME(:timestamp))";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':timestamp', $timestamp);
        $stmt->execute();
    }

    private function getCachedDiscountedProducts(): array
    {
        $query = "SELECT * FROM produtos_cache ORDER BY timestamp DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomProducts(int $limit): array
    {
        $query = "SELECT * FROM produtos ORDER BY RAND() LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
