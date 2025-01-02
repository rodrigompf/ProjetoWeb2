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
        require_once './app/views/bannersView.php';
    }

    // Adicionar um novo banner
    public function create()
    {
        $error = null;
        $success = null;

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obter os dados do formulário
                $name = $_POST['name'] ?? null;
                $image_url = $_POST['image_url'] ?? null;

                // Verificar se os campos obrigatórios foram preenchidos
                if (!$name || !$image_url) {
                    $error = "Os campos Nome e URL da Imagem são obrigatórios.";
                } else {
                    // Inserir o novo banner na base de dados
                    $conn = Connection::getInstance();
                    $sql = "INSERT INTO banners (name, image_url) VALUES (:name, :image_url)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':image_url', $image_url);

                    if ($stmt->execute()) {
                        // Redirecionar para a vista de banners em caso de sucesso
                        header("Location: /banners");
                        exit;
                    } else {
                        $error = "Erro ao adicionar banner.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erro na base de dados: " . $e->getMessage();
        }

        // Incluir a vista para adicionar um novo banner
        require_once './app/views/addBannersView.php';
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
        $success = null;

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
                $image_url = $_POST['image_url'] ?? null;

                // Verificar se os campos obrigatórios foram preenchidos
                if (!$name || !$image_url) {
                    $error = "Os campos Nome e URL da Imagem são obrigatórios.";
                } else {
                    // Atualizar o banner na base de dados
                    $sql = "UPDATE banners SET name = :name, image_url = :image_url WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':image_url', $image_url);
                    $stmt->bindParam(':id', $id);

                    if ($stmt->execute()) {
                        // Redirecionar para a vista de banners em caso de sucesso
                        header("Location: /banners");
                        exit;
                    } else {
                        $error = "Erro ao atualizar banner.";
                    }
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // Incluir a vista para editar o banner
        require_once './app/views/editBannersView.php';
    }
}
?>
