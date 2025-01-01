<?php

require_once './app/database/Connection.php';

class StockManagementController
{
    /**
     * Exibe a lista de categorias e produtos com o stock atual.
     */
    public function index()
    {
        $error = null;
        $categorias = [];
        $produtos = [];

        try {
            // Obtém a instância da conexão com a base de dados
            $conn = Connection::getInstance();

            // Consulta as categorias existentes na base de dados
            $sql = "SELECT id, nome FROM categorias";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            // Armazena as categorias obtidas na variável $categorias
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtém o ID da categoria se estiver presente na URL
            $categoria_id = $_GET['categoria_id'] ?? null;

            // Se a categoria estiver definida, consulta os produtos dessa categoria
            if ($categoria_id) {

                // Consulta os produtos da categoria selecionada
                $sql = "SELECT id, nome, preco, stock FROM produtos WHERE categoria_id = :categoria_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':categoria_id', $categoria_id);
                $stmt->execute();
                // Armazena os produtos obtidos na variável $produtos
                $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            // Em caso de erro na consulta, armazena a mensagem de erro
            $error = "Erro ao carregar dados: " . $e->getMessage();
        }

        // Carrega a view para exibir a gestão de stock
        require_once './app/views/stockManagementView.php';
    }

    /**
     * Atualiza a quantidade de stock de um produto específico.
     */
    public function addStock()
    {
        $error = null;
        $success = null;

        try {
            // Verifica se a requisição é do tipo POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conn = Connection::getInstance();

                // Obtém o ID do produto e a nova quantidade de stock
                $productId = $_POST['product_id'] ?? null;
                $newStock = (int) ($_POST['new_stock'] ?? 0);

                // Verifica se os dados são válidos
                if ($productId && $newStock > 0) {
                    // Atualiza o stock do produto na base de dados
                    $sql = "UPDATE produtos SET stock = stock + :new_stock WHERE id = :product_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':new_stock', $newStock);
                    $stmt->bindParam(':product_id', $productId);

                    // Se a execução for bem-sucedida, define a mensagem de sucesso
                    if ($stmt->execute()) {
                        $success = "Stock atualizado com sucesso!";
                    } else {
                        // Se ocorrer um erro ao atualizar o stock, define a mensagem de erro
                        $error = "Falha ao atualizar o stock.";
                    }
                } else {
                    // Se os dados forem inválidos, define uma mensagem de erro
                    $error = "Número inválido.";
                }
            }
        } catch (PDOException $e) {
            // Em caso de erro na consulta, armazena a mensagem de erro
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Redireciona o utilizador de volta à página de gestão de stock com mensagens de erro ou sucesso
        header("Location: /stock-management?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }
}
