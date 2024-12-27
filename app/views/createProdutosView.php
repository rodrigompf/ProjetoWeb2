<?php
// Assume-se que já há uma classe Connection configurada que gerencia a conexão com o banco
require_once './app/database/Connection.php';

// Criação de um objeto de conexão com o banco de dados
$conn = Connection::getInstance();

// Consulta para obter as categorias
$sql = "SELECT * FROM categorias"; // Ajuste o nome da tabela conforme seu banco
$stmt = $conn->prepare($sql);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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

        <a href="javascript:history.back()"
            class="absolute top-8 left-8 px-12 py-4 bg-gray-500 text-white font-semibold rounded shadow hover:bg-gray-600 transition">
            Back
        </a>

        <!-- Exibir Mensagens de Erro ou Sucesso -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para Adicionar Produto -->
        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded shadow-md">
            <!-- Nome do Produto -->
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-semibold mb-2">Nome</label>
                <input type="text" name="nome" id="nome" required class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Descrição -->
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 font-semibold mb-2">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" class="w-full px-4 py-2 rounded border"></textarea>
            </div>

            <!-- Preço -->
            <div class="mb-4">
                <label for="preco" class="block text-gray-700 font-semibold mb-2">Preço</label>
                <input type="number" name="preco" id="preco" step="0.01" required class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Preço com Desconto -->
            <div class="mb-4">
                <label for="discount_price" class="block text-gray-700 font-semibold mb-2">Preço com Desconto</label>
                <input type="number" name="discount_price" id="discount_price" step="0.01" class="w-full px-4 py-2 rounded border">
            </div>

            <!-- Categoria -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-gray-700 font-semibold mb-2">Categoria</label>
                <select name="categoria_id" id="categoria_id" required class="w-full px-4 py-2 rounded border">
                    <!-- Exibindo as categorias do banco de dados -->
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Desconto -->
            <div class="mb-4">
                <label for="desconto" class="block text-gray-700 font-semibold mb-2">Desconto</label>
                <select name="desconto" id="desconto" class="w-full px-4 py-2 rounded border">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>

            <!-- Imagem - Novo Campo para URL -->
            <div class="mb-4">
                <label for="imagem_url" class="block text-gray-700 font-semibold mb-2">URL da Imagem</label>
                <input type="url" name="imagem_url" id="imagem_url" class="w-full px-4 py-2 rounded border" placeholder="Cole o URL da imagem aqui">
            </div>

            <!-- Botão de Submissão -->
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Salvar Produto
            </button>
        </form>
    </div>
</body>

</html>
