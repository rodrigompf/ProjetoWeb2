<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[rgb(247,246,223)] flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <!-- Botão para voltar à página anterior -->
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>

        <!-- Título da página -->
        <h1 class="text-2xl font-bold mb-4">Editar Categoria</h1>

        <!-- Exibição de mensagem de erro, se houver -->
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Formulário para editar a categoria -->
        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

            <!-- Campo para editar o nome da categoria -->
            <label class="block mb-2 font-semibold" for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($categoria['nome']) ?>" class="w-full p-2 border rounded" required>

            <!-- Campo para editar a URL da imagem da categoria -->
            <label class="block mt-4 mb-2 font-semibold" for="image_url">URL da Imagem</label>
            <input type="text" name="image_url" id="image_url" value="<?= htmlspecialchars($categoria['image_url']) ?>" class="w-full p-2 border rounded" placeholder="https://example.com/imagem.jpg">
            <p class="text-sm text-gray-500 mt-2">Insira a URL da imagem para esta categoria.</p>

            <!-- Botão para submeter as alterações -->
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
                Salvar Alterações
            </button>
        </form>
    </div>
</body>

</html>