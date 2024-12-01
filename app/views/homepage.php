<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercado Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">Supermercado Online</h1>
        </div>
    </header>
    <main class="container mx-auto p-6">
        <div class="text-center">
            <h2 class="text-2xl font-semibold mb-4">Bem-vindo ao nosso Supermercado!</h2>
            <nav>
                <ul class="flex justify-center gap-6">
                    <li><a href="/produtos" class="text-green-600 underline">Ver Produtos</a></li>
                    <li><a href="/produtos/create" class="text-green-600 underline">Adicionar Novo Produto</a></li>
                </ul>
            </nav>
        </div>
    </main>
    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p>© 2024 Supermercado Online. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>