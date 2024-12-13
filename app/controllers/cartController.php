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
                // Obter os detalhes do produto, incluindo o desconto
                $produto = $model->getProdutoById($product_id);

                if ($produto) {
                    $produto['quantity'] = $item['quantity'];

                    // Recuperar o preço original e o desconto da base de dados
                    $precoOriginal = (float)$produto['preco'];
                    $descontoPercent = isset($produto['desconto']) ? (float)$produto['desconto'] : 0;

                    // Calcular o preço com desconto
                    if ($descontoPercent > 0) {
                        $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));
                    } else {
                        $discountPrice = $precoOriginal;  // Se não houver desconto, manter o preço original
                    }

                    // Adicionar os dados do produto com o preço com desconto
                    $produto['discount_price'] = round($discountPrice, 2); // Arredondado para 2 casas decimais
                    $produto['descontoPercent'] = $descontoPercent;

                    $produtos[] = $produto;
                }
            }
        }

        // Passar a lista de produtos com descontos para a view
        require_once __DIR__ . '/../views/cartView.php';
    }




    // Add product to the cart
    public function add($product_id)
    {
        require_once __DIR__ . '/../models/ProdutosModel.php';
        $model = new ProdutosModel();

        // Fetch the product including its discount from the database
        $produto = $model->getProdutoById((int)$product_id);

        if ($produto) {
            // Add the product to the cart
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            } else {
                // Store the discount as well
                $_SESSION['cart'][$product_id] = [
                    'quantity' => 1,
                    'discount' => $produto['desconto'], // Store the discount for later use
                    'price_with_discount' => $produto['preco'] * (1 - ($produto['desconto'] / 100)) // Calculate price with discount
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
