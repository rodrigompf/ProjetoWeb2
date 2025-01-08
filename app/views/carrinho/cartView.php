<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        /**
         * Função para remover 1 item do carrinho.
         */
        async function remove1(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 1 // Remover apenas 1 unidade
                    })
                });
                if (response.ok) {
                    location.reload(); // Recarrega a página para atualizar o carrinho
                } else {
                    console.error('Falha ao remover 1 item.');
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        /**
         * Função para remover todos os itens de um produto do carrinho.
         */
        async function removeAll(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 'all' // Remover todos os itens
                    })
                });
                if (response.ok) {
                    location.reload(); // Recarrega a página para atualizar o carrinho
                } else {
                    console.error('Falha ao remover todos os itens.');
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        /**
         * Função para finalizar a compra.
         */
        async function finalizarCompra() {
            try {
                const response = await fetch('/cart/buy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                // Regista a resposta para verificar se é HTML ou JSON
                const responseText = await response.text();
                console.log('Texto da resposta:', responseText);

                // Tenta analisar a resposta como JSON
                const data = JSON.parse(responseText);

                if (data.status === 'error') {
                    alert(`Erro: ${data.message}`); // Mostra erro caso a compra falhe
                } else if (data.status === 'success') {
                    alert(data.message); // Mostra mensagem de sucesso
                    window.location.href = '/'; // Redireciona após sucesso
                }
            } catch (error) {
                console.error('Erro inesperado:', error);
                alert("Ocorreu um erro ao finalizar a compra.");
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include './app/views/homepage/header.php'; ?>
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">O Seu Carrinho</h1>

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
                            <th class="px-4 py-2 text-center">Total</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCarrinho = 0; // Variável para somar o total do carrinho
                        foreach ($_SESSION['cart'] as $product_id => $item):
                            $precoComDesconto = $item['price_with_discount'];
                            $quantidade = $item['quantity'];
                            $totalItem = $precoComDesconto * $quantidade; // Calcular o total do item
                            $totalCarrinho += $totalItem; // Adicionar ao total do carrinho
                        ?>
                            <tr>
                                <!-- Imagem do Produto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if (!empty($item['imagem'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-24 h-24 object-cover">
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

                                <!-- Total do Item -->
                                <td class="border-t px-4 py-2 text-center">
                                    <p class="text-lg font-semibold">€<?= number_format($totalItem, 2) ?></p>
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

            <!-- Total do Carrinho -->
            <div class="flex justify-end mt-6 text-lg font-bold">
                <p class="mr-10">Total do Carrinho: €<?= number_format($totalCarrinho, 2) ?></p>
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