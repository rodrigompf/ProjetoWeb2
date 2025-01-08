-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Jan-2025 às 23:01
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
-- Estrutura da tabela `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `banners`
--

INSERT INTO `banners` (`id`, `name`, `image_url`) VALUES
(1, 'Green', 'https://passageirodeprimeira.b-cdn.net/wp-content/uploads/2023/04/ganhe-30-de-desconto-ao-fazer-o-seu-mercado-no-supermercado-justo-whatsapp-image-2023-04-17-at-18.39.03.jpeg'),
(2, 'Campanha', 'https://redeuniaosupermercados.com.br/wp-content/uploads/2019/12/banner-campanha-1.jpg'),
(3, 'Copão', 'https://www.designi.com.br/images/preview/12456595-m.jpg'),
(4, 'sdsd', 'https://www.designi.com.br/images/preview/13123515-m.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `image_url` varchar(2083) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `image_url`) VALUES
(1, 'Peixe', 'https://media.istockphoto.com/id/140005241/pt/foto/frutos-do-mar-no-gelo.jpg?s=612x612&w=0&k=20&c=VybcDtpqk8TnXOWUoYZWQWyKXP_1oPzcNfqhb3uPccE='),
(2, 'Carne', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNwkZPU4MogUqEhYZ70L18InYIdj1nqpjPDA&s'),
(3, 'Frutas', 'https://media.istockphoto.com/id/610771802/es/foto/bayas-de-frutas-del-bosque-por-encima-de-la-mezcla-variada-en-el-estudio.jpg?s=612x612&w=0&k=20&c=Z2LvkeSuPkEHagpAp-89CZfJ0Q3IDP91pazN0NXFB70='),
(4, 'Sumos', 'https://grandeconsumo.com/wp-content/uploads/2020/08/shutterstock_192709250-758x487.jpg'),
(5, 'Eletrodomesticos', 'https://pracasatoda.com/wp-content/uploads/2023/08/Lista-de-eletrodomesticos-para-cozinha-2.webp'),
(6, 'Brinquedos', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkhHTnJp_l4iZ-obnKPMq3tH2YgFzkEgbobg&s'),
(7, 'Doces', 'https://www.solinca.pt/wp-content/uploads/2022/02/Compulsao-por-doces.png.webp'),
(8, 'Higiene', 'https://difem.cl/wp-content/uploads/2022/10/difem-articulo-02-higiene.jpg'),
(9, 'Congelados', 'https://content.paodeacucar.com/wp-content/uploads/2017/08/alimentos-congelados-capa.jpg'),
(10, 'Pastelaria', 'https://www.pastelaria-central.pt/img/neaty-01.jpg');

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
(36, 1, '{\"28\":{\"name\":\"Croissant\",\"imagem\":\"pastelaria\\/croissant.jpg\",\"quantity\":1,\"original_price\":1.5,\"price_with_discount\":null,\"discount\":\"1.30\"}}', 0.00, '2024-12-24 13:50:49'),
(41, 1, '{\"19\":{\"name\":\"Chocolate de Leite\",\"imagem\":\"doces\\/chocolate_leite.jpg\",\"quantity\":2,\"original_price\":2.5,\"price_with_discount\":null,\"discount\":\"2.00\"}}', 0.00, '2024-12-24 13:53:47'),
(45, 1, '{\"29\":{\"name\":\"Pastel de Nata\",\"imagem\":\"https:\\/\\/www.pingodoce.pt\\/wp-content\\/uploads\\/2015\\/10\\/pastel-de-nata.jpg\",\"quantity\":1,\"original_price\":1,\"price_with_discount\":1,\"discount\":null},\"30\":{\"name\":\"Bolo de Chocolate\",\"imagem\":\"https:\\/\\/moinhoglobo.com.br\\/wp-content\\/uploads\\/2019\\/03\\/08-bolo-chocolate-1024x683.png\",\"quantity\":2,\"original_price\":2,\"price_with_discount\":1.96,\"discount\":\"1.80\"}}', 4.92, '2024-12-27 01:39:03'),
(46, 1, '{\"15\":{\"name\":\"Micro-ondas\",\"imagem\":\"https:\\/\\/res.cloudinary.com\\/sharp-consumer-eu\\/image\\/fetch\\/w_1100,f_auto,q_auto\\/https:\\/\\/s3.infra.brandquad.io\\/accounts-media\\/SHRP\\/DAM\\/origin\\/76743e70-e21b-11ee-9368-e2f64a9402cb.jpg\",\"quantity\":1,\"original_price\":70,\"price_with_discount\":28,\"discount\":\"60.00\"},\"13\":{\"name\":\"Liquidificador\",\"imagem\":\"https:\\/\\/www.continente.pt\\/dw\\/image\\/v2\\/BDVS_PRD\\/on\\/demandware.static\\/-\\/Sites-col-master-catalog\\/default\\/dwa21252ce\\/images\\/col\\/774\\/7749904-frente.jpg?sw=2000&sh=2000\",\"quantity\":1,\"original_price\":25,\"price_with_discount\":20,\"discount\":\"20.00\"}}', 48.00, '2024-12-27 01:42:08'),
(47, 1, '{\"13\":{\"name\":\"Liquidificador\",\"imagem\":\"https:\\/\\/www.continente.pt\\/dw\\/image\\/v2\\/BDVS_PRD\\/on\\/demandware.static\\/-\\/Sites-col-master-catalog\\/default\\/dwa21252ce\\/images\\/col\\/774\\/7749904-frente.jpg?sw=2000&sh=2000\",\"quantity\":1,\"original_price\":25,\"price_with_discount\":20,\"discount\":\"20.00\"},\"18\":{\"name\":\"Puzzle\",\"imagem\":\"https:\\/\\/puzzlemania-154aa.kxcdn.com\\/products\\/2024\\/puzzle-clementoni-1000-pieces-impossible-disney-classic.webp\",\"quantity\":1,\"original_price\":15,\"price_with_discount\":13.2,\"discount\":\"12.00\"},\"7\":{\"name\":\"Salm\\u00e3o\",\"imagem\":\"https:\\/\\/images.tcdn.com.br\\/img\\/img_prod\\/842178\\/salmao_fresco_fatiado_tipo_sashimi_77_1_20200903170409.jpg\",\"quantity\":3,\"original_price\":12,\"price_with_discount\":10.8,\"discount\":\"10.00\"}}', 65.60, '2024-12-27 19:56:59'),
(48, 1, '{\"6\":{\"name\":\"Pernil de Porco\",\"imagem\":\"https:\\/\\/static.itdg.com.br\\/images\\/640-440\\/df07a61782665ce2dfe51a648253a165\\/shutterstock-2229938609.jpg\",\"quantity\":1,\"original_price\":7,\"price_with_discount\":5.95,\"discount\":\"15.00\"}}', 5.95, '2024-12-28 13:01:49');

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
(2, 'Banana', 'Banana madura e doce', 1.20, 3, 'https://nutritionsource.hsph.harvard.edu/wp-content/uploads/2018/08/bananas-1354785_1920.jpg', 50.00, 1, 81),
(3, 'Laranja', 'Laranja rica em vitamina C', 2.00, 3, 'https://image.tuasaude.com/media/article/uk/dg/beneficios-da-laranja_53623_l.jpg', 15.00, 1, 90),
(4, 'Frango', 'Carne de frango fresco', 5.00, 2, 'https://naminhapanela.com/wp-content/uploads/2023/12/Como-fazer-frango-assado-720x720.jpg', 10.00, 1, 97),
(5, 'Bife de Vaca', 'Bife de vaca macio e suculento', 8.50, 2, 'https://ncultura.pt/wp-content/uploads/2023/12/Receita-de-bife-de-vaca-na-frigideira-a-Portuguesa-recipe.jpg', 10.00, 0, 50),
(6, 'Pernil de Porco', 'Pernil de porco delicioso', 7.00, 2, 'https://static.itdg.com.br/images/640-440/df07a61782665ce2dfe51a648253a165/shutterstock-2229938609.jpg', 15.00, 1, 83),
(7, 'Salmão', 'Peixe fresco e saboroso', 12.00, 1, 'https://images.tcdn.com.br/img/img_prod/842178/salmao_fresco_fatiado_tipo_sashimi_77_1_20200903170409.jpg', 10.00, 1, 41),
(8, 'Bacalhau', 'Bacalhau seco e delicioso', 15.00, 1, 'https://apraca.pt/cdn/shop/articles/bacalhau.jpg?v=1606988564', NULL, 0, 89),
(9, 'Atum', 'Atum fresco para fazer sushi', 10.00, 1, 'https://feed.continente.pt/media/l5zdbcfa/atum.jpeg?anchor=center&mode=crop&width=1600&height=900&rnd=132920758492670000&format=webp', 15.00, 1, 71),
(10, 'Sumo de Laranja', 'Sumo natural de laranja, sem adição de açúcares', 2.50, 4, 'https://www.biocabaz.pt/web/wp-conteudos/uploads/2021/08/sumo-de-laranja-bio-hollinger-1l-1000x1000.jpg', 2.00, 1, 89),
(11, 'Sumo de Maçã', 'Sumo natural de maçã, delicioso e saudável', 2.70, 4, 'https://iswari.s3.eu-west-3.amazonaws.com/products/ohzqme-297523-sumo-maca-bio-1-litros-ltr-hollinger-removebg-preview.png', NULL, 0, 114),
(12, 'Sumo de Uva', 'Sumo 100% uva, sem conservantes', 3.00, 4, 'https://iswari.s3.eu-west-3.amazonaws.com/products/xk7fuq-297530-sumo-uva-preta-bio-1-litros-ltr-hollinger-removebg-preview.png', 2.70, 1, 59),
(13, 'Liquidificador', 'Liquidificador de alta potência, ideal para smoothies', 25.00, 5, 'https://www.continente.pt/dw/image/v2/BDVS_PRD/on/demandware.static/-/Sites-col-master-catalog/default/dwa21252ce/images/col/774/7749904-frente.jpg?sw=2000&sh=2000', 20.00, 1, 71),
(14, 'Ferro de Engomar', 'Ferro a vapor portátil', 15.00, 5, 'https://www.continente.pt/dw/image/v2/BDVS_PRD/on/demandware.static/-/Sites-col-master-catalog/default/dw0e7a5153/images/col/643/6430018-esquerdo.jpg?sw=2000&sh=2000', NULL, 0, 71),
(15, 'Micro-ondas', 'Micro-ondas com grill e função de descongelar', 70.00, 5, 'https://res.cloudinary.com/sharp-consumer-eu/image/fetch/w_1100,f_auto,q_auto/https://s3.infra.brandquad.io/accounts-media/SHRP/DAM/origin/76743e70-e21b-11ee-9368-e2f64a9402cb.jpg', 60.00, 1, 13),
(16, 'Carro de Controlo Remoto', 'Carro elétrico de alta velocidade', 30.00, 6, 'https://www.worten.pt/i/1a9dab878d92010b39624faf915635af4ac56758', 25.00, 1, 99),
(17, 'Boneca', 'Boneca com acessórios incluídos', 20.00, 6, 'https://www.eurekakids.es/cdnassets/36945001-1_l.jpg', NULL, 0, 96),
(18, 'Puzzle', 'Puzzle de 1000 peças', 15.00, 6, 'https://puzzlemania-154aa.kxcdn.com/products/2024/puzzle-clementoni-1000-pieces-impossible-disney-classic.webp', 12.00, 1, 57),
(19, 'Chocolate de Leite', 'Barra de chocolate de leite, 200g', 2.50, 7, 'https://swissfood.store/wp-content/uploads/2024/03/Cailler-Milk-Chocolate-Bar-with-Almonds-200g-2.webp', 2.00, 1, 0),
(20, 'Pacote de Gomas', 'Pacote de gomas sortidas', 1.50, 7, 'https://www.continente.pt/dw/image/v2/BDVS_PRD/on/demandware.static/-/Sites-col-master-catalog/default/dw346a9016/images/col/753/7536685-frente.jpg?sw=2000&sh=2000', NULL, 0, 82),
(21, 'Caixa de Bombons', 'Caixa de bombons variados, 150g', 5.00, 7, 'https://www.auchan.pt/dw/image/v2/BFRC_PRD/on/demandware.static/-/Sites-auchan-pt-master-catalog/default/dwadd142b1/images/hi-res/000401284.jpg', 4.00, 1, 40),
(22, 'Pasta de Dentes', 'Pasta de dentes com proteção total', 3.00, 8, 'https://prd360hyper-17e49.kxcdn.com/images/thumbs/6879799_pasta-dentifrica-fluor-sensodyne-75ml_550.png', 2.50, 1, 78),
(23, 'Champô', 'Champô anticaspa, 500ml', 7.00, 8, 'https://www.shampoo.pt/media/catalog/product/cache/b4c7842441b629c7f30a99b5c3836464/l/o/loreal-scalp-advanced-shampoo-anticaspa-500ml_1.jpg', 6.00, 1, 25),
(24, 'Sabonete Líquido', 'Sabonete líquido hidratante, 300ml', 4.00, 8, 'https://sgfm.elcorteingles.es/SGFM/dctm/MEDIA03/202308/09/05255754701021____5__600x600.jpg', NULL, 0, 15),
(25, 'Pizza Congelada', 'Pizza de quatro queijos pronta para assar', 7.00, 9, 'https://mercantilnovaera.vtexassets.com/arquivos/ids/192303/Pizza-Quatro-Queijos-SEARA-Caixa-450g.jpg?v=637743980485670000', 6.00, 1, 120),
(26, 'Hambúrguer Congelado', 'Pacote com 6 hambúrgueres de vaca', 8.00, 9, 'https://images.tcdn.com.br/img/img_prod/854660/hamburguer_bovino_240gr_2x120gr_congelado_soubeef_401_1_5fb17aaae2886e9acd9b3fa40b56c782.jpg', 7.00, 1, 70),
(27, 'Batatas Fritas Congeladas', 'Batatas fritas congeladas prontas para fritar', 3.50, 9, 'https://cdn.awsli.com.br/1977/1977050/arquivos/tolochinsky-cannery-frozen-french-fry-1200.jpg', NULL, 0, 115),
(28, 'Croissant', 'Croissant folhado, ideal para o pequeno-almoço', 1.50, 10, 'https://www.continente.pt/dw/image/v2/BDVS_PRD/on/demandware.static/-/Sites-col-master-catalog/default/dw043bc2a2/images/col/775/7759757-frente.jpg?sw=2000&sh=2000', 1.30, 1, 0),
(29, 'Pastel de Nata', 'Pastel de nata tradicional', 1.00, 10, 'https://www.pingodoce.pt/wp-content/uploads/2015/10/pastel-de-nata.jpg', NULL, 0, 20),
(30, 'Bolo de Chocolate', 'Fatia de bolo de chocolate', 2.00, 10, 'https://moinhoglobo.com.br/wp-content/uploads/2019/03/08-bolo-chocolate-1024x683.png', 1.80, 1, 103);

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
-- Índices para tabela `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `compras_historico`
--
ALTER TABLE `compras_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
