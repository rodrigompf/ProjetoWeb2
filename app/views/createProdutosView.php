<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">Criar Produto</h1>
        </div>
    </header>
    <main class="container mx-auto p-6">
        <form action="/produtos/store" method="post" enctype="multipart/form-data" class="bg-white shadow rounded p-6">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-bold mb-2">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required class="border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label for="categoria" class="block text-gray-700 font-bold mb-2">Categoria:</label>
                <select id="categoria" name="categoria_id" required class="border rounded w-full py-2 px-3">
                    <?php
                    require_once '../app/models/categoriasModel.php';
                    $categoriasModel = new CategoriasModel();
                    $categorias = $categoriasModel->getAllCategorias();
                    foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>">
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="imagem" class="block text-gray-700 font-bold mb-2">Imagem do Produto:</label>
                <input type="file" id="imagem" name="imagem" class="border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded">Criar Produto</button>
            </div>
        </form>
    </main>
    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>