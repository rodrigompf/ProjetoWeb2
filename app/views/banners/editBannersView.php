<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Banner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[rgb(247,246,223)] flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>
        <h1 class="text-2xl font-bold mb-4">Editar Banner</h1>

        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label class="block mb-2 font-semibold" for="name">Nome</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($banner['name']) ?>" class="w-full p-2 border rounded" required>

            <label class="block mt-4 mb-2 font-semibold" for="image">Nova Imagem (opcional)</label>
            <input type="file" name="image" id="image" class="w-full p-2 border rounded" accept="image/*">

            <p class="text-sm text-gray-500 mt-2">Imagem atual: <?= htmlspecialchars(basename($banner['image_url'])) ?></p>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
                Salvar Alterações
            </button>
        </form>
    </div>
</body>

</html>
