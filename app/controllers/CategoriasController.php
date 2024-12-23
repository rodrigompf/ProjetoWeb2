<?php

require_once './app/database/Connection.php';

class CategoriasController {

    // Display all categories
    public function index() {
        try {
            $conn = Connection::getInstance();

            // Fetch all categories
            $sql = "SELECT * FROM categorias ORDER BY nome ASC";
            $stmt = $conn->query($sql);
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $categorias = [];
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        require_once './app/views/categoriasView.php';
    }

    // Add a new category
    public function create() {
        $error = null;
        $success = null;
    
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = $_POST['nome'] ?? null;
    
                if (!$nome) {
                    $error = "O campo Nome é obrigatório.";
                } else {
                    $conn = Connection::getInstance();
                    $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':nome', $nome);
    
                    if ($stmt->execute()) {
                        // Redirect to the categorias view on success
                        header("Location: /categorias");
                        exit;
                    } else {
                        $error = "Erro ao adicionar categoria.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }
    
        require_once './app/views/addcategoriasView.php';
    }
    

    // Delete a category
    public function delete($id) {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            $sql = "DELETE FROM categorias WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $success = "Categoria eliminada com sucesso!";
            } else {
                $error = "Erro ao eliminar categoria.";
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        header("Location: /categorias?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }
}
