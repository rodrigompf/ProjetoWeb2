<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">Produtos</h1>
        </div>
    </header>
    <main class="container mx-auto p-6">
        <!-- Botão para Adicionar Produto -->
        <div class="mb-6">
            <a href="/produtos/create" class="bg-green-600 text-white py-2 px-4 rounded">Adicionar Produto</a>
        </div>

        <!-- Barra de Pesquisa -->
        <form method="get" action="/produtos" class="mb-6">
            <input type="text" name="pesquisa" placeholder="Pesquisar produtos..." class="border rounded py-2 px-3 w-full md:w-1/3">
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded">Pesquisar</button>
        </form>

        <!-- Lista de Produtos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <div class="bg-white shadow rounded p-4">
                    <!-- Imagem do Produto -->
                    <?php if (!empty($produto["imagem"])): ?>
                        <img src="<?php echo htmlspecialchars($produto["imagem"]); ?>" alt="<?php echo htmlspecialchars($produto["nome"]); ?>" class="mb-4 w-full h-32 object-cover rounded">
                    <?php endif; ?>

                    <!-- Nome e Categoria -->
                    <h2 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($produto["nome"]); ?></h2>
                    <p class="text-sm text-gray-500">Categoria:
                        <?php echo htmlspecialchars($produto["categoria"] ?? 'Sem Categoria'); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Categorias em Destaque -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-6 text-gray-700">Categorias</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Categoria Peixe -->
                <a href="/produtos?categoria=peixe" class="block text-white text-center py-8 rounded shadow hover:opacity-80 transition"
                    style="background-image: url('/assets/peixe.jpg'); background-size: cover; background-position: center;">
                    <h3 class="text-xl font-bold bg-black bg-opacity-50 inline-block px-4 py-2 rounded">Peixe</h3>
                </a>
                <!-- Categoria Carne -->
                <a href="/produtos?categoria=carne" class="block text-white text-center py-8 rounded shadow hover:opacity-80 transition"
                    style="background-image: url('/assets/carne.jpg'); background-size: cover; background-position: center;">
                    <h3 class="text-xl font-bold bg-black bg-opacity-50 inline-block px-4 py-2 rounded">Carne</h3>
                </a>
                <!-- Categoria Frutas -->
                <a href="/produtos?categoria=frutas" class="block text-white text-center py-8 rounded shadow hover:opacity-80 transition"
                    style="background-image: url('/assets/frutas.jpg'); background-size: cover; background-position: center;">
                    <h3 class="text-xl font-bold bg-black bg-opacity-50 inline-block px-4 py-2 rounded">Frutas</h3>
                </a>
            </div>
        </div>

        <!-- Mostrar todos os produtos abaixo das categorias -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-6 text-gray-700">
                <?php echo isset($_GET['categoria']) ? 'Produtos de ' . ucfirst($_GET['categoria']) : 'Todos os Produtos'; ?>
            </h2>

            <ul class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if (count($produtosView) > 0): ?>
                    <?php foreach ($produtosView as $produto): ?>
                        <li class="bg-white p-4 rounded-lg shadow-md">
                            <img src="/assets/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="w-full h-48 object-cover rounded-t-lg">
                            <div class="p-4">
                                <h3 class="text-lg font-bold"><?php echo $produto['nome']; ?></h3>
                                <p class="text-sm text-gray-600"><?php echo $produto['descricao']; ?></p>
                                <p class="text-xl text-gray-800 mt-2"><?php echo '€' . number_format($produto['preco'], 2); ?></p>
                                <p class="text-sm text-gray-500 mt-1">Categoria: <?php echo ucfirst($produto['categoria_nome']); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-lg text-gray-500">Nenhum produto encontrado nesta categoria.</p>
                <?php endif; ?>
            </ul>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>