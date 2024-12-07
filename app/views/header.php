<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="bg-green-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-bold">
            <a href="/">Supermercado Online</a>
        </h1>
        <div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
            <?php else: ?>
                <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>