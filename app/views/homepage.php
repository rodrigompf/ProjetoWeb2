<?php
session_start(); // Inicia a sessão para garantir o acesso a variáveis de sessão
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1; // Verifica se o utilizador está autenticado e se é administrador
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercado Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Contêiner de Scroll Horizontal para as ofertas */
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

        /* Estilos para o banner */
        .banner-container {
            position: relative;
            width: 100%;
            max-height: 400px;
            overflow: hidden;
        }

        .banner-images-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 1s ease-in-out;
        }

        .banner-images-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            flex-shrink: 0; /* Impede que as imagens encolham */
        }

        #left-arrow,
        #right-arrow {
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
            padding: 10px;
            border-radius: 50%;
            color: white;
            font-size: 18px;
        }

        #left-arrow:hover,
        #right-arrow:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
    <script>
        window.onload = function () {
            const bannerImagesWrapper = document.getElementById('banner-images-wrapper');
            const banners = bannerImagesWrapper.getElementsByTagName('img'); // Obtém todas as imagens de banner
            let currentIndex = 0;

            // Função para mudar o banner
            function changeBanner(newIndex) {
                const totalBanners = banners.length;

                if (newIndex < 0) newIndex = totalBanners - 1; // Vai para o último banner se passar para trás
                if (newIndex >= totalBanners) newIndex = 0; // Volta ao primeiro banner se passar para frente

                // Define o deslocamento horizontal para mostrar o banner atual
                const offset = -100 * newIndex; // Cada banner ocupa 100% da largura do contêiner
                bannerImagesWrapper.style.transform = `translateX(${offset}%)`;

                // Atualiza o índice do banner atual
                currentIndex = newIndex;
            }

            // Define o intervalo para troca automática de banners (a cada 5 segundos)
            setInterval(() => {
                changeBanner(currentIndex + 1); // Vai para o próximo banner
            }, 5000);

            // Adiciona ouvintes de eventos para as setas de navegação
            document.getElementById('left-arrow').addEventListener('click', () => {
                changeBanner(currentIndex - 1); // Vai para o banner anterior
            });

            document.getElementById('right-arrow').addEventListener('click', () => {
                changeBanner(currentIndex + 1); // Vai para o próximo banner
            });

            // Inicializa o slideshow com o primeiro banner
            changeBanner(currentIndex);
        };
    </script>
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
                    <div class="text-center mt-4">
                        <a href="/produtos" class="bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:bg-green-700 focus:outline-none">
                            Ver Produtos
                        </a>
                    </div>

                    <?php if ($isAdmin): ?>
                        <div class="text-center mt-4">
                            <a href="adminZone" class="bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg transform transition-transform hover:bg-green-700 focus:outline-none">
                                Adicionar Novo Produto
                            </a>
                        </div>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Seção de banners -->
        <section class="mt-8">
            <div class="banner-container relative" id="banner-container">
                <?php if (!empty($banners)): ?>
                    <div class="banner-images-wrapper" id="banner-images-wrapper">
                        <?php foreach ($banners as $banner): ?>
                            <img src="<?= htmlspecialchars($banner) ?>" alt="Imagem do Banner" class="w-full h-full object-cover" loading="lazy">
                        <?php endforeach; ?>
                    </div>

                    <!-- Setas de navegação para alternar entre os banners -->
                    <div class="absolute inset-0 flex justify-between items-center">
                        <button id="left-arrow">‹</button>
                        <button id="right-arrow">›</button>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500">Não há banners disponíveis.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Seção de Ofertas Imperdíveis -->
        <section class="mt-8">
            <h2 class="text-2xl font-semibold text-center text-green-600 mb-4">Ofertas Imperdíveis!</h2>

            <div class="offers-container">
                <?php if (!empty($produtosComDesconto)): ?>
                    <?php foreach ($produtosComDesconto as $produto): ?>
                        <div class="product bg-white rounded-lg shadow-lg p-4 transform hover:scale-105 transition-all duration-200">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="w-full h-48 object-cover rounded-md">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center rounded-md">
                                    <span class="text-gray-600">Sem Imagem</span>
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

</body>

</html>
