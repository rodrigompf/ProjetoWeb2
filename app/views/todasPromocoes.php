<?php
session_start();

// Certifique-se de que a variável $produtosComDesconto seja passada pelo controlador
if (!isset($produtosComDesconto)) {
    die('Erro: Produtos não encontrados.'); // Mensagem para caso a variável não esteja definida
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as Promoções</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles */
        .offers-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
        }

        .product {
            width: 250px;
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
    <?php include './app/views/header.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto p-6 flex-grow">
        <h1 class="text-3xl font-semibold text-center text-green-600 mb-6">Todas as Promoções</h1>

        <!-- Ofertas em Grade -->
        <section class="mt-8">
            <div class="offers-container">
                <?php if (!empty($produtosComDesconto)): ?>
                    <?php foreach ($produtosComDesconto as $produto): ?>
                        <div class="product bg-white rounded-lg shadow-lg p-4 transform hover:scale-105 transition-all duration-200">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                     alt="<?= htmlspecialchars($produto['nome']) ?>" 
                                     class="w-full h-48 object-cover rounded-md">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center rounded-md">
                                    <span class="text-gray-600">No Image</span>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($produto['nome']) ?></h3>
                                <p class="text-sm text-gray-500 mt-2">
                                    Preço Original: <span class="line-through text-gray-400"><?= number_format($produto['preco'], 2) ?>€</span>
                                </p>
                                <p class="text-lg text-green-600 font-semibold mt-2">
                                    Preço com Desconto: <?= number_format($produto['preco_com_desconto'], 2) ?>€
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 mt-4 text-center">Nenhuma oferta disponível no momento.</p>
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
