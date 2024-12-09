<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Produtos</h1>

        <form method="GET" class="mb-6">
            <input 
                type="text" 
                name="search" 
                placeholder="Pesquisar pelo nome" 
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                class="w-full px-4 py-2 rounded border"
            >
        </form>

        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nome</th>
                    <th class="px-4 py-2">Preço</th>
                    <th class="px-4 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($produto['id']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($produto['nome']) ?></td>
                    <td class="border px-4 py-2">$ <?= htmlspecialchars($produto['preco']) ?></td>
                    <td class="border px-4 py-2">
                        <a href="/produtos/edit/<?= $produto['id'] ?>" 
                        class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transition">
                            Editar
                        </a>
                        <a href="/produtos/delete/<?= $produto['id'] ?>" 
                        class="px-4 py-2 bg-red-500 text-white rounded shadow hover:bg-red-600 transition"
                        onclick="return confirm('Tem certeza que deseja eliminar este produto?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
