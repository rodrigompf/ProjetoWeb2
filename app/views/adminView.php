<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto View</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <a href="javascript:history.back()" 
       class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
        Back
    </a>

    <div class="space-x-4">
        <a href="/produtos/create" 
           class="px-4 py-2 bg-blue-500 text-white font-semibold rounded shadow hover:bg-blue-600 transition">
            Adicionar Produto
        </a>

        <a href="/produtos/edit" 
           class="px-4 py-2 bg-green-500 text-white font-semibold rounded shadow hover:bg-green-600 transition">
            Editar Produto
        </a>

        <a href="/categorias" 
           class="px-4 py-2 bg-purple-500 text-white font-semibold rounded shadow hover:bg-purple-600 transition">
            Adicionar/eliminar Categorias
        </a>

        <a href="/stock-management" 
        class="px-4 py-2 bg-orange-500 text-white font-semibold rounded shadow hover:bg-orange-600 transition">
            Stock Management
        </a>
    </div>

</body>
</html>
