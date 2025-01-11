<?php

require_once './app/database/Connection.php';

class AdminController
{
    // Método para exibir a página inicial de administração
    public function index()
    {
        require_once './app/views/homepage/adminView.php';
    }

    // Método para criar um novo produto
    public function create()
    {
        $error = null;
        $success = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conn = Connection::getInstance();

                // Captura dos dados enviados no formulário
                $nome = $_POST['nome'] ?? null;
                $descricao = $_POST['descricao'] ?? null;
                $preco = $_POST['preco'] ?? null;
                $discount_price = $_POST['discount_price'] ?? null;
                $categoria_id = $_POST['categoria_id'] ?? null;
                $desconto = $_POST['desconto'] ?? null;

                // Validação dos campos obrigatórios
                if (!$nome || !$preco || !$categoria_id) {
                    $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
                } elseif (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    // Validação e upload da imagem
                    $uploadDir = './assets/produtos/';
                    $imageTmpName = $_FILES['imagem']['tmp_name'];
                    $originalExtension = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                    $imageName = $nome . '.' . $originalExtension; // Usa o nome do produto
                    $imagePath = $uploadDir . $imageName;

                    if (!move_uploaded_file($imageTmpName, $imagePath)) {
                        $error = "Erro ao fazer upload da imagem.";
                    }
                } else {
                    $error = "Por favor, envie uma imagem válida.";
                }

                // Inserção no banco de dados se não houver erros
                if (!$error) {
                    $sql = "SELECT MIN(t1.id + 1) AS next_id 
                        FROM produtos t1 
                        WHERE NOT EXISTS (SELECT t2.id FROM produtos t2 WHERE t2.id = t1.id + 1)";
                    $stmt = $conn->query($sql);
                    $nextId = $stmt->fetchColumn();
                    $nextId = $nextId ?: 1;

                    $sql = "INSERT INTO produtos (id, nome, descricao, preco, discount_price, categoria_id, imagem, desconto, stock) 
                        VALUES (:id, :nome, :descricao, :preco, :discount_price, :categoria_id, :imagem, :desconto, :stock)";
                    $stmt = $conn->prepare($sql);

                    $stmt->bindParam(':id', $nextId);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':descricao', $descricao);
                    $stmt->bindParam(':preco', $preco);
                    $stmt->bindParam(':discount_price', $discount_price);
                    $stmt->bindParam(':categoria_id', $categoria_id);
                    $stmt->bindParam(':imagem', $imageName); // Salva o nome do arquivo no banco
                    $stmt->bindParam(':desconto', $desconto);

                    $stock = 0;
                    $stmt->bindParam(':stock', $stock);

                    if ($stmt->execute()) {
                        $success = "Produto adicionado com sucesso!";
                    } else {
                        $error = "Erro ao adicionar produto.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Incluir a view
        require_once './app/views/produtos/createProdutosView.php';
    }


    // Método para listar os produtos editáveis
    public function editList()
    {
        try {
            $conn = Connection::getInstance();
            $search = $_GET['search'] ?? ''; // Parâmetro de pesquisa

            $sql = "SELECT id, nome, preco, stock FROM produtos WHERE nome LIKE :search";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':search', "%$search%");
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $produtos = [];
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Incluir a vista para listar os produtos
        require_once './app/views/produtos/editListView.php';
    }

    // Método para exibir o formulário de edição de um produto
    public function editForm($id)
    {
        $error = null; // Mensagem de erro
        $success = null; // Mensagem de sucesso

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
                $imagem_url = $_POST['imagem_url'] ?? null; // URL da imagem
                $imagem_nova = $_FILES['imagem'] ?? null;  // Imagem enviada pelo utilizador

                // Validar campos obrigatórios
                if (!$nome || !$preco || !$categoria_id) {
                    $error = "Os campos Nome, Preço e Categoria são obrigatórios.";
                } else {
                    // Obter os detalhes do produto original
                    $sql = "SELECT imagem FROM produtos WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    $imagem_atual = $produto['imagem'];

                    // Processar upload ou URL da imagem
                    if ($imagem_nova && $imagem_nova['error'] === UPLOAD_ERR_OK) {
                        $uploadDirs = [
                            1 => 'peixes/',
                            2 => 'carnes/',
                            3 => 'frutas/',
                            4 => 'sumos/',
                            5 => 'eletrodomesticos/',
                            6 => 'brinquedos/',
                            7 => 'doces/',
                            8 => 'higiene/',
                            9 => 'congelados/',
                            10 => 'pastelaria/',
                        ];

                        if (isset($uploadDirs[$categoria_id])) {
                            $uploadDir = $uploadDirs[$categoria_id];
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }

                            $targetFile = $uploadDir . basename($imagem_nova['name']);

                            if (!empty($imagem_atual) && file_exists($imagem_atual)) {
                                unlink($imagem_atual); // Remove a imagem antiga
                            }

                            if (move_uploaded_file($imagem_nova['tmp_name'], $targetFile)) {
                                $imagem_atual = $targetFile;
                            } else {
                                $error = "Erro ao fazer upload da nova imagem.";
                            }
                        } else {
                            $error = "Categoria inválida.";
                        }
                    } elseif ($imagem_url && filter_var($imagem_url, FILTER_VALIDATE_URL)) {
                        $imagem_atual = $imagem_url;
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

            // Obter os detalhes do produto
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            // Obter categorias disponíveis
            $sqlCategorias = "SELECT id, nome FROM categorias ORDER BY nome";
            $stmtCategorias = $conn->query($sqlCategorias);
            $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Incluir a vista de formulário de edição
        require_once './app/views/produtos/editFormView.php';
    }


    // Método para eliminar um produto
    public function delete($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            // Obter detalhes do produto para apagar a imagem associada
            $sql = "SELECT imagem FROM produtos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produto) {
                // Apagar o ficheiro de imagem
                if (!empty($produto['imagem']) && file_exists($produto['imagem'])) {
                    unlink($produto['imagem']);
                }

                // Eliminar o produto da base de dados
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

        // Redirecionar para a lista de produtos com feedback
        header("Location: /produtos/edit?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }
}
