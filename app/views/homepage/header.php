<?php
?>

<header class="bg-green-600 text-white p-4 fixed top-0 left-0 w-full z-50">
    <style>
        /* Fixar o cabeçalho no topo */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 50;
        }

        /* Adicionar espaçamento ao conteúdo abaixo do cabeçalho */
        body {
            padding-top: 80px;
        }

        /* Para garantir que o conteúdo principal não seja coberto */
        .main-content {
            margin-top: 80px;
            /* Espaço necessário para o cabeçalho fixo */
        }
    </style>
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-bold">
            <a href="/">Cesta 24</a>
        </h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <!-- Link para o carrinho de compras, com verificação de login -->
                <a href="<?php echo isset($_SESSION['user']) && $_SESSION['user'] ? '/cart' : 'javascript:alert(\'Por favor efetue o login para poder fazer compras.\')'; ?>" class="relative">
                    <!-- Ícone do carrinho de compras (imagem) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 10a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z" />
                    </svg>
                    <!-- Exibir o número de itens no carrinho, se houver -->
                    <span id="cart-count" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">
                        <?php echo !empty($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    </span>
                </a>

                <a href="/profile" class="relative">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a5 5 0 015 5v1h3a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V10a2 2 0 012-2h3V7a5 5 0 015-5z" />
    </svg>
</a>
                <!-- Verificação se o utilizador está logado -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                    <!-- Botão de Logout -->
                    <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
                <?php else: ?>
                    <!-- Botão de Login, caso o utilizador não esteja logado -->
                    <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</header>
