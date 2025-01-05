<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <!-- Inclui o Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- Caixa de formulário centralizada -->
    <div class="bg-white p-8 rounded shadow-md w-96">
        <!-- Botão para voltar à página anterior -->
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>

        <!-- Título da página -->
        <h1 class="text-2xl font-bold mb-4">Adicionar Categoria</h1>

        <!-- Exibir mensagem de erro, se houver -->
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Exibir mensagem de sucesso, se houver -->
        <?php if (!empty($success)): ?>
            <p class="text-green-500"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Formulário para adicionar uma categoria -->
        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

            <!-- Campo para o nome da categoria -->
            <label class="block mb-2 font-semibold" for="nome">Nome</label>
            <input type="text" name="nome" id="nome" class="w-full p-2 border rounded" required>

            <!-- Campo para a URL da imagem -->
            <label class="block mt-4 mb-2 font-semibold" for="image_url">URL da Imagem</label>
            <input type="text" name="image_url" id="image_url" class="w-full p-2 border rounded" placeholder="https://example.com/imagem.jpg">
            <p class="text-sm text-gray-500 mt-2">Insira a URL da imagem para esta categoria.</p>

            <!-- Botão para submeter o formulário -->
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition">
                Adicionar
            </button>
        </form>
    </div>
</body>

</html>