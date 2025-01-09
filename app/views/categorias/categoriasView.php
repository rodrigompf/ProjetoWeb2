<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[rgb(247,246,223)] flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <!-- Link para voltar à página anterior -->
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>

        <!-- Título da página -->
        <h1 class="text-2xl font-bold mb-4">Categorias</h1>

        <!-- Exibição de erros, se houver -->
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Exibição de sucesso, se houver -->
        <?php if (!empty($success)): ?>
            <p class="text-green-500"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Lista de Categorias -->
        <ul class="space-y-2">
            <?php foreach ($categorias as $categoria): ?>
                <li class="p-2 bg-gray-200 rounded flex justify-between items-center">
                    <?= htmlspecialchars($categoria['nome']) ?>

                    <!-- Ações para editar e eliminar categorias -->
                    <div class="flex space-x-2">
                        <!-- Link para editar a categoria -->
                        <a href="/categorias/edit/<?= $categoria['id'] ?>" class="text-blue-500 hover:underline">Editar</a>

                        <!-- Link para eliminar a categoria -->
                        <a href="/categorias/delete/<?= $categoria['id'] ?>" class="text-red-500 hover:underline">Eliminar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Botão para adicionar nova categoria -->
        <a href="/categorias/create" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
            Adicionar Categoria
        </a>
    </div>
</body>

</html>