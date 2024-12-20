-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 07:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supermarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
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
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `desconto` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `categoria_id`, `imagem`, `discount_price`, `desconto`) VALUES
(2, 'Banana', 'Banana madura e doce', 1.20, 3, 'frutas/banana.jpg', 50.00, 1),
(3, 'Laranja', 'Laranja rica em vitamina C', 2.00, 3, 'frutas/laranja.jpg', 15.00, 1),
(4, 'Frango', 'Carne de frango fresco', 5.00, 2, 'carne/frango.jpg', 10.00, 1),
(5, 'Bife de Vaca', 'Bife de vaca macio e suculento', 8.50, 2, 'carne/bife_vaca.jpg', 10.00, 0),
(6, 'Pernil de Porco', 'Pernil de porco delicioso', 7.00, 2, 'carne/pernil_porco.jpg', 15.00, 1),
(7, 'Salmão', 'Peixe fresco e saboroso', 12.00, 1, 'peixe/salmao.jpg', 10.00, 1),
(8, 'Bacalhau', 'Bacalhau seco e delicioso', 15.00, 1, 'peixe/bacalhau.jpg', NULL, 0),
(9, 'Atum', 'Atum fresco para fazer sushi', 10.00, 1, 'peixe/atum.jpg', 15.00, 1),
(10, 'Sumo de Laranja', 'Sumo natural de laranja, sem adição de açúcares', 2.50, 4, 'sumos/sumo_laranja.jpg', 2.00, 1),
(11, 'Sumo de Maçã', 'Sumo natural de maçã, delicioso e saudável', 2.70, 4, 'sumos/sumo_maca.jpg', NULL, 0),
(12, 'Sumo de Uva', 'Sumo 100% uva, sem conservantes', 3.00, 4, 'sumos/sumo_uva.jpg', 2.70, 1),
(13, 'Liquidificador', 'Liquidificador de alta potência, ideal para smoothies', 25.00, 5, 'eletrodomesticos/liquidificador.jpg', 20.00, 1),
(14, 'Ferro de Engomar', 'Ferro a vapor portátil', 15.00, 5, 'eletrodomesticos/ferro_engomar.jpg', NULL, 0),
(15, 'Micro-ondas', 'Micro-ondas com grill e função de descongelar', 70.00, 5, 'eletrodomesticos/micro_ondas.jpg', 60.00, 1),
(16, 'Carro de Controlo Remoto', 'Carro elétrico de alta velocidade', 30.00, 6, 'brinquedos/carro_controlo.jpg', 25.00, 1),
(17, 'Boneca', 'Boneca com acessórios incluídos', 20.00, 6, 'brinquedos/boneca.jpg', NULL, 0),
(18, 'Puzzle', 'Puzzle de 1000 peças', 15.00, 6, 'brinquedos/puzzle.jpg', 12.00, 1),
(19, 'Chocolate de Leite', 'Barra de chocolate de leite, 200g', 2.50, 7, 'doces/chocolate_leite.jpg', 2.00, 1),
(20, 'Pacote de Gomas', 'Pacote de gomas sortidas', 1.50, 7, 'doces/gomas.jpg', NULL, 0),
(21, 'Caixa de Bombons', 'Caixa de bombons variados, 150g', 5.00, 7, 'doces/bombons.jpg', 4.00, 1),
(22, 'Pasta de Dentes', 'Pasta de dentes com proteção total', 3.00, 8, 'higiene/pasta_dentes.jpg', 2.50, 1),
(23, 'Champô', 'Champô anticaspa, 500ml', 7.00, 8, 'higiene/champo.jpg', 6.00, 1),
(24, 'Sabonete Líquido', 'Sabonete líquido hidratante, 300ml', 4.00, 8, 'higiene/sabonete_liquido.jpg', NULL, 0),
(25, 'Pizza Congelada', 'Pizza de quatro queijos pronta para assar', 7.00, 9, 'congelados/pizza.jpg', 6.00, 1),
(26, 'Hambúrguer Congelado', 'Pacote com 6 hambúrgueres de vaca', 8.00, 9, 'congelados/hamburguer.jpg', 7.00, 1),
(27, 'Batatas Fritas Congeladas', 'Batatas fritas congeladas prontas para fritar', 3.50, 9, 'congelados/batatas.jpg', NULL, 0),
(28, 'Croissant', 'Croissant folhado, ideal para o pequeno-almoço', 1.50, 10, 'pastelaria/croissant.jpg', 1.30, 1),
(29, 'Pastel de Nata', 'Pastel de nata tradicional', 1.00, 10, 'pastelaria/pastel_nata.jpg', NULL, 0),
(30, 'Bolo de Chocolate', 'Fatia de bolo de chocolate', 2.00, 10, 'pastelaria/bolo_chocolate.jpg', 1.80, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$TEsXaNEWG5ceqxJ9eKkii.gSTl8QQzXCG1I1zcqkCcnjGETipvcsS', 1, '2024-12-05 19:26:29'),
(2, 'alex', 'alex@gmail.com', '$2y$10$iYSIIIkocSHNIqIHPCAbPeYuOL5Da9DlLgACUvyIDUBPF0fyZcXxW', 0, '2024-12-12 11:33:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
