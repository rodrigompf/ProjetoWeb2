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
    public function index() {
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

                // Debug: Check discount percentage
                error_log('Discount Percentage: ' . $descontoPercent);

                $precoOriginal = (float)$produto['preco'];

                // Calculate the discounted price
                $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));

                // Debug the calculated discounted price
                error_log('Calculated Discounted Price: ' . $discountPrice);

                $produto['discount_price'] = round($discountPrice, 2);

                $produtos[] = $produto;
            }
        }
    }

    require_once __DIR__ . '/../views/cartView.php';
}








    // Add product to the cart
    public function add($product_id) {
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
    
            echo "Produto adicionado ao carrinho";
            exit();
        } else {
            echo "Erro ao adicionar produto ao carrinho";
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
        header('Location: /cart');
        exit();
    }

    // Simulate purchase
    public function buy()
    {
        unset($_SESSION['cart']);
        echo "<p class='text-green-500 text-center mt-6'>Compra realizada com sucesso!</p>";
        header('Location: /');
        exit();
    }
}
