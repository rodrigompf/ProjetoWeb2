<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
    <a href="javascript:history.back()" 
       class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Back
    </a>
        <h1 class="text-2xl font-bold mb-4">Categorias</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="text-green-500"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <ul class="space-y-2">
    <?php foreach ($categorias as $categoria): ?>
        <li class="p-2 bg-gray-200 rounded flex justify-between items-center">
            <?= htmlspecialchars($categoria['nome']) ?>
            <div class="flex space-x-2">
                <a href="/categorias/edit/<?= $categoria['id'] ?>" class="text-blue-500 hover:underline">Editar</a>
                <a href="/categorias/delete/<?= $categoria['id'] ?>" class="text-red-500 hover:underline">Eliminar</a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

        <a href="/categorias/create" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
            Adicionar Categoria
        </a>
    </div>
</body>
</html>
