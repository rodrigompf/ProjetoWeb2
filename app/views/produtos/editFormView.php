<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[rgb(247,246,223)]">
    <div class="container mx-auto py-10">
        <!-- Botão para voltar à página anterior -->
        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Voltar
        </a>

        <!-- Título da página -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Produto</h1>

        <!-- Exibir mensagens de erro ou sucesso -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para editar o produto -->
        <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded shadow-md">

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

            <!-- Campo para editar o nome do produto -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-semibold mb-2">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto['nome']) ?>" class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Campo para editar a descrição do produto -->
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 font-semibold mb-2">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" class="w-full px-4 py-2 rounded border"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>

            <!-- Campo para editar o preço do produto -->
            <div class="mb-4">
                <label for="preco" class="block text-gray-700 font-semibold mb-2">Preço</label>
                <input type="number" name="preco" id="preco" step="0.01" value="<?= htmlspecialchars($produto['preco']) ?>" class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Campo para editar o percentagem do desconto -->
            <div class="mb-4">
                <label for="discount_price" class="block text-gray-700 font-semibold mb-2">Percentagem do desconto</label>
                <input type="number" name="discount_price" id="discount_price" step="0.01" value="<?= htmlspecialchars($produto['discount_price']) ?>" class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Campo para escolher a categoria do produto -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-semibold mb-2">Categoria</label>
                <select name="categoria_id" id="categoria_id" class="w-full px-4 py-2 rounded border">
                    <!-- Exibe as categorias disponíveis, marcando a categoria atual -->
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= $produto['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campo para definir se o produto tem desconto -->
            <div class="mb-4">
                <label for="desconto" class="block text-gray-700 font-semibold mb-2">Desconto</label>
                <select name="desconto" id="desconto" class="w-full px-4 py-2 rounded border">
                    <option value="0" <?= $produto['desconto'] == 0 ? 'selected' : '' ?>>Não</option>
                    <option value="1" <?= $produto['desconto'] == 1 ? 'selected' : '' ?>>Sim</option>
                </select>
            </div>

            <!-- Exibe a imagem atual do produto -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Imagem Atual</label>
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do Produto" class="w-48 h-48 object-cover rounded mb-4">
                <?php else: ?>
                    <p class="text-gray-600">Nenhuma imagem disponível.</p>
                <?php endif; ?>
            </div>

            <!-- Campo para editar a imagem do produto -->
            <div class="mb-4">
                <label for="imagem" class="block text-gray-700 font-semibold mb-2">Nova Imagem</label>
                <input type="file" name="imagem" id="imagem" class="w-full px-4 py-2 rounded border">
                <p class="text-sm text-gray-500 mt-2">Selecione uma nova imagem para substituir a atual.</p>
            </div>

            <!-- Botão para submeter as alterações -->
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Salvar Alterações
            </button>
        </form>
    </div>
</body>

</html>