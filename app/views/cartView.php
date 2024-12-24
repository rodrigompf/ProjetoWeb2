<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        /**
         * Função para calcular desconto
         */
        function calculateDiscountPercentage(originalPrice, priceWithDiscount) {
            if (originalPrice > 0 && priceWithDiscount > 0) {
                return 100 - ((priceWithDiscount / originalPrice) * 100);
            }
            return 0;
        }

        /**
         * Função para remover 1 item
         */
        async function remove1(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 1
                    })
                });
                if (response.ok) {
                    location.reload();
                } else {
                    console.error('Falha ao remover 1 item.');
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        /**
         * Função para remover todos os itens
         */
        async function removeAll(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 'all'
                    })
                });
                if (response.ok) {
                    location.reload();
                } else {
                    console.error('Falha ao remover todos os itens.');
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        /**
         * Função para finalizar a compra
         */
        async function finalizarCompra() {
    try {
        // Send purchase request to the server
        const response = await fetch('/cart/buy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.status === 'error') {
            // Show the error message returned from PHP
            alert(data.message); // This will show the specific out-of-stock error or other issues
        } else if (data.status === 'success') {
            alert(data.message);  // Success message
            window.location.href = '/';  // Redirect to the homepage or another page
        }
    } catch (error) {
        console.error('Erro inesperado:', error);
        alert("Ocorreu um erro ao finalizar a compra.");
    }
}


    </script>
</head>

<body class="bg-gray-100">
    <?php include './app/views/header.php'; ?>
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Seu Carrinho</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="flex justify-center">
                <table class="w-3/4 bg-white rounded-lg shadow-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Imagem</th>
                            <th class="px-4 py-2 text-center">Nome</th>
                            <th class="px-4 py-2 text-center">Preço</th>
                            <th class="px-4 py-2 text-center">Quantidade</th>
                            <th class="px-4 py-2 text-center">Desconto (%)</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <tr>
                                <!-- Imagem do Produto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if (!empty($item['imagem'])): ?>
                                        <img src="/assets/<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-24 h-24 object-cover">
                                    <?php else: ?>
                                        <img src="/assets/default.jpg" alt="Imagem não disponível" class="w-24 h-24 object-cover">
                                    <?php endif; ?>
                                </td>

                                <!-- Nome do Produto -->
                                <td class="border-t px-4 py-2 text-center"><?= htmlspecialchars($item['name']) ?></td>

                                <!-- Preço do Produto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if ($item['original_price'] > $item['price_with_discount']): ?>
                                        <p class="text-sm text-gray-500 line-through">€<?= number_format($item['original_price'], 2) ?></p>
                                        <p class="text-lg text-green-600 font-semibold">€<?= number_format($item['price_with_discount'], 2) ?></p>
                                    <?php else: ?>
                                        <p class="text-lg text-green-600 font-semibold">€<?= number_format($item['original_price'], 2) ?></p>
                                    <?php endif; ?>
                                </td>

                                <!-- Quantidade -->
                                <td class="border-t px-4 py-2 text-center"><?= $item['quantity'] ?></td>

                                <!-- Percentual de Desconto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="bg-red-500 text-white rounded px-2 py-1 text-sm">-<?= number_format($item['discount'], 0) ?>%</span>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Ações -->
                                <td class="border-t px-4 py-2 text-center">
                                    <button onclick="remove1(<?= $product_id ?>)" class="bg-yellow-500 text-white rounded-lg py-2 px-4 hover:bg-yellow-600">Remover 1</button>
                                    <button onclick="removeAll(<?= $product_id ?>)" class="bg-red-500 text-white rounded-lg py-2 px-4 hover:bg-red-600">Remover Tudo</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botão para Finalizar Compra -->
            <div class="flex justify-center mt-6">
                <button onclick="finalizarCompra()" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Finalizar Compra
                </button>
            </div>
        <?php else: ?>
            <p class="text-center mt-6 text-gray-500 text-lg">O seu carrinho está vazio!</p>
        <?php endif; ?>

        <!-- Botão para Ver Histórico -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="flex justify-center mt-6">
                <a href="/cart/history" class="bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Ver Histórico de Compras
                </a>
            </div>
        <?php else: ?>
            <p class="text-center mt-6 text-gray-500 text-lg">Você precisa estar logado para ver o histórico de compras.</p>
        <?php endif; ?>
    </div>
</body>

</html>
