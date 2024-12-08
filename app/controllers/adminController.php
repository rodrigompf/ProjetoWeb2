<?php

require_once '../app/database/Connection.php';

class AdminController {

    public function index() {
        require_once '../app/views/adminView.php';
    }

    public function create() {
        $error = null;
        $success = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conn = Connection::getInstance();

                // Capture form inputs
                $nome = $_POST['nome'] ?? null;
                $descricao = $_POST['descricao'] ?? null;
                $preco = $_POST['preco'] ?? null;
                $discount_price = $_POST['discount_price'] ?? null;
                $categoria_id = $_POST['categoria_id'] ?? null;
                $desconto = $_POST['desconto'] ?? null;

                // Map categoria_id to asset folders
                $uploadDirs = [
                    1 => 'peixes/',   // Category ID 1 corresponds to the Peixes folder
                    2 => 'carnes/',   // Category ID 2 corresponds to the Carnes folder
                    3 => 'frutas/',   // Category ID 3 corresponds to the Frutas folder
                ];

                // Validate required fields
                if (!$nome || !$preco || !$categoria_id) {
                    $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
                } else {
                    $targetFile = null;

                    // Handle file upload if provided
                    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                        if (isset($uploadDirs[$categoria_id])) {
                            $uploadDir = $uploadDirs[$categoria_id];

                            // Ensure upload directory exists
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }

                            $targetFile = $uploadDir . basename($_FILES['imagem']['name']);

                            // Move the uploaded image to the target folder
                            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                                $error = "Erro ao fazer upload da imagem.";
                            }
                        } else {
                            $error = "Categoria inválida.";
                        }
                    }

                    // If no errors, insert product data into the database
                    if (!$error) {
                        $sql = "INSERT INTO produtos (nome, descricao, preco, discount_price, categoria_id, imagem, desconto) 
                                VALUES (:nome, :descricao, :preco, :discount_price, :categoria_id, :imagem, :desconto)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':nome', $nome);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':preco', $preco);
                        $stmt->bindParam(':discount_price', $discount_price);
                        $stmt->bindParam(':categoria_id', $categoria_id);
                        $stmt->bindParam(':imagem', $targetFile);
                        $stmt->bindParam(':desconto', $desconto);

                        if ($stmt->execute()) {
                            $success = "Produto adicionado com sucesso!";
                        } else {
                            $error = "Erro ao adicionar produto.";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Include the view with feedback
        require_once '../app/views/createProdutosView.php';
    }
}
?>
