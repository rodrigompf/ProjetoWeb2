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
    <title>Produtos de <?php echo htmlspecialchars($categoria); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Estilo para o nome do produto, limitando o número de linhas exibidas */
        .product-name {
            height: 3rem;
            /* Altura fixa para o título */
            line-height: 1.5rem;
            /* Espaçamento entre linhas */
            overflow: hidden;
            /* Oculta texto extra */
            text-overflow: ellipsis;
            /* Adiciona reticências */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Limita a 2 linhas */
            -webkit-box-orient: vertical;
        }

        /* Estilo para a descrição do produto, limitando o número de linhas exibidas */
        .product-description {
            height: 4rem;
            /* Altura fixa para a descrição */
            line-height: 1.25rem;
            /* Espaçamento entre linhas */
            overflow: hidden;
            /* Oculta texto extra */
            text-overflow: ellipsis;
            /* Adiciona reticências */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limita a 3 linhas */
            -webkit-box-orient: vertical;
        }
    </style>

    <script>
        /**
         * Função para adicionar um produto ao carrinho
         */
        async function addToCart(productId) {
            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST'
                });

                // Verifica se a resposta foi bem-sucedida
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

        /**
         * Função para filtrar os produtos com base no texto digitado na barra de pesquisa
         */
        function filterProducts() {
            const query = document.getElementById('search-bar').value.toLowerCase(); // Obtém o valor da pesquisa
            const products = document.querySelectorAll('.product-item'); // Obtém todos os itens de produto

            // Filtra os produtos
            products.forEach(product => {
                const name = product.querySelector('.product-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    product.style.display = 'block'; // Exibe o produto se corresponder à pesquisa
                } else {
                    product.style.display = 'none'; // Oculta o produto se não corresponder
                }
            });
        }
    </script>
</head>

<body class="bg-[rgb(247,246,223)]">
    <?php include './app/views/homepage/header.php'; ?>

    <div class="container mx-auto py-10">
        <!-- Título da página com a categoria -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Produtos da Categoria <?php echo htmlspecialchars($categoria); ?>
        </h1>

        <!-- Barra de pesquisa para filtrar produtos -->
        <div class="mb-6">
            <input
                type="text"
                id="search-bar"
                onkeyup="filterProducts()"
                placeholder="Pesquisar produtos..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Grade de Produtos -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <div class="product-item bg-white rounded-lg shadow-lg overflow-hidden relative">
                    <!-- Verifica se o produto tem desconto e exibe o valor do desconto -->
                    <?php if ($produto['desconto'] == 1 && $produto['discount_price'] > 0): ?>
                        <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            -<?php echo htmlspecialchars($produto['discount_price']); ?>%
                        </div>
                    <?php endif; ?>

                    <!-- Imagem do produto -->
                    <img
                        src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <!-- Nome do produto -->
                        <h3 class="product-name text-lg font-bold">
                            <?php echo htmlspecialchars($produto['nome']); ?>
                        </h3>
                        <div class="mt-2">
                            <!-- Exibe o preço original com o desconto, se houver -->
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
                        <!-- Descrição do produto -->
                        <p class="product-description text-gray-600 mt-2">
                            <?php echo htmlspecialchars($produto['descricao']); ?>
                        </p>
                        <!-- Botão para adicionar o produto ao carrinho -->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                            <button onclick="addToCart(<?php echo $produto['id']; ?>)"
                                class="mt-4 inline-block bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600 transition">
                                Adicionar ao Carrinho
                            </button>
                        <?php else: ?>
                            <button onclick="javascript:alert('Por favor efetue o login para poder fazer compras.')"
                                class="mt-4 inline-block bg-gray-500 text-white rounded-lg py-2 px-4 cursor-not-allowed">
                                Adicionar ao Carrinho
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

<script>
    // Função assíncrona para adicionar um produto ao carrinho
    async function addToCart(productId) {
        try {
            // Envia a requisição para adicionar o produto ao carrinho
            const response = await fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            // Verifica se a resposta foi bem-sucedida
            if (response.ok) {
                const data = await response.json();

                if (data.status === 'success') {
                    // Atualiza a contagem do carrinho
                    updateCartCount(data.cart_count);
                    alert(data.message); // Exibe mensagem de sucesso
                } else {
                    alert('Erro ao adicionar ao carrinho');
                }
            } else {
                alert('Erro ao adicionar ao carrinho');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao adicionar ao carrinho');
        }
    }

    // Função para atualizar o contador do carrinho no cabeçalho
    function updateCartCount(cartCount) {
        const cartCountElement = document.getElementById('cart-count');
        cartCountElement.innerText = cartCount;
    }
</script>


</html>