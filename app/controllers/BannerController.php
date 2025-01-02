<?php

require_once './app/models/homeModel.php';

class BannerController
{
    private $homeModel;

    // Construtor para inicializar o modelo da página inicial
    public function __construct()
    {
        $this->homeModel = new HomeModel();
    }

    // Obtém todos os banners do modelo
    public function getBanners(): array
    {
        // Recupera os banners a partir do modelo
        return $this->homeModel->getBanners();
    }

    // Obtém o índice atual do banner, com validação
    public function getCurrentBannerIndex($currentIndex): int
    {
        // Obtemos a lista de banners
        $banners = $this->getBanners();

        // Verifica se existe um índice de banner passado via query string; caso contrário, usa o índice atual
        $currentIndex = isset($_GET['banner']) ? (int)$_GET['banner'] : $currentIndex;

        // Garante que o índice atual é válido (dentro dos limites do array de banners)
        return max(0, min($currentIndex, count($banners) - 1));
    }

    // Renderiza os banners e passa os dados para a vista
    public function renderBanners()
    {
        // Obtém a lista de banners
        $banners = $this->getBanners();

        // Inclui a vista correspondente para exibir os banners
        include './app/views/bannerView.php';
    }
    public function createBanner()
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = $_POST['image_url'] ?? null;

            if (!$image_url || !filter_var($image_url, FILTER_VALIDATE_URL)) {
                $error = "A URL da imagem fornecida não é válida.";
            } else {
                try {
                    $conn = Connection::getInstance();
                    $sql = "INSERT INTO banners (image_url) VALUES (:image_url)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':image_url', $image_url);
                    if ($stmt->execute()) {
                        $success = "Banner adicionado com sucesso!";
                    } else {
                        $error = "Erro ao adicionar o banner.";
                    }
                } catch (PDOException $e) {
                    $error = "Erro no banco de dados: " . $e->getMessage();
                }
            }
        }

        include './app/views/createBannerView.php';
    }

    // Método para listar os banners editáveis
    public function editBannerList()
    {
        try {
            $conn = Connection::getInstance();
            $sql = "SELECT id, image_url FROM banners";
            $stmt = $conn->query($sql);
            $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $banners = [];
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        // Incluir a vista para listar os banners
        require_once './app/views/editBannerListView.php';
    }

    // Método para exibir o formulário de edição de um banner
    public function editBannerForm($id)
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = $_POST['image_url'] ?? null;

            if (!$image_url || !filter_var($image_url, FILTER_VALIDATE_URL)) {
                $error = "A URL da imagem fornecida não é válida.";
            } else {
                try {
                    $conn = Connection::getInstance();
                    $sql = "UPDATE banners SET image_url = :image_url WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':image_url', $image_url);
                    $stmt->bindParam(':id', $id);

                    if ($stmt->execute()) {
                        $success = "Banner atualizado com sucesso!";
                    } else {
                        $error = "Erro ao atualizar o banner.";
                    }
                } catch (PDOException $e) {
                    $error = "Erro no banco de dados: " . $e->getMessage();
                }
            }
        }

        $sql = "SELECT * FROM banners WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        include './app/views/editBannerFormView.php';
    }

    // Método para eliminar um banner
    public function deleteBanner($id)
    {
        $error = null;
        $success = null;

        try {
            $conn = Connection::getInstance();
            $sql = "DELETE FROM banners WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $success = "Banner eliminado com sucesso!";
            } else {
                $error = "Erro ao eliminar o banner.";
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }

        header("Location: /banners?success=" . urlencode($success) . "&error=" . urlencode($error));
        exit;
    }
}
