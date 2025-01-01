<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Inclusão do cabeçalho da página -->
    <?php include './app/views/header.php'; ?>

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Histórico de Compras</h1>

        <!-- Verificar se o utilizador tem compras anteriores -->
        <?php if (!empty($purchases)): ?>
            <div class="overflow-x-auto">
                <!-- Tabela com as compras anteriores -->
                <table class="w-full bg-white rounded-lg shadow-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <!-- Cabeçalhos das colunas da tabela -->
                            <th class="px-4 py-2 text-center">Data</th>
                            <th class="px-4 py-2 text-center">Preço Total</th>
                            <th class="px-4 py-2 text-center">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop pelas compras para exibir os dados -->
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <!-- Exibição da data da compra -->
                                <td class="border-t px-4 py-2 text-center"><?= $purchase['created_at'] ?></td>
                                <!-- Exibição do preço total da compra -->
                                <td class="border-t px-4 py-2 text-center">€<?= number_format($purchase['total_price'], 2) ?></td>
                                <!-- Botão para ver os detalhes da compra -->
                                <td class="border-t px-4 py-2 text-center">
                                    <button onclick="viewDetails(<?= $purchase['id'] ?>)" class="bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600">Ver Detalhes</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Caso o utilizador não tenha compras anteriores -->
            <p class="text-center text-gray-500">Não tem compras anteriores.</p>
        <?php endif; ?>
    </div>

    <script>
        // Função para redirecionar para a página de detalhes de uma compra específica
        function viewDetails(purchaseId) {
            window.location.href = `/cart/history/details/${purchaseId}`;
        }
    </script>
</body>

</html>
