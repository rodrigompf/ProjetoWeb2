<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Cabeçalho da página -->
    <?php include './app/views/homepage/header.php'; ?>

    <!-- Conteúdo principal da página -->
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Explore as nossas categorias</h1>

        <!-- Grid para exibir as categorias -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Verifica se há categorias -->
            <?php if (!empty($categorias)): ?>
                <!-- Loop para exibir cada categoria -->
                <?php foreach ($categorias as $categoria): ?>
                    <a href="/produtos/categoria/<?php echo htmlspecialchars($categoria['nome']); ?>"
                        class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <img src="<?php echo htmlspecialchars($categoria['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($categoria['nome']); ?>"
                            class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                            <span class="text-xl font-semibold text-white"><?php echo htmlspecialchars($categoria['nome']); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensagem caso não existam categorias -->
                <p class="text-gray-500 text-lg text-center">Nenhuma categoria encontrada.</p>
            <?php endif; ?>
        </div>

        <!-- Mensagem informativa sobre a seleção de categorias -->
        <div class="mt-12">
            <p class="text-gray-500 text-lg text-center">Escolha uma categoria para ver os produtos.</p>
        </div>
    </div>
</body>

</html>
