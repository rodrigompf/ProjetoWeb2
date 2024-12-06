<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Adicionar Novo Produto</h1>

        <form action="/produtos/store" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded shadow-md">
            <!-- Nome do Produto -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-semibold mb-2">Nome do Produto:</label>
                <input type="text" name="nome" id="nome" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Categoria -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-semibold mb-2">Categoria:</label>
                <select name="categoria_id" id="categoria_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="1">Peixe</option>
                    <option value="2">Carne</option>
                    <option value="3">Frutas</option>
                </select>
            </div>

            <!-- Imagem -->
            <div class="mb-4">
                <label for="imagem" class="block text-gray-700 font-semibold mb-2">Imagem do Produto:</label>
                <input type="file" name="imagem" id="imagem"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Botão de Submissão -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    Salvar Produto
                </button>
            </div>
        </form>
    </div>
</body>

</html>