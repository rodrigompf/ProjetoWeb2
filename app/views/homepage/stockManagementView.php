<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Função para exibir o modal de adicionar stock
        function showModal(productId, currentStock) {
            const modal = document.getElementById('addStockModal');
            modal.classList.remove('hidden'); // Exibe o modal
            document.getElementById('productIdInput').value = productId; // Define o ID do produto no modal
            document.getElementById('currentStockValue').innerText = currentStock; // Mostra o stock atual no modal
        }

        // Função para ocultar o modal
        function hideModal() {
            const modal = document.getElementById('addStockModal');
            modal.classList.add('hidden'); // Oculta o modal
        }
    </script>
</head>

<body class="bg-[rgb(247,246,223)] flex flex-col items-center min-h-screen p-4">

    <!-- Botão para voltar à página anterior -->
    <a href="javascript:history.back()"
        class="mb-4 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Voltar
    </a>

    <div class="w-full max-w-4xl bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-6">Gestão de stock</h1>

        <!-- Formulário para selecionar a categoria -->
        <form method="GET" action="/stock-management">
            <label for="categoria_id" class="block text-lg font-semibold mb-2">Selecionar Categoria:</label>
            <select name="categoria_id" id="categoria_id" class="block w-full p-3 border rounded mb-6">
                <option value="">-- Selecione uma Categoria --</option>
                <!-- Preenche as opções de categorias dinamicamente -->
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['id']) ?>"
                        <?= isset($_GET['categoria_id']) && $_GET['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                Ver Produtos
            </button>
        </form>

        <?php if (!empty($produtos)): ?>
            <!-- Título da lista de produtos -->
            <h2 class="text-xl font-semibold mt-8 mb-4">Produtos:</h2>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Nome</th>
                        <th class="border border-gray-300 px-4 py-2">Preço</th>
                        <th class="border border-gray-300 px-4 py-2">Stock</th>
                        <th class="border border-gray-300 px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop através dos produtos para exibi-los na tabela -->
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['id']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td class="border border-gray-300 px-4 py-2">€<?= htmlspecialchars(number_format($produto['preco'], 2)) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($produto['stock']) ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <!-- Botão para abrir o modal e adicionar stock -->
                                <button onclick="showModal('<?= $produto['id'] ?>', '<?= $produto['stock'] ?>')"
                                    class="px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transition">
                                    Adicionar Stock
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['categoria_id'])): ?>
            <!-- Caso não haja produtos para a categoria selecionada -->
            <p class="mt-6 text-red-500">Nenhum produto encontrado para a categoria selecionada.</p>
        <?php endif; ?>
    </div>

    <!-- Modal para adicionar stock -->
    <div id="addStockModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Adicionar Stock</h2>
            <p>Stock Atual: <span id="currentStockValue" class="font-bold"></span></p>
            <form method="POST" action="/stock-management/add">
                <!-- Campo oculto para o ID do produto -->
                <input type="hidden" name="product_id" id="productIdInput">
                <label for="newStock" class="block text-lg font-semibold mt-4">Nova quantidade de stock:</label>
                <!-- Campo para inserir a nova quantidade de stock -->
                <input type="number" name="new_stock" id="newStock" class="block w-full p-3 border rounded mb-6" required>
                
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

                <div class="flex justify-between">
                    <!-- Botão para fechar o modal -->
                    <button type="button" onclick="hideModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 transition">
                        Voltar
                    </button>
                    <!-- Botão para submeter o formulário de adicionar stock -->
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>