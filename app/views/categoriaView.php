<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos de <?php echo htmlspecialchars($categoria); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        async function searchProdutos(query) {
            const response = await fetch(`/produtos/search?categoria=<?php echo urlencode($categoria); ?>&query=${query}`);
            const produtos = await response.json();
            const produtosList = document.getElementById('produtos-list');
            produtosList.innerHTML = '';

            if (produtos.length > 0) {
                produtos.forEach(produto => {
                    const li = document.createElement('li');
                    li.className = 'bg-white border rounded-lg shadow p-4';
                    li.innerHTML = `
                        <img src="/assets/${produto.imagem}" alt="${produto.nome}" class="w-full h-48 object-cover rounded-t-lg">
                        <h3 class="text-lg font-bold mt-2">${produto.nome}</h3>
                        <p class="text-gray-600">${produto.descricao}</p>
                        <p class="text-lg text-gray-800 font-bold mt-4">€${produto.preco}</p>
                    `;
                    produtosList.appendChild(li);
                });
            } else {
                produtosList.innerHTML = '<p class="text-gray-500 text-lg">Nenhum produto encontrado.</p>';
            }
        }

        function handleInput(event) {
            searchProdutos(event.target.value);
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Produtos de <?php echo htmlspecialchars($categoria); ?></h1>

        <div class="mb-6">
            <input type="text"
                oninput="handleInput(event)"
                placeholder="Pesquisar produtos..."
                class="w-full border border-gray-300 rounded-lg p-4">
        </div>

        <ul id="produtos-list" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <li class="bg-white border rounded-lg shadow">
                    <img src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p class="text-lg text-gray-800 mt-4 font-bold">€<?php echo number_format($produto['preco'], 2); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>