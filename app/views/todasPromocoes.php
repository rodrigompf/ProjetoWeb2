<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão
}

// Verifica se a variável $produtosComDesconto está definida
if (!isset($produtosComDesconto)) {
    die('Erro: Produtos não encontrados.'); // Mensagem caso a variável não esteja definida
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
        /* Estilos personalizados para a página */
        .offers-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
        }

        .product {
            width: 250px;
            margin-right: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .product img {
            height: 200px;
            object-fit: cover;
        }

        .product-name {
            min-height: 3rem;
            /* Garante espaço para até duas linhas no nome do produto */
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
        }
    </style>
    <script>
        // Função assíncrona para adicionar um produto ao carrinho
        async function addToCart(productId) {
            try {
                // Envia a requisição para adicionar o produto ao carrinho
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
    </script>
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
                            <!-- Verifica se o produto tem imagem e exibe-a -->
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                    alt="<?= htmlspecialchars($produto['nome']) ?>"
                                    class="w-full h-48 object-cover rounded-md">
                            <?php else: ?>
                                <!-- Caso não tenha imagem, exibe um fundo cinza com a mensagem 'Sem Imagem' -->
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center rounded-md">
                                    <span class="text-gray-600">Sem Imagem</span>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <!-- Nome do produto -->
                                <h3 class="product-name text-xl font-semibold text-gray-800"><?= htmlspecialchars($produto['nome']) ?></h3>

                                <!-- Preço original com riscado -->
                                <p class="text-sm text-gray-500 mt-2">
                                    Preço Original: <span class="line-through text-gray-400"><?= number_format($produto['preco'], 2) ?>€</span>
                                </p>

                                <!-- Preço com desconto -->
                                <p class="text-lg text-green-600 font-semibold mt-2">
                                    Preço com Desconto: <?= number_format($produto['preco_com_desconto'], 2) ?>€
                                </p>

                                <!-- Botão para adicionar o produto ao carrinho -->
                                <button onclick="addToCart(<?= $produto['id'] ?>)"
                                    class="mt-4 inline-block bg-blue-500 text-white rounded-lg py-2 px-4 hover:bg-blue-600 transition">
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Mensagem caso não haja produtos com desconto disponíveis -->
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