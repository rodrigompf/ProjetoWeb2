<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // Function to remove 1 item from the cart
        async function remove1(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 1
                    }) // Remove 1 item
                });
                if (response.ok) {
                    location.reload(); // Reload the page after successful removal
                } else {
                    console.error('Failed to remove 1 item.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Function to remove all items of a product from the cart
        async function removeAll(productId) {
            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: 'all'
                    }) // Remove all items
                });
                if (response.ok) {
                    location.reload(); // Reload the page after successful removal
                } else {
                    console.error('Failed to remove all items.');
                }
            } catch (error) {
                console.error('Error:', error);
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
                            <th class="px-4 py-2 text-center">Nome</th>
                            <th class="px-4 py-2 text-center">Preço</th>
                            <th class="px-4 py-2 text-center">Quantidade</th>
                            <th class="px-4 py-2 text-center">Desconto (%)</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <?php require_once __DIR__ . '/../models/ProdutosModel.php'; ?>
                            <?php $model = new ProdutosModel(); ?>
                            <?php $produto = $model->getProdutoById($product_id); ?>

                            <?php
                            // Dados do produto recuperados da base de dados
                            $nome = htmlspecialchars($produto['nome']);
                            $precoOriginal = (float)$produto['preco'];
                            $descontoPercent = isset($produto['desconto']) ? (float)$produto['desconto'] : 0;
                            $discountPrice = isset($produto['discount_price']) ? (float)$produto['discount_price'] : $precoOriginal;

                            // Calculate discount price if discount percentage is available
                            if ($descontoPercent > 0) {
                                $precoComDesconto = $precoOriginal * (1 - ($descontoPercent / 100));
                            } else {
                                $precoComDesconto = $discountPrice;
                            }
                            ?>

                            <tr>
                                <td class="border-t px-4 py-2 text-center"><?= $nome ?></td>

                                <!-- Preço do Produto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if ($precoComDesconto < $precoOriginal): ?>
                                        <p class="text-sm text-gray-500 line-through">
                                            €<?= number_format($precoOriginal, 2) ?>
                                        </p>
                                        <p class="text-lg text-green-600 font-semibold">
                                            €<?= number_format($precoComDesconto, 2) ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-lg text-green-600 font-semibold">€<?= number_format($precoOriginal, 2) ?></p>
                                    <?php endif; ?>
                                </td>

                                <!-- Quantidade -->
                                <td class="border-t px-4 py-2 text-center"><?= $item['quantity'] ?></td>

                                <!-- Percentual de Desconto -->
                                <td class="border-t px-4 py-2 text-center">
                                    <?php if ($descontoPercent > 0): ?>
                                        <span class="bg-red-500 text-white rounded px-2 py-1 text-sm">
                                            -<?= number_format($descontoPercent, 0) ?>%
                                        </span>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Botões de Ação -->
                                <td class="border-t px-4 py-2 text-center">
                                    <button onclick="remove1(<?= $product_id ?>)"
                                        class="bg-yellow-500 text-white rounded-lg py-2 px-4 hover:bg-yellow-600 transition">
                                        Remover 1
                                    </button>
                                    <button onclick="removeAll(<?= $product_id ?>)"
                                        class="bg-red-500 text-white rounded-lg py-2 px-4 hover:bg-red-600 transition">
                                        Remover Tudo
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center mt-6 text-gray-500 text-lg">O seu carrinho está vazio!</p>
        <?php endif; ?>

    </div>
</body>

</html>