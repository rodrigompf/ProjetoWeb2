<?php
session_start();

class CartController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../database/connection.php';
        $this->db = Connection::getInstance();
    }

    // Display Cart Contents
    public function index()
    {
        require_once __DIR__ . '/../models/ProdutosModel.php';
        $model = new ProdutosModel();

        $produtos = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $produto = $model->getProdutoById($product_id);

                if ($produto) {
                    $produto['quantity'] = $item['quantity'];

                    // Use the discount stored in the session if available
                    $descontoPercent = isset($item['discount']) ? (float)$item['discount'] : (float)$produto['desconto'];

                    $precoOriginal = (float)$produto['preco'];

                    // Calculate the discounted price
                    $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));

                    $produto['discount_price'] = round($discountPrice, 2);

                    $produtos[] = $produto;
                }
            }
        }

        require_once __DIR__ . '/../views/cartView.php';
    }

    // Add product to the cart
    public function add($product_id)
    {
        require_once __DIR__ . '/../models/ProdutosModel.php';
        $model = new ProdutosModel();

        $produto = $model->getProdutoById((int)$product_id);

        if ($produto) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $produto['nome'],
                    'imagem' => $produto['imagem'], // Store the image
                    'quantity' => 1,
                    'original_price' => (float)$produto['preco'],
                    'price_with_discount' => $produto['precoDescontado'],
                    'discount' => $produto['discount_price'],
                ];
            }

            echo json_encode(['status' => 'success', 'message' => 'Produto adicionado ao carrinho']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar produto ao carrinho']);
            exit();
        }
    }

    // Remove product from the cart (1 or all)
    public function remove($product_id)
    {
        if (!isset($_SESSION['cart'][$product_id])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado no carrinho']);
            exit();
        }

        // Get the action (quantity or 'all') from POST data
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['quantity'] ?? '1';

        if ($action === 'all') {
            unset($_SESSION['cart'][$product_id]);
        } else {
            // Remove specific quantity
            $qtyToRemove = (int)$action;
            $_SESSION['cart'][$product_id]['quantity'] -= $qtyToRemove;

            // Remove product entirely if quantity <= 0
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }

        header('HTTP/1.1 200 OK');
        echo json_encode(['status' => 'success']);
        exit();
    }

    // Clean the cart
    public function cleanCart()
    {
        unset($_SESSION['cart']);
        echo json_encode(['status' => 'success', 'message' => 'Carrinho limpo com sucesso']);
        exit();
    }

    // Função privada para salvar o histórico de compras
    private function savePurchaseHistory($cartData, $totalPrice)
    {
        $userId = $_SESSION['user']['id'] ?? 0;  // puxar ID do usuário logado
        $stmt = $this->db->prepare("INSERT INTO compras_historico (user_id, cart_data, total_price) VALUES (?, ?, ?)");
        $stmt->execute([$userId, json_encode($cartData), $totalPrice]);

        // Limite 5 compras
        $stmt = $this->db->prepare("DELETE FROM compras_historico WHERE id NOT IN (SELECT id FROM compras_historico ORDER BY created_at DESC LIMIT 5)");
        $stmt->execute();
    }
    public function buy()
    {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $cartData = $_SESSION['cart'];
            $totalPrice = 0;

            // Calcular o total da compra
            foreach ($cartData as $item) {
                $totalPrice += $item['price_with_discount'] * $item['quantity'];
            }

            try {
                // Salvar no histórico de compras
                $this->savePurchaseHistory($cartData, $totalPrice);

                // Limpar o carrinho
                unset($_SESSION['cart']);

                // Redirecionar para a página principal após a compra
                header('Location: /');
                exit();
            } catch (Exception $e) {
                // Se ocorrer um erro durante o processo de finalização, você pode tratar isso aqui
                // Por exemplo, registrar o erro e não exibir uma mensagem ao usuário
                error_log("Erro ao finalizar a compra: " . $e->getMessage());

                // Enviar uma resposta para o usuário sem erro
                echo json_encode(['status' => 'error', 'message' => 'Erro ao finalizar a compra.']);
                exit();
            }
        } else {
            // Se o carrinho estiver vazio
            echo json_encode(['status' => 'error', 'message' => 'Carrinho vazio.']);
            exit();
        }
    }

    // Exibir o histórico de compras
    public function history()
    {
        $userId = $_SESSION['user']['id'] ?? 0;  // puxar o ID do usuário logado

        try {
            // Buscar as últimas 5 compras
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);

            // Verifique se a consulta foi bem-sucedida
            if ($stmt->errorCode() != '00000') {
                throw new Exception("Erro ao executar consulta SQL: " . implode(", ", $stmt->errorInfo()));
            }

            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($purchases)) {
                echo "Nenhuma compra encontrada.";
                exit();
            }

            require_once __DIR__ . '/../views/historyView.php';  // Exibe a página do histórico
        } catch (Exception $e) {
            echo "Erro ao carregar o histórico: " . $e->getMessage();
        }
    }
}
