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

        async function searchProducts() {
            const searchQuery = document.getElementById('search-bar').value;
            const category = '<?php echo addslashes($categoria); ?>';

            try {
                const response = await fetch(`/produtos/search?categoria=${category}&query=${searchQuery}`);

                if (!response.ok) {
                    // Handle non-OK responses gracefully
                    console.error('Erro ao buscar produtos:', response.statusText);
                    return; // Exit without showing an alert
                }

                const products = await response.json();
                const productsGrid = document.getElementById('products-grid');
                productsGrid.innerHTML = '';

                products.forEach(product => {
                    const isDiscounted = product.desconto === 1;
                    const discountBadge = isDiscounted && product.discount_price > 0 ?
                        `<div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        -${product.discount_price}%
                   </div>` :
                        '';

                    const productHTML = `
                <div class="bg-white rounded-lg shadow-lg overflow-hidden relative">
                    ${discountBadge}
                    <img
                        src="/assets/${product.imagem}"
                        alt="${product.nome}"
                        class="w-full h-48 object-cover"
                    >
                    <div class="p-4">
                        <h3 class="text-lg font-bold">${product.nome}</h3>
                        <div class="mt-2">
                            ${product.precoDescontado < product.preco
                                ? `<p class="text-gray-500 line-through">€${product.preco.toFixed(2)}</p>
                                   <p class="text-lg font-bold text-gray-900">€${product.precoDescontado.toFixed(2)}</p>`
                                : `<p class="text-lg font-bold text-gray-900">€${product.preco.toFixed(2)}</p>`
                            }
                        </div>
                        <p class="text-gray-600 mt-2">${product.descricao}</p>
                        <button onclick="addToCart(${product.id})"
                                class="mt-4 inline-block bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600 transition">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            `;
                    productsGrid.innerHTML += productHTML;
                });
            } catch (error) {
                // Log errors instead of showing alerts
                console.error('Erro ao buscar produtos:', error);
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

        <!-- Search Bar -->
        <div class="mb-6">
            <input
                id="search-bar"
                type="text"
                placeholder="Procurar produtos..."
                class="w-full p-3 border rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                onkeyup="searchProducts()">
        </div>


        <!-- Products Grid -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden relative">
                    <?php if ($produto['desconto'] == 1 && $produto['discount_price'] > 0): ?>
                        <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            -<?php echo htmlspecialchars($produto['discount_price']); ?>%
                        </div>
                    <?php endif; ?>

                    <img
                        src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($produto['nome']); ?></h3>
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
                        <p class="text-gray-600 mt-2">
                            <?php echo htmlspecialchars($produto['descricao']); ?>
                        </p>
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