<?php

require_once './app/database/Connection.php';

class AdminController
{

    public function index()
    {
        require_once './app/views/adminView.php';
    }

    public function create()
    {
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
                $imagem_url = $_POST['imagem_url'] ?? null;  // Agora estamos capturando a URL da imagem

                // Validate required fields
                if (!$nome || !$preco || !$categoria_id) {
                    $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
                } else {
                    // Validar a URL da imagem
                    if (!filter_var($imagem_url, FILTER_VALIDATE_URL)) {
                        $error = "A URL da imagem fornecida não é válida.";
                    }

                    // If no errors, find the next available ID
                    if (!$error) {
                        // Query to find the next available ID
                        $sql = "SELECT MIN(t1.id + 1) AS next_id 
                            FROM produtos t1 
                            WHERE NOT EXISTS (SELECT t2.id FROM produtos t2 WHERE t2.id = t1.id + 1)";
                        $stmt = $conn->query($sql);
                        $nextId = $stmt->fetchColumn();

                        // Fallback: if the table is empty, use 1
                        $nextId = $nextId ?: 1;

                        // Insert product data into the database, including the stock value set to 0
                        $sql = "INSERT INTO produtos (id, nome, descricao, preco, discount_price, categoria_id, imagem, desconto, stock) 
                            VALUES (:id, :nome, :descricao, :preco, :discount_price, :categoria_id, :imagem, :desconto, :stock)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id', $nextId);
                        $stmt->bindParam(':nome', $nome);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':preco', $preco);
                        $stmt->bindParam(':discount_price', $discount_price);
                        $stmt->bindParam(':categoria_id', $categoria_id);
                        $stmt->bindParam(':imagem', $imagem_url);  // Salvando a URL da imagem no banco de dados
                        $stmt->bindParam(':desconto', $desconto);

                        // Set stock to 0
                        $stock = 0;
                        $stmt->bindParam(':stock', $stock);

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
        require_once './app/views/createProdutosView.php';
    }



    public function editList()
    {
        try {
            $conn = Connection::getInstance();
            $search = $_GET['search'] ?? '';

            $sql = "SELECT id, nome, preco, stock FROM produtos WHERE nome LIKE :search";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':search', "%$search%");
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $produtos = [];
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        require_once './app/views/editListView.php';
    }


    public function editForm($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Captura dos dados do formulário
                $nome = $_POST['nome'] ?? null;
                $descricao = $_POST['descricao'] ?? null;
                $preco = $_POST['preco'] ?? null;
                $discount_price = $_POST['discount_price'] ?? null;
                $categoria_id = $_POST['categoria_id'] ?? null;
                $desconto = $_POST['desconto'] ?? null;
                $imagem_url = $_POST['imagem_url'] ?? null;  // URL da imagem se fornecida
                $imagem_nova = $_FILES['imagem'] ?? null;    // Caso o usuário envie um arquivo

                // Validar campos obrigatórios
                if (!$nome || !$preco || !$categoria_id) {
                    $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
                } else {
                    // Recupera o produto original
                    $sql = "SELECT imagem FROM produtos WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    $imagem_atual = $produto['imagem'];

                    // Verificar se imagem foi enviada via URL ou upload
                    if ($imagem_nova && $imagem_nova['error'] === UPLOAD_ERR_OK) {
                        // Se o usuário enviou uma nova imagem, devemos fazer upload
                        $uploadDirs = [
                            1 => 'peixes/',      // Categoria 1 -> Peixes
                            2 => 'carnes/',      // Categoria 2 -> Carnes
                            3 => 'frutas/',      // Categoria 3 -> Frutas
                            4 => 'sumos/',       // Categoria 4 -> Sumos
                            5 => 'eletrodomesticos/', // Categoria 5 -> Eletrodomésticos
                            6 => 'brinquedos/',  // Categoria 6 -> Brinquedos
                            7 => 'doces/',       // Categoria 7 -> Doces
                            8 => 'higiene/',     // Categoria 8 -> Higiene
                            9 => 'congelados/',  // Categoria 9 -> Congelados
                            10 => 'pastelaria/', // Categoria 10 -> Pastelaria
                        ];

                        // Verificar se a categoria existe no array de diretórios
                        if (isset($uploadDirs[$categoria_id])) {
                            $uploadDir = $uploadDirs[$categoria_id];
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);  // Cria o diretório caso não exista
                            }

                            $targetFile = $uploadDir . basename($imagem_nova['name']);

                            // Apagar a imagem antiga se necessário
                            if (!empty($imagem_atual) && file_exists($imagem_atual)) {
                                unlink($imagem_atual);  // Remove a imagem antiga
                            }

                            // Mover o arquivo carregado para o diretório
                            if (move_uploaded_file($imagem_nova['tmp_name'], $targetFile)) {
                                $imagem_atual = $targetFile;  // Atualiza o caminho da imagem
                            } else {
                                $error = "Erro ao fazer upload da nova imagem.";
                            }
                        } else {
                            $error = "Categoria inválida.";
                        }
                    } elseif ($imagem_url && filter_var($imagem_url, FILTER_VALIDATE_URL)) {
                        // Se a URL da imagem foi fornecida e é válida
                        $imagem_atual = $imagem_url;
                    } else {
                        // Se não for nem URL nem arquivo, manter a imagem antiga
                        $imagem_atual = $imagem_atual;
                    }

                    // Atualizar os dados do produto
                    if (!$error) {
                        $sql = "UPDATE produtos 
                            SET nome = :nome, descricao = :descricao, preco = :preco, 
                                discount_price = :discount_price, categoria_id = :categoria_id, 
                                desconto = :desconto, imagem = :imagem 
                            WHERE id = :id";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':nome', $nome);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':preco', $preco);
                        $stmt->bindParam(':discount_price', $discount_price);
                        $stmt->bindParam(':categoria_id', $categoria_id);
                        $stmt->bindParam(':desconto', $desconto);
                        $stmt->bindParam(':imagem', $imagem_atual);
                        $stmt->bindParam(':id', $id);

                        if ($stmt->execute()) {
                            $success = "Produto atualizado com sucesso!";
                        } else {
                            $error = "Erro ao atualizar o produto.";
                        }
                    }
                }
            }

            // Obter os detalhes do produto para preencher o formulário
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            // Obter as categorias dinâmicas da base de dados
            $sqlCategorias = "SELECT id, nome FROM categorias ORDER BY nome";
            $stmtCategorias = $conn->query($sqlCategorias);
            $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        require_once './app/views/editFormView.php';
    }


    public function delete($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            // Fetch the product to delete its image if needed
            $sql = "SELECT imagem FROM produtos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produto) {
                // Delete the product's image file
                if (!empty($produto['imagem']) && file_exists($produto['imagem'])) {
                    unlink($produto['imagem']);
                }

                // Delete the product from the database
                $sql = "DELETE FROM produtos WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    $success = "Produto eliminado com sucesso!";
                } else {
                    $error = "Erro ao eliminar o produto.";
                }
            } else {
                $error = "Produto não encontrado.";
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Redirect back to the product list with feedback
        header("Location: /produtos/edit?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }
    
}
