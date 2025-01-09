<?php

require_once './app/database/Connection.php';

class BannersController
{
    // Exibir todos os banners
    public function index()
    {
        try {
            $conn = Connection::getInstance();

            // Obter todos os banners da base de dados
            $sql = "SELECT * FROM banners ORDER BY name ASC";
            $stmt = $conn->query($sql);
            $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $banners = [];
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Incluir a vista para exibir os banners
        require_once './app/views/banners/bannersView.php';
    }

    // Adicionar um novo banner
    public function create()
    {
        $error = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obter os dados do formulário
                $name = $_POST['name'] ?? null;
                $image = $_FILES['image'] ?? null;

                // Verificar se os campos obrigatórios foram preenchidos
                if (!$name || !$image) {
                    $error = "Os campos Nome e Imagem são obrigatórios.";
                } else {
                    // Pasta para armazenar os banners
                    $uploadDir = './assets/banners/';

                    // Verifica e cria a pasta, se necessário
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Processar o upload da imagem
                    if ($image['error'] === UPLOAD_ERR_OK) {
                        $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
                        $imageName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) . '.' . $imageExtension; // Nome do arquivo baseado no nome fornecido
                        $uploadPath = $uploadDir . $imageName;

                        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                            // Inserir o novo banner na base de dados
                            $conn = Connection::getInstance();
                            $sql = "INSERT INTO banners (name, image_url) VALUES (:name, :image_url)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':image_url', $uploadPath);

                            if ($stmt->execute()) {
                                // Redirecionar para a vista de banners em caso de sucesso
                                header("Location: /banners");
                                exit;
                            } else {
                                $error = "Erro ao adicionar banner na base de dados.";
                            }
                        } else {
                            $error = "Erro ao salvar o arquivo no servidor.";
                        }
                    } else {
                        $error = "Erro no upload da imagem.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Incluir a vista para adicionar um novo banner
        require_once './app/views/banners/addBannersView.php';
    }



    // Eliminar um banner
    public function delete($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();

            // Eliminar o banner com o ID especificado
            $sql = "DELETE FROM banners WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $success = "Banner eliminado com sucesso!";
            } else {
                $error = "Erro ao eliminar banner.";
            }
        } catch (PDOException $e) {
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Redirecionar para a lista de banners com mensagens de erro ou sucesso
        header("Location: /banners?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }

    // Editar um banner
    public function edit($id)
    {
        $error = null;

        try {
            $conn = Connection::getInstance();

            // Obter os detalhes do banner com o ID especificado
            $sql = "SELECT * FROM banners WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $banner = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$banner) {
                throw new Exception("Banner não encontrado.");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obter os novos dados do formulário
                $name = $_POST['name'] ?? null;
                $newImage = $_FILES['image'] ?? null;

                // Verificar se os campos obrigatórios foram preenchidos
                if (!$name) {
                    $error = "O campo Nome é obrigatório.";
                } else {
                    $imagePath = $banner['image_url']; // Caminho da imagem atual

                    // Se um novo arquivo foi enviado, processá-lo
                    if ($newImage && $newImage['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = './assets/banners/';
                        $imageExtension = pathinfo($newImage['name'], PATHINFO_EXTENSION);
                        $imageName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $banner['name']) . '.' . $imageExtension;
                        $uploadPath = $uploadDir . $imageName;

                        // Substituir o arquivo existente
                        if (move_uploaded_file($newImage['tmp_name'], $uploadPath)) {
                            $imagePath = $uploadPath;
                        } else {
                            $error = "Erro ao salvar a nova imagem.";
                        }
                    }

                    // Atualizar os dados no banco de dados
                    $sql = "UPDATE banners SET name = :name, image_url = :image_url WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':image_url', $imagePath);
                    $stmt->bindParam(':id', $id);

                    if ($stmt->execute()) {
                        // Redirecionar para a lista de banners em caso de sucesso
                        header("Location: /banners");
                        exit;
                    } else {
                        $error = "Erro ao atualizar o banner.";
                    }
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // Incluir a vista para editar o banner
        require_once './app/views/banners/editBannersView.php';
    }
}
