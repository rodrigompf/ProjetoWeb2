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

        <section class="mt-8">
            <h2 class="text-2xl font-semibold text-center text-green-600 mb-4">Ofertas Imperdíveis!</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <?php if (!empty($produtosComDesconto)): ?>
                    <div class="offers-banner">
                        <h2>Ofertas Imperdíveis!</h2>
                        <div class="products">
                            <?php foreach ($produtosComDesconto as $produto): ?>
                                <div class="product">
                                    <img src="<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>">
                                    <h3><?= $produto['nome'] ?></h3>
                                    <p>Preço Original: <?= number_format($produto['preco'], 2) ?>€</p>
                                    <p>Preço com Desconto: <?= number_format($produto['preco_com_desconto'], 2) ?>€</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Nenhuma oferta disponível no momento.</p>
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