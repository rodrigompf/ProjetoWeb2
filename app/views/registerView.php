<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Supermercado Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Cabeçalho -->
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold">Supermercado Online</h1>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto p-6 flex-grow flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Cadastro</h2>

            <form method="POST" action="/register" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700">Usuário</label>
                    <input type="text" id="username" name="username" placeholder="Digite seu nome de usuário"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600" required>
                </div>
                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                    Cadastrar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Já tem uma conta? 
                    <a href="/login" class="text-blue-500 hover:underline">Faça login</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>
