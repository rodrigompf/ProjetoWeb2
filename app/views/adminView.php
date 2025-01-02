<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visão do Produto</title>
    <!-- Inclui o Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Botão para voltar à página anterior -->
    <a href="javascript:history.back()"
        class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Voltar
    </a>

    <!-- Botões de navegação para várias ações -->
    <div class="space-x-4">
        <!-- Botão para adicionar um novo produto -->
        <a href="/produtos/create"
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded shadow hover:bg-blue-600 transition">
            Adicionar Produto
        </a>

        <!-- Botão para editar um produto existente -->
        <a href="/produtos/edit"
            class="px-4 py-2 bg-green-500 text-white font-semibold rounded shadow hover:bg-green-600 transition">
            Editar Produto
        </a>

        <!-- Botão para adicionar ou eliminar categorias -->
        <a href="/categorias"
            class="px-4 py-2 bg-purple-500 text-white font-semibold rounded shadow hover:bg-purple-600 transition">
            Adicionar/eliminar Categorias
        </a>

        <!-- Botão para gestão de stock -->
        <a href="/stock-management"
            class="px-4 py-2 bg-orange-500 text-white font-semibold rounded shadow hover:bg-orange-600 transition">
            Gestão de Stock
        </a>
         <!-- Botão para gestão Banners -->
         <a href="/banners"
                class="px-4 py-2 bg-red-500 text-white font-semibold rounded shadow hover:bg-red-600 transition"
                aria-label="Gestão de Banners">
                Gestão de Banners
            </a>
    </div>

</body>

</html>