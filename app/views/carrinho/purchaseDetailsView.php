<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Compra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[rgb(247,246,223)] py-10">

    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-6">Detalhes da Compra</h1>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Exibe as informações básicas da compra, como data e total -->
            <div class="mb-6">
                <p><strong class="font-semibold">Data:</strong> <?= htmlspecialchars($purchaseDetails['created_at']) ?></p>
                <p><strong class="font-semibold">Total:</strong> €<?= htmlspecialchars(number_format($purchaseDetails['total_price'], 2)) ?></p>
            </div>

            <!-- Título para a lista de produtos -->
            <h2 class="text-2xl font-semibold mb-4">Produtos:</h2>

            <!-- Verifica se há dados do carrinho (produtos da compra) -->
            <?php if (!empty($purchaseDetails['cart_data'])): ?>
                <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                    <!-- Cabeçalho da tabela com as colunas: Imagem, Nome, Quantidade, Preço Unitário e Subtotal -->
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Imagem</th>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-left">Quantidade</th>
                            <th class="px-4 py-2 text-left">Preço Unitário</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop que percorre cada item do carrinho de compras -->
                        <?php foreach ($purchaseDetails['cart_data'] as $item): ?>
                            <tr class="border-b">
                                <!-- Exibe a imagem do produto -->
                                <td class="px-4 py-2">
                                    <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-16 h-16 object-cover rounded-md">
                                </td>
                                <!-- Exibe o nome do produto -->
                                <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                                <!-- Exibe a quantidade do produto -->
                                <td class="px-4 py-2"><?= htmlspecialchars($item['quantity']) ?></td>
                                <!-- Exibe o preço unitário do produto com desconto -->
                                <td class="px-4 py-2">€<?= htmlspecialchars(number_format($item['price_with_discount'], 2)) ?></td>
                                <!-- Exibe o subtotal do produto (preço unitário x quantidade) -->
                                <td class="px-4 py-2">€<?= htmlspecialchars(number_format($item['price_with_discount'] * $item['quantity'], 2)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- Mensagem caso não haja produtos nesta compra -->
                <p class="text-center text-gray-500">Nenhum produto encontrado nesta compra.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
