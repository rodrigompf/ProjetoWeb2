<?php
session_start(); // Start the session to check for login status
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
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/"><h1 class="text-3xl font-bold">Supermercado Online</h1></a>
            <div>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
                <?php else: ?>
                    <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div class="container mx-auto py-10">
        <!-- Categorias -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Explore Nossas Categorias</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Categoria Peixe -->
            <a href="/produtos?categoria=peixe"
    class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
    <img src="../../assets/peixe.png" alt="Peixe" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
        <span class="text-xl font-semibold text-white">Peixe</span>
    </div>
</a>

<a href="/produtos?categoria=carne"
    class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
    <img src="../../assets/carne.png" alt="Carne" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
        <span class="text-xl font-semibold text-white">Carne</span>
    </div>
</a>

<a href="/produtos?categoria=frutas"
    class="relative block h-40 rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-300">
    <img src="../../assets/frutas.png" alt="Frutas" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
        <span class="text-xl font-semibold text-white">Frutas</span>
    </div>
</a>
        </div>

        <!-- Produtos -->
        <div class="mt-12">
            <?php if (!empty($_GET['categoria'])): ?>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Produtos de <?php echo ucfirst(htmlspecialchars($_GET['categoria'])); ?>
                </h2>
                <p class="text-gray-500 text-lg text-center">
                    Aqui você pode listar os produtos relacionados a <?php echo htmlspecialchars($_GET['categoria']); ?>.
                </p>
            <?php else: ?>
                <p class="text-gray-500 text-lg text-center">Escolha uma categoria para ver os produtos.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
