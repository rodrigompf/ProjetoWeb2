<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <!-- Categorias -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Explore Nossas Categorias</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Categoria Peixe -->
            <a href="/produtos?categoria=peixe"
                class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
                <img src="/assets/peixe.jpg" alt="Peixe" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <span class="text-xl font-semibold text-white">Peixe</span>
                </div>
            </a>
            <!-- Categoria Carne -->
            <a href="/produtos?categoria=carne"
                class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
                <img src="/assets/carne.jpg" alt="Carne" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <span class="text-xl font-semibold text-white">Carne</span>
                </div>
            </a>
            <!-- Categoria Frutas -->
            <a href="/produtos?categoria=frutas"
                class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
                <img src="/assets/frutas.jpg" alt="Frutas" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <span class="text-xl font-semibold text-white">Frutas</span>
                </div>
            </a>
        </div>

        <!-- Produtos -->
        <div class="mt-12">
            <?php if (!empty($_GET['categoria']) && count($produtosView) > 0): ?>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Produtos de <?php echo ucfirst($_GET['categoria']); ?>
                </h2>
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($produtosView as $produto): ?>
                        <li class="bg-white border rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <img src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>"
                                alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                                <p class="text-lg text-gray-800 mt-4 font-bold">€<?php echo number_format($produto['preco'], 2); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif (!empty($_GET['categoria'])): ?>
                <p class="text-gray-500 text-lg text-center">Nenhum produto encontrado nesta categoria.</p>
            <?php else: ?>
                <p class="text-gray-500 text-lg text-center">Escolha uma categoria para ver os produtos.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
