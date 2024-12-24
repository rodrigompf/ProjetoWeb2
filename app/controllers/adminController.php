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

            // Validate required fields
            if (!$nome || !$preco || !$categoria_id) {
                $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
            } else {
                $targetFile = null;

                // Handle file upload if provided
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    // Recuperar as categorias do banco de dados
                    $sql = "SELECT id, nome FROM categorias";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Procurar a categoria no banco de dados pelo ID
                    $categoriaNome = null;
                    foreach ($categorias as $categoria) {
                        if ($categoria['id'] == $categoria_id) {
                            $categoriaNome = $categoria['nome'];
                            break;
                        }
                    }

                    if ($categoriaNome) {
                        // Define o diretório de upload com base no nome da categoria
                        $uploadDir = strtolower($categoriaNome) . '/';

                        // Verifica se o diretório de upload existe, se não, cria
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        // Caminho para o arquivo de imagem
                        $targetFile = $uploadDir . basename($_FILES['imagem']['name']);

                        // Move o arquivo de imagem para o diretório de destino
                        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                            $error = "Erro ao fazer upload da imagem.";
                        }
                    } else {
                        $error = "Categoria inválida.";
                    }
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
                    $stmt->bindParam(':imagem', $targetFile);
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

                $nome = $_POST['nome'] ?? null;
                $descricao = $_POST['descricao'] ?? null;
                $preco = $_POST['preco'] ?? null;
                $discount_price = $_POST['discount_price'] ?? null;
                $categoria_id = $_POST['categoria_id'] ?? null;
                $desconto = $_POST['desconto'] ?? null;

                $sql = "SELECT imagem FROM produtos WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                $targetFile = $produto['imagem'];

                // imagem
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    if (!empty($produto['imagem']) && file_exists($produto['imagem'])) {
                        unlink($produto['imagem']);
                    }

                    // Categorias
                    $uploadDirs = [
                        1 => 'peixes/',
                        2 => 'carnes/',
                        3 => 'frutas/',
                    ];

                    if (isset($uploadDirs[$categoria_id])) {
                        $uploadDir = $uploadDirs[$categoria_id];
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $targetFile = $uploadDir . basename($_FILES['imagem']['name']);

                        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $targetFile)) {
                            $error = "Erro ao fazer upload da nova imagem.";
                        }
                    } else {
                        $error = "Categoria inválida.";
                    }
                }

                // Dar Update no Produto
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
                    $stmt->bindParam(':imagem', $targetFile);
                    $stmt->bindParam(':id', $id);

                    if ($stmt->execute()) {
                        $success = "Produto atualizado com sucesso!";
                    } else {
                        $error = "Erro ao atualizar o produto.";
                    }
                }
            }

            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
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
