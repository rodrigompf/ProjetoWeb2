<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php include './app/views/header.php'; ?>
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Histórico de Compras</h1>

        <?php if (!empty($purchases)): ?>
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-lg shadow-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-center">Data</th>
                            <th class="px-4 py-2 text-center">Preço Total</th>
                            <th class="px-4 py-2 text-center">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <td class="border-t px-4 py-2 text-center"><?= $purchase['created_at'] ?></td>
                                <td class="border-t px-4 py-2 text-center">€<?= number_format($purchase['total_price'], 2) ?></td>
                                <td class="border-t px-4 py-2 text-center">
                                    <button onclick="viewDetails(<?= $purchase['id'] ?>)" class="bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600">Ver Detalhes</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Não tem compras anteriores.</p>
        <?php endif; ?>
    </div>

    <script>
        function viewDetails(purchaseId) {
            window.location.href = `/cart/history/details/${purchaseId}`;
        }
    </script>
</body>

</html>