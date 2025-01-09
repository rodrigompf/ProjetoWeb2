<?php

require_once './app/database/Connection.php';

class CategoriasController
{

    // Exibir todas as categorias
    public function index()
    {
        try {
            $conn = Connection::getInstance();

            // Obter todas as categorias da base de dados, ordenadas por nome
            $sql = "SELECT * FROM categorias ORDER BY nome ASC";
            $stmt = $conn->query($sql);
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $categorias = [];
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Incluir a vista para exibir as categorias
        require_once './app/views/categorias/categoriasView.php';
    }

    // Adicionar uma nova categoria
    public function create()
    {
        $error = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obter os dados do formulário
                $nome = $_POST['nome'] ?? null;
                $imageFile = $_FILES['image'] ?? null;

                // Verificar se os campos obrigatórios foram preenchidos
                if (!$nome || !$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
                    $error = "O campo Nome e uma imagem válida são obrigatórios.";
                } else {
                    // Processar o upload da imagem
                    $uploadDir = './assets/categorias/';
                    $imageExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                    $imageName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nome) . '.' . $imageExtension;
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                        // Inserir a nova categoria na base de dados
                        $conn = Connection::getInstance();
                        $sql = "INSERT INTO categorias (nome, image_url) VALUES (:nome, :image_url)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':nome', $nome);
                        $stmt->bindParam(':image_url', $uploadPath);

                        if ($stmt->execute()) {
                            // Redirecionar para a vista de categorias em caso de sucesso
                            header("Location: /categorias");
                            exit;
                        } else {
                            $error = "Erro ao adicionar categoria.";
                        }
                    } else {
                        $error = "Erro ao fazer upload da imagem.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Incluir a vista para adicionar uma nova categoria
        require_once './app/views/categorias/addcategoriasView.php';
    }


    // Eliminar uma categoria
    public function delete($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            // Eliminar a categoria com o ID especificado
            $sql = "DELETE FROM categorias WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $success = "Categoria eliminada com sucesso!";
            } else {
                $error = "Erro ao eliminar categoria.";
            }
        } catch (PDOException $e) {
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Redirecionar para a lista de categorias com mensagens de erro ou sucesso
        header("Location: /categorias?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }

    // Editar uma categoria
    public function edit($id)
    {
        $error = null;

        try {
            $conn = Connection::getInstance();

            // Obter os detalhes da categoria com o ID especificado
            $sql = "SELECT * FROM categorias WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$categoria) {
                throw new Exception("Categoria não encontrada.");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obter os novos dados do formulário
                $nome = $_POST['nome'] ?? null;
                $imageFile = $_FILES['image'] ?? null;

                // Atualizar apenas o nome se nenhuma nova imagem for enviada
                $imagePath = $categoria['image_url'];

                if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = './assets/categorias/';
                    $imageExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                    $imageName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $categoria['nome']) . '.' . $imageExtension;
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                        $imagePath = $uploadPath;
                    } else {
                        $error = "Erro ao salvar a nova imagem.";
                    }
                }

                // Atualizar os dados na base de dados
                $sql = "UPDATE categorias SET nome = :nome, image_url = :image_url WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':image_url', $imagePath);
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    // Redirecionar para a lista de categorias em caso de sucesso
                    header("Location: /categorias");
                    exit;
                } else {
                    $error = "Erro ao atualizar categoria.";
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // Incluir a vista para editar a categoria
        require_once './app/views/categorias/editcategoriasView.php';
    }
}
