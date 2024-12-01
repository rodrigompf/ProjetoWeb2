<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <!-- Categorias -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Categorias</h1>
        <div class="flex flex-wrap gap-4">
            <!-- Categoria Peixe -->
            <a href="/produtos?categoria=peixe"
                class="block w-32 h-32 bg-cover bg-center rounded shadow"
                style="background-image: url('/assets/peixe.jpg');">
                <div class="bg-black bg-opacity-50 text-white text-center h-full flex items-center justify-center rounded">
                    <span class="text-lg font-bold">Peixe</span>
                </div>
            </a>
            <!-- Categoria Carne -->
            <a href="/produtos?categoria=carne"
                class="block w-32 h-32 bg-cover bg-center rounded shadow"
                style="background-image: url('/assets/carne.jpg');">
                <div class="bg-black bg-opacity-50 text-white text-center h-full flex items-center justify-center rounded">
                    <span class="text-lg font-bold">Carne</span>
                </div>
            </a>
            <!-- Categoria Frutas -->
            <a href="/produtos?categoria=frutas"
                class="block w-32 h-32 bg-cover bg-center rounded shadow"
                style="background-image: url('/assets/frutas.jpg');">
                <div class="bg-black bg-opacity-50 text-white text-center h-full flex items-center justify-center rounded">
                    <span class="text-lg font-bold">Frutas</span>
                </div>
            </a>
        </div>

        <!-- Produtos -->
        <div class="mt-10">
            <?php if (!empty($_GET['categoria']) && count($produtosView) > 0): ?>
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    Produtos de <?php echo ucfirst($_GET['categoria']); ?>
                </h2>
                <ul class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($produtosView as $produto): ?>
                        <li class="bg-white border rounded-lg shadow">
                            <img src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>"
                                alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                class="w-full h-48 object-cover rounded-t-lg">
                            <div class="p-4">
                                <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                                <p class="text-lg text-gray-800 mt-4 font-bold">€<?php echo number_format($produto['preco'], 2); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif (!empty($_GET['categoria'])): ?>
                <p class="text-gray-500 text-lg">Nenhum produto encontrado nesta categoria.</p>
            <?php else: ?>
                <p class="text-gray-500 text-lg">Escolha uma categoria para ver os produtos.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>