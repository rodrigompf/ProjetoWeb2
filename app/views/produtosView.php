<?php
session_start(); // Certifique-se de que a sessão esteja iniciada
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
    <?php include './app/views/header.php'; ?>

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Explore Nossas Categorias</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <a href="/produtos/categoria/<?php echo htmlspecialchars($categoria['nome']); ?>"
                        class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <img src="../../assets/<?php echo htmlspecialchars($categoria['nome']); ?>.png"
                            alt="<?php echo htmlspecialchars($categoria['nome']); ?>"
                            class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                            <span class="text-xl font-semibold text-white"><?php echo htmlspecialchars($categoria['nome']); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-lg text-center">Nenhuma categoria encontrada.</p>
            <?php endif; ?>
        </div>

        <div class="mt-12">
            <p class="text-gray-500 text-lg text-center">Escolha uma categoria para ver os produtos.</p>
        </div>
    </div>
</body>

</html>
