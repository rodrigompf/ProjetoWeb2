<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão
}

class CartController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../database/connection.php';
        $this->db = Connection::getInstance();
    }

    // Exibir conteúdo do carrinho
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

        // Exibir a vista do carrinho
        require_once __DIR__ . '/../views/cartView.php';
    }

    // Exibir os detalhes de uma compra
    public function details($id)
    {
        try {
            // Buscar os detalhes da compra pelo ID
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE id = ?");
            $stmt->execute([$id]);
            $purchaseDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$purchaseDetails) {
                // Caso a compra não seja encontrada, retorna um erro 404
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

            // Exibir os detalhes da compra
            require_once __DIR__ . '/../views/purchaseDetailsView.php';
        } catch (Exception $e) {
            // Caso ocorra um erro ao carregar os detalhes
            echo "Erro ao carregar os detalhes: " . $e->getMessage();
        }
    }


    // Adicionar um produto ao carrinho
    public function add($product_id)
    {
        require_once __DIR__ . '/../models/ProdutosModel.php';
        $model = new ProdutosModel();

        // Obter o produto pelo ID
        $produto = $model->getProdutoById((int)$product_id);

        if ($produto) {
            // Inicializar o carrinho se não existir
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Verificar se o produto já existe no carrinho
            if (isset($_SESSION['cart'][$product_id])) {
                // Aumentar a quantidade se o produto já estiver no carrinho
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            } else {
                // Caso contrário, adicionar o produto ao carrinho
                $_SESSION['cart'][$product_id] = [
                    'name' => $produto['nome'],
                    'imagem' => $produto['imagem'],
                    'quantity' => 1,
                    'original_price' => (float)$produto['preco'],
                    'price_with_discount' => $produto['desconto'],
                    'discount' => $produto['discount_price'],
                ];
            }

            // Retornar sucesso como resposta em formato JSON
            echo json_encode(['status' => 'success', 'message' => 'Produto adicionado ao carrinho']);
            exit();
        } else {
            // Caso o produto não exista, retornar erro
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar produto ao carrinho']);
            exit();
        }
    }

    // Remover um produto do carrinho
    public function remove($product_id)
    {
        // Verificar se o produto existe no carrinho
        if (!isset($_SESSION['cart'][$product_id])) {
            // Caso não exista, retornar erro 404
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado no carrinho']);
            exit();
        }

        // Obter os dados da quantidade a ser removida
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['quantity'] ?? '1';

        // Caso a ação seja remover todos os itens
        if ($action === 'all') {
            unset($_SESSION['cart'][$product_id]);
        } else {
            // Caso contrário, remover a quantidade especificada
            $qtyToRemove = (int)$action;
            $_SESSION['cart'][$product_id]['quantity'] -= $qtyToRemove;

            // Caso a quantidade seja 0 ou menor, remover o produto do carrinho
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }

        // Retornar sucesso como resposta em formato JSON
        header('HTTP/1.1 200 OK');
        echo json_encode(['status' => 'success']);
        exit();
    }

    // Limpar o carrinho
    public function cleanCart()
    {
        // Remover todos os itens do carrinho
        unset($_SESSION['cart']);
        // Retornar sucesso como resposta em formato JSON
        echo json_encode(['status' => 'success', 'message' => 'Carrinho limpo com sucesso']);
        exit();
    }

    // Finalizar a compra
    public function buy()
    {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $cartData = $_SESSION['cart'];
            $totalPrice = 0;
            $outOfStockItems = []; // Array para armazenar itens fora de estoque
            $insufficientStockItems = []; // Array para armazenar itens com estoque insuficiente

            // Calcular o preço total do carrinho
            foreach ($cartData as $item) {
                $totalPrice += $item['price_with_discount'] * $item['quantity'];
            }

            try {
                // Iniciar transação para garantir atomicidade
                $this->db->beginTransaction();

                // Salvar o histórico da compra
                $this->savePurchaseHistory($cartData, $totalPrice);

                // Atualizar o estoque de cada item no carrinho
                foreach ($cartData as $product_id => $item) {
                    // Buscar o estoque atual e nome do produto
                    $stmt = $this->db->prepare("SELECT stock, nome FROM produtos WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($produto) {
                        // Verificar se a quantidade no carrinho excede o estoque
                        if ($item['quantity'] > $produto['stock']) {
                            // Caso não haja estoque suficiente, adicionar ao array de itens sem estoque
                            $insufficientStockItems[] = $produto['nome'] . " (estoque disponível: " . $produto['stock'] . ")";
                        } else {
                            // Atualizar o estoque do produto
                            $newStock = $produto['stock'] - $item['quantity'];

                            // Atualizar a quantidade de estoque no banco de dados
                            $updateStmt = $this->db->prepare("UPDATE produtos SET stock = ? WHERE id = ?");
                            $updateStmt->execute([$newStock, $product_id]);
                        }
                    }
                }

                // Caso haja itens fora de estoque, lançar exceção
                if (count($insufficientStockItems) > 0) {
                    throw new Exception("Não há stock suficiente para os seguintes produtos: " . implode(', ', $insufficientStockItems));
                }

                // Se tudo ocorrer bem, realizar o commit da transação
                $this->db->commit();

                // Limpar o carrinho após a compra bem-sucedida
                unset($_SESSION['cart']);

                // Definir o tipo de conteúdo como JSON
                header('Content-Type: application/json');

                // Retornar sucesso como resposta
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Compra finalizada com sucesso!'
                ]);
                exit();
            } catch (Exception $e) {
                // Se ocorrer algum erro, realizar o rollback da transação
                $this->db->rollBack();

                // Registrar o erro para debug
                error_log("Erro durante o processo de compra: " . $e->getMessage()); // Registrar a mensagem de erro

                // Definir o tipo de conteúdo como JSON
                header('Content-Type: application/json');

                // Retornar a mensagem de erro específica
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage() // Isso incluirá a mensagem de erro sobre itens fora de stock, se aplicável
                ]);
                exit();
            }
        } else {
            // Se o carrinho estiver vazio, retornar erro
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'O carrinho está vazio.'
            ]);
            exit();
        }
    }

    // Exibir o histórico de compras
    public function history()
    {
        $userId = $_SESSION['user']['id'] ?? 0;

        try {
            // Buscar o histórico de compras do utilizador
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);
            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($purchases)) {
                // Caso o utilizador não tenha compras, exibir mensagem
                echo "Nenhuma compra encontrada.";
                exit();
            }

            // Exibir o histórico de compras
            require_once __DIR__ . '/../views/historyView.php';
        } catch (Exception $e) {
            // Caso ocorra erro ao carregar o histórico
            echo "Erro ao carregar o histórico: " . $e->getMessage();
        }
    }

    // Salvar o histórico da compra no banco de dados
    public function savePurchaseHistory($cartData, $totalPrice)
    {
        try {
            // Supondo que o ID do utilizador esteja armazenado na sessão
            $userId = $_SESSION['user']['id'] ?? 0;
            $cartDataJson = json_encode($cartData); // Codificar os dados do carrinho como string JSON

            // Inserir o histórico de compra na tabela compras_historico
            $stmt = $this->db->prepare("INSERT INTO compras_historico (user_id, total_price, cart_data, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$userId, $totalPrice, $cartDataJson]);

            // Retornar o ID inserido (opcional, caso necessário para futuras operações)
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            // Caso ocorra erro ao salvar o histórico de compra
            throw new Exception("Erro ao salvar o histórico de compras: " . $e->getMessage());
        }
    }
}
