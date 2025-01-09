<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão se ela ainda não estiver ativa
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supermercado Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[rgb(247,246,223)] flex flex-col min-h-screen">
    <!-- Cabeçalho da página -->
    <?php include './app/views/homepage/header.php'; ?>

    <!-- Conteúdo principal da página -->
    <main class="container mx-auto p-6 flex-grow flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h2>

            <!-- Exibição de mensagens de erro, caso existam -->
            <?php if (isset($error)): ?>
                <div class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Formulário de login -->
            <form method="POST" action="/login" class="space-y-4">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

                <!-- Campo para o email do utilizador -->
                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>

                <!-- Campo para a senha do utilizador -->
                <div>
                    <label for="password" class="block text-gray-700">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>

                <!-- Botão para enviar o formulário de login -->
                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                    Entrar
                </button>
            </form>

            <!-- Link para registo de novos utilizadores -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">Não tem uma conta?
                    <a href="/register" class="text-blue-500 hover:underline">Registe-se aqui</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Rodapé da página -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>