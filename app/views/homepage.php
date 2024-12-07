<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercado Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Horizontal Scroll Container */
        .offers-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 16px;
        }
        .product {
            min-width: 250px;
            max-width: 250px;
            margin-right: 16px;
        }
        .product img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Cabeçalho -->
    <?php include '../app/views/header.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto p-6 flex-grow">
        <div class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Bem-vindo ao nosso Supermercado!</h2>
            <nav>
                <ul class="flex justify-center gap-6">
                    <li><a href="/produtos" class="text-green-600 underline">Ver Produtos</a></li>
                    <li><a href="/produtos/create" class="text-green-600 underline">Adicionar Novo Produto</a></li>
                </ul>
            </nav>
        </div>

        <!-- Ofertas Imperdíveis Section -->
        <section class="mt-8">
            <h2 class="text-2xl font-semibold text-center text-green-600 mb-4">Ofertas Imperdíveis!</h2>

            <!-- Scroll Container -->
            <div class="offers-container">
                <?php if (!empty($produtosComDesconto)): ?>
                    <?php foreach ($produtosComDesconto as $produto): ?>
                        <div class="product rounded-lg shadow-md overflow-hidden bg-white p-4 transform hover:scale-105 transition-transform">
                            <?php if (isset($produto['imagem']) && !empty($produto['imagem'])): ?>
                                <img src="<?= "../../assets/" . $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>" class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600">No Image</span>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="mt-2 text-lg font-bold"><?= $produto['nome'] ?></h3>
                            <p class="text-gray-500">
                                Preço Original: <span class="line-through"><?= number_format($produto['preco'], 2) ?>€</span>
                            </p>
                            <p class="text-green-600 font-semibold mt-1">
                                Preço com Desconto: <?= number_format($produto['preco_com_desconto'], 2) ?>€
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 mt-4">Nenhuma oferta disponível no momento.</p>
                <?php endif; ?>
            </div>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>
