<?php
session_start(); // Certifique-se de que a sessão esteja iniciada
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1; // Verifica se o usuário está logado e é administrador
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

        /* Estilo para o Banner */
        .banner-container {
            position: relative;
            width: 100%;
            max-height: 400px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .banner-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 1s ease-in-out;
        }

        .banner {
            display: flex;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Cabeçalho -->
    <?php include './app/views/header.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto p-6 flex-grow">
        <div class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Bem-vindo ao nosso Supermercado!</h2>
            <nav>
                <ul class="flex justify-center gap-6">
                    <!-- Botão de Ver Produtos -->
                    <div class="text-center mt-4">
                        <!-- Ver Produtos Button -->
                        <a href="/produtos" class="bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:bg-green-700 focus:outline-none">
                            Ver Produtos
                        </a>
                    </div>

                    <?php if ($isAdmin): ?>
                        <!-- Adicionar Novo Produto Button -->
                        <div class="text-center mt-4">
                            <a href="adminZone" class="bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:bg-green-700 focus:outline-none">
                                Adicionar Novo Produto
                            </a>
                        </div>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>

        <!-- Banners rotativos de alimentos -->
        <section class="mt-8">
            <div class="banner-container" id="banner-container">
                <div class="banner" id="banner">
                    <!-- As imagens serão inseridas dinamicamente aqui -->
                </div>
            </div>
        </section>

        <section class="mt-8">
            <h2 class="text-2xl font-semibold text-center text-green-600 mb-4">Ofertas Imperdíveis!</h2>

            <!-- Scroll Container -->
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
                <!-- Botão para ver todas as promoções -->
                <div class="text-center mt-4">
                    <a href="/todasPromocoes" class="bg-red-500 text-white py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:bg-red-700 focus:outline-none">
                        Ver todas as promoções
                    </a>
                </div>
            </section>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Chave de API do Unsplash
        const UNSPLASH_API_KEY = 'API_KEY';
        const bannerContainer = document.getElementById('banner');

        // Função para buscar imagens de comida
        async function fetchFoodImages() {
            try {
                const response = await fetch(`https://api.unsplash.com/photos/random?query=food&count=4&client_id=${UNSPLASH_API_KEY}`);
                const images = await response.json();

                // Adiciona as imagens no banner
                images.forEach(image => {
                    const img = document.createElement('img');
                    img.src = image.urls.regular;
                    img.alt = "Imagem de comida";
                    bannerContainer.appendChild(img);
                });

                // Inicia a rotação das imagens após o carregamento
                changeBanner();
            } catch (error) {
                console.error('Erro ao buscar as imagens:', error);
            }
        }

        // Função para mudar as imagens do banner
        let currentIndex = 0;
        let bannerImages = []; // Para armazenar as imagens carregadas

        function changeBanner() {
            // Carrega todas as imagens para a rotação
            bannerImages = document.querySelectorAll('#banner img');
            const totalImages = bannerImages.length;

            if (totalImages > 0) {
                bannerImages[currentIndex].style.opacity = 0; // Esconde a imagem atual
                currentIndex = (currentIndex + 1) % totalImages;
                bannerImages[currentIndex].style.opacity = 1; // Mostra a nova imagem
            }
        }

        // Muda a cada 10 segundos
        setInterval(changeBanner, 10000);

        fetchFoodImages(); // Chama a função para carregar as imagens de comida
    </script>
</body>

</html>