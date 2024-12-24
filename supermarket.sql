-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Dez-2024 às 14:54
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `supermarket`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(6, 'Brinquedos'),
(2, 'Carne'),
(9, 'Congelados'),
(7, 'Doces'),
(5, 'Eletrodomesticos'),
(3, 'Frutas'),
(8, 'Higiene'),
(10, 'Pastelaria'),
(1, 'Peixe'),
(4, 'Sumos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `compras_historico`
--

CREATE TABLE `compras_historico` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cart_data` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `compras_historico`
--

INSERT INTO `compras_historico` (`id`, `user_id`, `cart_data`, `total_price`, `created_at`) VALUES
(20, 1, '{\"17\":{\"name\":\"Boneca\",\"imagem\":\"brinquedos\\/boneca.jpg\",\"quantity\":1,\"original_price\":20,\"price_with_discount\":null,\"discount\":null}}', 0.00, '2024-12-24 13:34:41'),
(21, 1, '{\"21\":{\"name\":\"Caixa de Bombons\",\"imagem\":\"doces\\/bombons.jpg\",\"quantity\":1,\"original_price\":5,\"price_with_discount\":null,\"discount\":\"4.00\"}}', 0.00, '2024-12-24 13:35:25'),
(34, 1, '{\"19\":{\"name\":\"Chocolate de Leite\",\"imagem\":\"doces\\/chocolate_leite.jpg\",\"quantity\":1,\"original_price\":2.5,\"price_with_discount\":null,\"discount\":\"2.00\"}}', 0.00, '2024-12-24 13:50:04'),
(36, 1, '{\"28\":{\"name\":\"Croissant\",\"imagem\":\"pastelaria\\/croissant.jpg\",\"quantity\":1,\"original_price\":1.5,\"price_with_discount\":null,\"discount\":\"1.30\"}}', 0.00, '2024-12-24 13:50:49'),
(41, 1, '{\"19\":{\"name\":\"Chocolate de Leite\",\"imagem\":\"doces\\/chocolate_leite.jpg\",\"quantity\":2,\"original_price\":2.5,\"price_with_discount\":null,\"discount\":\"2.00\"}}', 0.00, '2024-12-24 13:53:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `desconto` tinyint(1) DEFAULT 0,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `categoria_id`, `imagem`, `discount_price`, `desconto`, `stock`) VALUES
(2, 'Banana', 'Banana madura e doce', 1.20, 3, 'frutas/banana.jpg', 50.00, 1, 81),
(3, 'Laranja', 'Laranja rica em vitamina C', 2.00, 3, 'frutas/laranja.jpg', 15.00, 1, 90),
(4, 'Frango', 'Carne de frango fresco', 5.00, 2, 'carne/frango.jpg', 10.00, 1, 97),
(5, 'Bife de Vaca', 'Bife de vaca macio e suculento', 8.50, 2, 'carne/bife_vaca.jpg', 10.00, 0, 50),
(6, 'Pernil de Porco', 'Pernil de porco delicioso', 7.00, 2, 'carne/pernil_porco.jpg', 15.00, 1, 84),
(7, 'Salmão', 'Peixe fresco e saboroso', 12.00, 1, 'peixe/salmao.jpg', 10.00, 1, 44),
(8, 'Bacalhau', 'Bacalhau seco e delicioso', 15.00, 1, 'peixe/bacalhau.jpg', NULL, 0, 89),
(9, 'Atum', 'Atum fresco para fazer sushi', 10.00, 1, 'peixe/atum.jpg', 15.00, 1, 71),
(10, 'Sumo de Laranja', 'Sumo natural de laranja, sem adição de açúcares', 2.50, 4, 'sumos/sumo_laranja.jpg', 2.00, 1, 89),
(11, 'Sumo de Maçã', 'Sumo natural de maçã, delicioso e saudável', 2.70, 4, 'sumos/sumo_maca.jpg', NULL, 0, 114),
(12, 'Sumo de Uva', 'Sumo 100% uva, sem conservantes', 3.00, 4, 'sumos/sumo_uva.jpg', 2.70, 1, 59),
(13, 'Liquidificador', 'Liquidificador de alta potência, ideal para smoothies', 25.00, 5, 'eletrodomesticos/liquidificador.jpg', 20.00, 1, 73),
(14, 'Ferro de Engomar', 'Ferro a vapor portátil', 15.00, 5, 'eletrodomesticos/ferro_engomar.jpg', NULL, 0, 71),
(15, 'Micro-ondas', 'Micro-ondas com grill e função de descongelar', 70.00, 5, 'eletrodomesticos/micro_ondas.jpg', 60.00, 1, 14),
(16, 'Carro de Controlo Remoto', 'Carro elétrico de alta velocidade', 30.00, 6, 'brinquedos/carro_controlo.jpg', 25.00, 1, 99),
(17, 'Boneca', 'Boneca com acessórios incluídos', 20.00, 6, 'brinquedos/boneca.jpg', NULL, 0, 96),
(18, 'Puzzle', 'Puzzle de 1000 peças', 15.00, 6, 'brinquedos/puzzle.jpg', 12.00, 1, 58),
(19, 'Chocolate de Leite', 'Barra de chocolate de leite, 200g', 2.50, 7, 'doces/chocolate_leite.jpg', 2.00, 1, 0),
(20, 'Pacote de Gomas', 'Pacote de gomas sortidas', 1.50, 7, 'doces/gomas.jpg', NULL, 0, 82),
(21, 'Caixa de Bombons', 'Caixa de bombons variados, 150g', 5.00, 7, 'doces/bombons.jpg', 4.00, 1, 40),
(22, 'Pasta de Dentes', 'Pasta de dentes com proteção total', 3.00, 8, 'higiene/pasta_dentes.jpg', 2.50, 1, 78),
(23, 'Champô', 'Champô anticaspa, 500ml', 7.00, 8, 'higiene/champo.jpg', 6.00, 1, 25),
(24, 'Sabonete Líquido', 'Sabonete líquido hidratante, 300ml', 4.00, 8, 'higiene/sabonete_liquido.jpg', NULL, 0, 15),
(25, 'Pizza Congelada', 'Pizza de quatro queijos pronta para assar', 7.00, 9, 'congelados/pizza.jpg', 6.00, 1, 120),
(26, 'Hambúrguer Congelado', 'Pacote com 6 hambúrgueres de vaca', 8.00, 9, 'congelados/hamburguer.jpg', 7.00, 1, 70),
(27, 'Batatas Fritas Congeladas', 'Batatas fritas congeladas prontas para fritar', 3.50, 9, 'congelados/batatas.jpg', NULL, 0, 115),
(28, 'Croissant', 'Croissant folhado, ideal para o pequeno-almoço', 1.50, 10, 'pastelaria/croissant.jpg', 1.30, 1, 0),
(29, 'Pastel de Nata', 'Pastel de nata tradicional', 1.00, 10, 'pastelaria/pastel_nata.jpg', NULL, 0, 21),
(30, 'Bolo de Chocolate', 'Fatia de bolo de chocolate', 2.00, 10, 'pastelaria/bolo_chocolate.jpg', 1.80, 1, 105);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$TEsXaNEWG5ceqxJ9eKkii.gSTl8QQzXCG1I1zcqkCcnjGETipvcsS', 1, '2024-12-05 19:26:29'),
(2, 'alex', 'alex@gmail.com', '$2y$10$iYSIIIkocSHNIqIHPCAbPeYuOL5Da9DlLgACUvyIDUBPF0fyZcXxW', 0, '2024-12-12 11:33:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `compras_historico`
--
ALTER TABLE `compras_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `compras_historico`
--
ALTER TABLE `compras_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `compras_historico`
--
ALTER TABLE `compras_historico`
  ADD CONSTRAINT `compras_historico_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
