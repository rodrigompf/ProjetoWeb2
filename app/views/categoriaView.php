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
                    li.className = 'bg-white border rounded-lg shadow relative overflow-hidden';
                    li.innerHTML = `
                        <div class="relative">
                            <img src="/assets/${produto.imagem}" alt="${produto.nome}" class="w-full h-48 object-cover">
                            ${
                                produto.discount_price
                                    ? `<div class="absolute top-2 left-2 bg-red-500 text-white text-sm font-bold rounded-full px-3 py-1 shadow-lg">
                                    -${produto.discount_price}%
                                </div>`
                                    : ''
                            }
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold">${produto.nome}</h3>
                            <div class="flex items-center mt-2">
                                ${
                                    produto.precoDescontado < produto.preco
                                        ? `
                                    <p class="text-gray-500 line-through mr-2">€${produto.preco}</p>
                                    <p class="text-lg font-bold text-gray-900">€${produto.precoDescontado}</p>
                                `
                                        : `<p class="text-lg font-bold text-gray-900">€${produto.preco}</p>`
                                }
                            </div>
                            <p class="text-gray-600 mt-2">${produto.descricao}</p>
                        </div>
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
        <div class="relative mb-6">
            <a href="/produtos" class="absolute top-0 right-0 text-gray-700 hover:text-black text-5xl font-bold">
                ←
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Produtos de <?php echo htmlspecialchars($categoria); ?></h1>
        </div>

        <div class="mb-6">
            <input type="text"
                   oninput="handleInput(event)"
                   placeholder="Pesquisar produtos..."
                   class="w-full border border-gray-300 rounded-lg p-4">
        </div>

        <ul id="produtos-list" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($produtosView as $produto): ?>
                <li class="bg-white border rounded-lg shadow relative overflow-hidden">
                    <div class="relative">
                        <img src="/assets/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="w-full h-48 object-cover">
                        <?php if ($produto['precoDescontado'] < $produto['preco']): ?>
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-sm font-bold rounded-full px-3 py-1 shadow-lg">
                                -<?php echo $produto['discount_price']; ?>%
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <div class="flex items-center mt-2">
                            <?php if ($produto['precoDescontado'] < $produto['preco']): ?>
                                <p class="text-gray-500 line-through mr-2">
                                    €<?php echo number_format($produto['preco'], 2); ?>
                                </p>
                                <p class="text-lg font-bold text-gray-900">
                                    €<?php echo number_format($produto['precoDescontado'], 2); ?>
                                </p>
                            <?php else: ?>
                                <p class="text-lg font-bold text-gray-900">
                                    €<?php echo number_format($produto['preco'], 2); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-600 mt-2">
                            <?php echo htmlspecialchars($produto['descricao']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</body>

</html>
