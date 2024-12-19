<?php
?>

<header class="bg-green-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-bold">
            <a href="/">Supermercado Online</a>
        </h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <a href="<?php echo isset($_SESSION['user']) && $_SESSION['user'] ? '/cart' : 'javascript:alert(\'Porfavor efetue o login para poder fazer compras.\')'; ?>" class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 10a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z" />
                    </svg>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                    <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
                <?php else: ?>
                    <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
