<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        async function remove1(productId) {
            const response = await fetch(`/cart/remove/${productId}/1`, { method: 'POST' });
            if (response.ok) location.reload();
        }

        async function removeAll(productId) {
            const response = await fetch(`/cart/remove/${productId}/all`, { method: 'POST' });
            if (response.ok) location.reload();
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include './app/views/header.php'; ?>

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Seu Carrinho</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="min-w-full bg-white rounded-lg shadow-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200">
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Desconto (%)</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                        <?php require_once __DIR__ . '/../models/ProdutosModel.php'; ?>
                        <?php $model = new ProdutosModel(); ?>
                        <?php $produto = $model->getProdutoById($product_id); ?>

                        <?php
                        $nome = htmlspecialchars($produto['nome']);
                        $precoOriginal = (float)$produto['preco'];
                        $discountPrice = isset($produto['discount_price']) && !is_null($produto['discount_price']) ? (float)$produto['discount_price'] : $precoOriginal;
                        $descontoPercent = isset($produto['desconto']) ? (float)$produto['desconto'] : 0;

                        // Logging for debugging
                        error_log("Product: $nome | Original Price: €$precoOriginal | Discount Price: €$discountPrice | Discount % in DB: $descontoPercent");

                        if ($descontoPercent > 0) {
                            $discountPrice = $precoOriginal * (1 - ($descontoPercent / 100));
                        }

                        $calculatedDescontoPercent = 0;
                        if ($precoOriginal > 0 && $discountPrice < $precoOriginal) {
                            $calculatedDescontoPercent = round((($precoOriginal - $discountPrice) / $precoOriginal) * 100);
                        }
                        ?>

                        <tr>
                            <td class="border-t px-4 py-2"><?= $nome ?></td>

                            <!-- Product Price -->
                            <td class="border-t px-4 py-2">
                                <?php if ($discountPrice < $precoOriginal): ?>
                                    <span class="line-through text-gray-500">
                                        €<?= number_format($precoOriginal, 2) ?>
                                    </span>
                                    <span class="font-bold text-red-500">
                                        €<?= number_format($discountPrice, 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="font-bold"><?= "€" . number_format($precoOriginal, 2) ?></span>
                                <?php endif; ?>
                            </td>

                            <!-- Quantity -->
                            <td class="border-t px-4 py-2"><?= $item['quantity'] ?></td>

                            <!-- Discount Percentage -->
                            <td class="border-t px-4 py-2">
                                <?php if ($calculatedDescontoPercent > 0): ?>
                                    <span class="bg-red-500 text-white rounded px-2 py-1 text-sm">
                                        -<?= $calculatedDescontoPercent ?>%
                                    </span>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td class="border-t px-4 py-2">
                                <!-- Remove 1 Button -->
                                <button onclick="remove1(<?= $product_id ?>)"
                                        class="bg-yellow-500 text-white rounded-lg py-2 px-4 hover:bg-yellow-600 transition">
                                    Remover 1
                                </button>

                                <!-- Remove All Button -->
                                <button onclick="removeAll(<?= $product_id ?>)"
                                        class="bg-red-500 text-white rounded-lg py-2 px-4 hover:bg-red-600 transition">
                                    Remover Tudo
                                </button>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p class="text-center mt-6 text-gray-500 text-lg">Seu carrinho está vazio!</p>
        <?php endif; ?>

    </div>
</body>
</html>

