<?php

require_once './app/database/Connection.php';

class StockManagementController
{
    public function index()
    {
        $error = null;
        $categorias = [];
        $produtos = [];

        try {
            $conn = Connection::getInstance();


            $sql = "SELECT id, nome FROM categorias";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $categoria_id = $_GET['categoria_id'] ?? null;

            if ($categoria_id) {

                $sql = "SELECT id, nome, preco, stock FROM produtos WHERE categoria_id = :categoria_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':categoria_id', $categoria_id);
                $stmt->execute();
                $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $error = "Erro ao carregar dados: " . $e->getMessage();
        }

        require_once './app/views/stockManagementView.php';
    }
    public function addStock()
{
    $error = null;
    $success = null;

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = Connection::getInstance();

            $productId = $_POST['product_id'] ?? null;
            $newStock = (int) ($_POST['new_stock'] ?? 0);

            if ($productId && $newStock > 0) {
                $sql = "UPDATE produtos SET stock = stock + :new_stock WHERE id = :product_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':new_stock', $newStock);
                $stmt->bindParam(':product_id', $productId);

                if ($stmt->execute()) {
                    $success = "Stock atualizado com sucesso!";
                } else {
                    $error = "Falha ao atualizar o stock.";
                }
            } else {
                $error = "Número inválido.";
            }
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }

    header("Location: /stock-management?success=" . urlencode($success) . "&error=" . urlencode($error));
    exit;
}

}
