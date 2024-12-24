<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showModal(productId, currentStock) {
            const modal = document.getElementById('addStockModal');
            modal.classList.remove('hidden');
            document.getElementById('productIdInput').value = productId;
            document.getElementById('currentStockValue').innerText = currentStock;
        }

        function hideModal() {
            const modal = document.getElementById('addStockModal');
            modal.classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen p-4">

    <a href="javascript:history.back()" 
       class="mb-4 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Back
    </a>

    <div class="w-full max-w-4xl bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-6">Stock Management</h1>

        <form method="GET" action="/stock-management">
            <label for="categoria_id" class="block text-lg font-semibold mb-2">Select Category:</label>
            <select name="categoria_id" id="categoria_id" class="block w-full p-3 border rounded mb-6">
                <option value="">-- Select a Category --</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['id']) ?>"
                        <?= isset($_GET['categoria_id']) && $_GET['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                View Products
            </button>
        </form>

        <?php if (!empty($produtos)): ?>
            <h2 class="text-xl font-semibold mt-8 mb-4">Products:</h2>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">Stock</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['id']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td class="border border-gray-300 px-4 py-2">€<?= htmlspecialchars(number_format($produto['preco'], 2)) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['stock']) ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="showModal('<?= $produto['id'] ?>', '<?= $produto['stock'] ?>')"
                                        class="px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transition">
                                    Add Stock
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['categoria_id'])): ?>
            <p class="mt-6 text-red-500">No products found for the selected category.</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="addStockModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Add Stock</h2>
            <p>Current Stock: <span id="currentStockValue" class="font-bold"></span></p>
            <form method="POST" action="/stock-management/add">
                <input type="hidden" name="product_id" id="productIdInput">
                <label for="newStock" class="block text-lg font-semibold mt-4">New Stock Amount:</label>
                <input type="number" name="new_stock" id="newStock" class="block w-full p-3 border rounded mb-6" required>
                <div class="flex justify-between">
                    <button type="button" onclick="hideModal()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 transition">
                        Back
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
