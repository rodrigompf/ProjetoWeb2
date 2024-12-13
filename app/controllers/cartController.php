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
                    
                    // Calculate discounted price if applicable
                    $precoOriginal = (float)$produto['preco'];
                    $descontoPercent = isset($produto['desconto']) ? (float)$produto['desconto'] : 0;

                    $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));

                    $produto['discount_price'] = $discountPrice;
                    $produto['descontoPercent'] = $descontoPercent;

                    $produtos[] = $produto;
                }
            }
        }

        require_once __DIR__ . '/../views/cartView.php';
    }

    // Remove product from the cart
    public function remove($product_id, $action)
    {
        if (!isset($_SESSION['cart'][$product_id])) {
            header('HTTP/1.1 404 Not Found');
            exit();
        }

        if ($action === '1') {
            if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                $_SESSION['cart'][$product_id]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        } elseif ($action === 'all') {
            unset($_SESSION['cart'][$product_id]);
        }

        header('HTTP/1.1 200 OK');
        exit();
    }

    // Clean the cart
    public function cleanCart()
    {
        unset($_SESSION['cart']);
        header('Location: /cart');
        exit();
    }

    // Buy products (Simulate Purchase)
    public function buy()
    {
        unset($_SESSION['cart']);
        echo "<p class='text-green-500 text-center mt-6'>Compra realizada com sucesso!</p>";
        header('Location: /');
        exit();
    }

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
                    'quantity' => 1
                ];
            }

            echo "Produto adicionado ao carrinho";
            exit();
        } else {
            echo "Erro ao adicionar produto ao carrinho";
            exit();
        }
    }
}
?>
