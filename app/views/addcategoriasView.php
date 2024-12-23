<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
    <a href="javascript:history.back()" 
       class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Back
    </a>
        <h1 class="text-2xl font-bold mb-4">Adicionar Categoria</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="text-green-500"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label class="block mb-2 font-semibold" for="nome">Nome</label>
            <input type="text" name="nome" id="nome" class="w-full p-2 border rounded" required>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
                Adicionar
            </button>
        </form>
    </div>
</body>
</html>
