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

    // Exibir Conteúdo do Carrinho
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

                    // Calcular o preço com desconto
                    $descontoPercent = isset($item['discount']) ? (float)$item['discount'] : (float)$produto['desconto'];
                    $precoOriginal = (float)$produto['preco'];
                    $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));

                    $produto['discount_price'] = round($discountPrice, 2);

                    $produtos[] = $produto;
                }
            }
        }

        require_once __DIR__ . '/../views/cartView.php';
    }

    // Exibir detalhes de uma compra
    public function details($id)
    {
        try {
            // Buscar detalhes da compra pelo ID
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE id = ?");
            $stmt->execute([$id]);
            $purchaseDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$purchaseDetails) {
                header("HTTP/1.0 404 Not Found");
                echo "Compra não encontrada.";
                exit();
            }

            // Decodificar os dados do carrinho armazenados como JSON
            $purchaseDetails['cart_data'] = json_decode($purchaseDetails['cart_data'], true);

            // Verificar se os produtos foram corretamente decodificados
            if (!isset($purchaseDetails['cart_data']) || !is_array($purchaseDetails['cart_data'])) {
                $purchaseDetails['cart_data'] = [];
            }

            require_once __DIR__ . '/../views/purchaseDetailsView.php';
        } catch (Exception $e) {
            echo "Erro ao carregar os detalhes: " . $e->getMessage();
        }
    }


    // Adicionar produto ao carrinho
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
                    'imagem' => $produto['imagem'],
                    'quantity' => 1,
                    'original_price' => (float)$produto['preco'],
                    'price_with_discount' => $produto['desconto'],
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

    // Remover produto do carrinho
    public function remove($product_id)
    {
        if (!isset($_SESSION['cart'][$product_id])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado no carrinho']);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['quantity'] ?? '1';

        if ($action === 'all') {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $qtyToRemove = (int)$action;
            $_SESSION['cart'][$product_id]['quantity'] -= $qtyToRemove;

            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }

        header('HTTP/1.1 200 OK');
        echo json_encode(['status' => 'success']);
        exit();
    }

    // Limpar o carrinho
    public function cleanCart()
    {
        unset($_SESSION['cart']);
        echo json_encode(['status' => 'success', 'message' => 'Carrinho limpo com sucesso']);
        exit();
    }

    // Finalizar compra
    public function buy()
{
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $cartData = $_SESSION['cart'];
        $totalPrice = 0;
        $outOfStockItems = []; // Array to track out-of-stock items
        $insufficientStockItems = []; // Array to track items with insufficient stock

        // Calculate the total price of the cart
        foreach ($cartData as $item) {
            $totalPrice += $item['price_with_discount'] * $item['quantity'];
        }

        try {
            // Start transaction for atomicity
            $this->db->beginTransaction();

            // Save purchase history
            $this->savePurchaseHistory($cartData, $totalPrice);

            // Update stock levels for each item in the cart
            foreach ($cartData as $product_id => $item) {
                // Fetch the current stock and product name
                $stmt = $this->db->prepare("SELECT stock, nome FROM produtos WHERE id = ?");
                $stmt->execute([$product_id]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($produto) {
                    if ($item['quantity'] > $produto['stock']) {
                        $insufficientStockItems[] = $produto['nome'] . " (available stock: " . $produto['stock'] . ")";
                    } else {
                        $newStock = $produto['stock'] - $item['quantity'];

                        // Update the stock
                        $updateStmt = $this->db->prepare("UPDATE produtos SET stock = ? WHERE id = ?");
                        $updateStmt->execute([$newStock, $product_id]);
                    }
                }
            }

            // If there are any out-of-stock items, throw an exception
            if (count($insufficientStockItems) > 0) {
                throw new Exception("There is insufficient stock for the following product(s): " . implode(', ', $insufficientStockItems));
            }

            // Commit the transaction if everything is successful
            $this->db->commit();

            // Clear the cart after successful purchase
            unset($_SESSION['cart']);

            // Set Content-Type to JSON
            header('Content-Type: application/json');

            // Return success message
            echo json_encode([
                'status' => 'success',
                'message' => 'Purchase completed successfully!'
            ]);
            exit();

        } catch (Exception $e) {
            // Rollback the transaction if anything goes wrong
            $this->db->rollBack();

            // Log the error for debugging
            error_log("Error during purchase process: " . $e->getMessage()); // Log the error message

            // Set Content-Type to JSON
            header('Content-Type: application/json');

            // Return the specific error message to the client
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage() // This will include the "out of stock" error message if applicable
            ]);
            exit();
        }
    } else {
        // If the cart is empty, return an error message
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'The cart is empty.'
        ]);
        exit();
    }
}




    // Exibir o histórico de compras
    public function history()
    {
        $userId = $_SESSION['user']['id'] ?? 0;

        try {
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);
            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($purchases)) {
                echo "Nenhuma compra encontrada.";
                exit();
            }

            require_once __DIR__ . '/../views/historyView.php';
        } catch (Exception $e) {
            echo "Erro ao carregar o histórico: " . $e->getMessage();
        }
    }
    public function savePurchaseHistory($cartData, $totalPrice)
    {
        try {
            // Assuming user ID is stored in session
            $userId = $_SESSION['user']['id'] ?? 0;
            $cartDataJson = json_encode($cartData); // Encode cart data as JSON string
            
            // Insert into the compras_historico table
            $stmt = $this->db->prepare("INSERT INTO compras_historico (user_id, total_price, cart_data, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$userId, $totalPrice, $cartDataJson]);

            // You could return the inserted ID for later use if necessary
            return $this->db->lastInsertId(); // Optional
        } catch (Exception $e) {
            // Handle any database errors here
            throw new Exception("Error saving purchase history: " . $e->getMessage());
        }
    }
}
