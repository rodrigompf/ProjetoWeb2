<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        /**
         * Função para filtrar os produtos com base no texto digitado na barra de pesquisa
         */
        function filterProducts() {
            const query = document.getElementById('search-bar').value.toLowerCase();
            const targetSelector = document.getElementById('search-bar').getAttribute('data-target');
            const nameColumnIndex = parseInt(document.getElementById('search-bar').getAttribute('data-name-column'), 10) - 1;

            const rows = document.querySelectorAll(targetSelector); // Seleciona as linhas alvo
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells[nameColumnIndex] && cells[nameColumnIndex].textContent.toLowerCase().includes(query)) {
                    row.style.display = ''; // Exibe a linha
                } else {
                    row.style.display = 'none'; // Oculta a linha
                }
            });
        }
    </script>
</head>

<body class="bg-[rgb(247,246,223)]">
    <div class="container mx-auto py-10">
        <!-- Botão para voltar à página anterior -->
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>

        <!-- Título da página -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Produtos</h1>

        <!-- Formulário de pesquisa para filtrar produtos por nome -->
        <div class="mb-6">
            <input
                type="text"
                id="search-bar"
                onkeyup="filterProducts()"
                placeholder="Pesquisar produtos pelo nome..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                data-target="tbody tr"
                data-name-column="2">
        </div>

        <!-- Tabela com a lista de produtos -->
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nome</th>
                    <th class="px-4 py-2">Preço</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop para exibir cada produto na tabela -->
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <!-- Exibe o ID do produto -->
                        <td class="border px-4 py-2"><?= htmlspecialchars($produto['id']) ?></td>

                        <!-- Exibe o nome do produto -->
                        <td class="border px-4 py-2"><?= htmlspecialchars($produto['nome']) ?></td>

                        <!-- Exibe o preço do produto -->
                        <td class="border px-4 py-2">$ <?= htmlspecialchars($produto['preco']) ?></td>

                        <!-- Exibe a quantidade em stock do produto -->
                        <td class="border px-4 py-2"><?= htmlspecialchars($produto['stock']) ?></td>

                        <!-- Coluna para ações (editar ou eliminar) -->
                        <td class="border px-4 py-2">
                            <div class="flex justify-center gap-2">
                                <!-- Link para editar o produto -->
                                <a href="/produtos/edit/<?= $produto['id'] ?>"
                                    class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                                    Editar
                                </a>
                                <!-- Link para eliminar o produto com confirmação -->
                                <a href="/produtos/delete/<?= $produto['id'] ?>"
                                    class="px-4 py-2 bg-red-500 text-white rounded shadow hover:bg-red-600 transition"
                                    onclick="return confirm('Tem a certeza que deseja eliminar este produto?')">
                                    Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>