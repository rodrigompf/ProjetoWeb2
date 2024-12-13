<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos de <?php echo htmlspecialchars($categoria); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        async function addToCart(productId) {
            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST'
                });

                if (response.ok) {
                    alert('Produto adicionado ao carrinho');
                } else {
                    alert('Erro ao adicionar ao carrinho');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao adicionar ao carrinho');
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include './app/views/header.php'; ?>

    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Produtos da Categoria <?php echo htmlspecialchars($categoria); ?>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Product Image -->
                    <img
                        src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        class="w-full h-48 object-cover"
                    >

                    <div class="p-4">
                        <!-- Product Name -->
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($produto['nome']); ?></h3>

                        <!-- Product Price -->
                        <div class="mt-2">
                            <?php if ($produto['precoDescontado'] < $produto['preco']): ?>
                                <p class="text-gray-500 line-through">
                                    €<?php echo number_format($produto['preco'], 2); ?>
                                </p>
                                <p class="text-lg font-bold text-gray-900">
                                    €<?php echo number_format($produto['precoDescontado'], 2); ?>
                                </p>
                            <?php else: ?>
                                <p class="text-lg font-bold text-gray-900">
                                    €<?php echo number_format($produto['preco'], 2); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Product Description -->
                        <p class="text-gray-600 mt-2">
                            <?php echo htmlspecialchars($produto['descricao']); ?>
                        </p>

                        <!-- Button to add product to cart -->
                        <button onclick="addToCart(<?php echo $produto['id']; ?>)"
                                class="mt-4 inline-block bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600 transition">
                            Adicionar ao Carrinho
                        </button>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>
