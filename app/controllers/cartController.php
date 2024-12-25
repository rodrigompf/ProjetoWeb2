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

                    // Usar o desconto armazenado na sessão, se disponível
                    $descontoPercent = isset($item['discount']) ? (float)$item['discount'] : (float)$produto['desconto'];

                    $precoOriginal = (float)$produto['preco'];

                    // Calcular o preço com desconto
                    $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));

                    $produto['discount_price'] = round($discountPrice, 2);

                    $produtos[] = $produto;
                }
            }
        }

        require_once __DIR__ . '/../views/cartView.php';
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
                    'imagem' => $produto['imagem'], // Armazenar a imagem
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

    // Remover produto do carrinho (1 ou todos)
    public function remove($product_id)
    {
        if (!isset($_SESSION['cart'][$product_id])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado no carrinho']);
            exit();
        }

        // Obter a ação (quantidade ou 'todos') dos dados POST
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['quantity'] ?? '1';

        if ($action === 'all') {
            unset($_SESSION['cart'][$product_id]);
        } else {
            // Remover quantidade específica
            $qtyToRemove = (int)$action;
            $_SESSION['cart'][$product_id]['quantity'] -= $qtyToRemove;

            // Remover produto completamente se a quantidade for <= 0
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

    // Função privada para salvar o histórico de compras
    private function savePurchaseHistory($cartData, $totalPrice)
    {
        $userId = $_SESSION['user']['id'] ?? 0;  // Obter o ID do usuário logado

        // Registrar os valores antes de inserir no banco de dados
        error_log("Inserindo compra para o usuário $userId com preço total: $totalPrice");

        // Inserir a compra atual
        $stmt = $this->db->prepare("INSERT INTO compras_historico (user_id, cart_data, total_price) VALUES (?, ?, ?)");
        if (!$stmt->execute([$userId, json_encode($cartData), $totalPrice])) {
            error_log("Erro ao inserir compra: " . implode(", ", $stmt->errorInfo()));
            throw new Exception("Erro ao inserir compra no banco de dados");
        }

        // Obter os últimos 5 IDs de compras para este usuário
        $stmt = $this->db->prepare("SELECT id FROM compras_historico WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        if (!$stmt->execute([$userId])) {
            error_log("Erro ao obter últimas 5 compras: " . implode(", ", $stmt->errorInfo()));
            throw new Exception("Erro ao buscar últimas 5 compras");
        }
        $recentPurchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obter todos os IDs de compras para este usuário
        $stmt = $this->db->prepare("SELECT id FROM compras_historico WHERE user_id = ?");
        if (!$stmt->execute([$userId])) {
            error_log("Erro ao obter todas as compras: " . implode(", ", $stmt->errorInfo()));
            throw new Exception("Erro ao buscar todas as compras");
        }
        $allPurchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Registrar antes da exclusão
        error_log("Todos os IDs de compras: " . implode(", ", array_column($allPurchases, 'id')));
        error_log("IDs das compras recentes: " . implode(", ", array_column($recentPurchases, 'id')));

        // Extrair os IDs das compras
        $recentPurchaseIds = array_column($recentPurchases, 'id');
        $allPurchaseIds = array_column($allPurchases, 'id');

        // Identificar quais compras excluir (aqueles que não estão entre as 5 mais recentes)
        $purchasesToDelete = array_diff($allPurchaseIds, $recentPurchaseIds);

        // Agora excluímos qualquer registro de compra mais antigo que as 5 compras mais recentes
        if (!empty($purchasesToDelete)) {
            foreach ($purchasesToDelete as $purchaseId) {
                // Registrar a tentativa de exclusão
                error_log("Excluindo compra antiga com ID: $purchaseId");

                $stmt = $this->db->prepare("DELETE FROM compras_historico WHERE id = ?");
                if (!$stmt->execute([$purchaseId])) {
                    error_log("Erro ao excluir compra com ID $purchaseId: " . implode(", ", $stmt->errorInfo()));
                    throw new Exception("Erro ao excluir compra antiga");
                }
            }
        }
    }

    public function buy()
    {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $cartData = $_SESSION['cart'];
            $totalPrice = 0;
            $outOfStockItems = []; // Array para rastrear itens fora de estoque
            $insufficientStockItems = []; // Array para rastrear itens com estoque insuficiente

            // Calcular o preço total do carrinho
            foreach ($cartData as $item) {
                $totalPrice += $item['price_with_discount'] * $item['quantity'];
            }

            try {
                // Iniciar a transação para atomicidade
                $this->db->beginTransaction();

                // Salvar o histórico de compras
                $this->savePurchaseHistory($cartData, $totalPrice);

                // Atualizar os níveis de estoque para cada item no carrinho
                foreach ($cartData as $product_id => $item) {
                    // Buscar o estoque atual e o nome do produto
                    $stmt = $this->db->prepare("SELECT stock, nome FROM produtos WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($produto) {

                        if ($item['quantity'] > $produto['stock']) {
                            $insufficientStockItems[] = $produto['nome'] . " (estoque disponível: " . $produto['stock'] . ")";
                        } else {

                            $newStock = $produto['stock'] - $item['quantity'];

                            $updateStmt = $this->db->prepare("UPDATE produtos SET stock = ? WHERE id = ?");
                            $updateStmt->execute([$newStock, $product_id]);
                        }
                    }
                }

                if (count($insufficientStockItems) > 0) {
                    throw new Exception("Não há estoque suficiente para o(s) seguinte(s) produto(s): " . implode(', ', $insufficientStockItems));
                }

                // Confirmar a transação, pois tudo ocorreu com sucesso
                $this->db->commit();

                // Limpar o carrinho após a compra bem-sucedida
                unset($_SESSION['cart']);

                // Retornar a mensagem de sucesso
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Compra finalizada com sucesso!'
                ]);
                exit();

            } catch (Exception $e) {
                // Desfazer a transação se algo der errado
                $this->db->rollBack();

                // Registrar o erro para depuração
                error_log("Erro ao finalizar a compra: " . $e->getMessage());

                // Retornar a mensagem de erro específica
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage() // Isso incluirá a mensagem de estoque insuficiente, se aplicável
                ]);
                exit();
            }
        } else {
            // Se o carrinho estiver vazio, retornar uma mensagem de erro
            echo json_encode([
                'status' => 'error',
                'message' => 'Carrinho vazio.'
            ]);
            exit();
        }
    }

    // Exibir o histórico de compras
    public function history()
    {
        $userId = $_SESSION['user']['id'] ?? 0;  // Obter o ID do usuário logado

        try {
            // Buscar as últimas 5 compras
            $stmt = $this->db->prepare("SELECT * FROM compras_historico WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$userId]);

            // Verificar se a consulta foi bem-sucedida
            if ($stmt->errorCode() != '00000') {
                throw new Exception("Erro ao executar consulta SQL: " . implode(", ", $stmt->errorInfo()));
            }

            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($purchases)) {
                echo "Nenhuma compra encontrada.";
                exit();
            }

            require_once __DIR__ . '/../views/historyView.php';  // Exibir a página do histórico
        } catch (Exception $e) {
            echo "Erro ao carregar o histórico: " . $purchaseId;

        }
    }
}
