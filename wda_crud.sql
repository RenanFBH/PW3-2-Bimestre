-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/09/2024 às 23:54
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
-- SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `wda_crud`
--
CREATE DATABASE IF NOT EXISTS `wda_crud` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wda_crud`;


-- --------------------------------------------------------

--
-- Estrutura para tabela `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cpf_cnpj` varchar(14) NOT NULL,
  `birthdate` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `hood` varchar(100) NOT NULL,
  `zip_code` varchar(8) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(2) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `ie` varchar(12) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `customers`
--

INSERT INTO `customers` (`id`, `name`, `cpf_cnpj`, `birthdate`, `address`, `hood`, `zip_code`, `city`, `state`, `phone`, `mobile`, `ie`, `created`, `modified`) VALUES
(1, 'Fulano de Tal', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(2, 'Ciclano de Tal', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(3, 'Customer 3', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(4, 'Customer 4', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(5, 'Customer 5', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(6, 'Customer 6', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(7, 'Customer 7', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00'),
(8, 'Customer 8', '123.456.789-00', '1989-01-01 00:00:00', 'Rua da Web, 123', 'Internet', '12345678', 'Teste', 'SP', '15 55663322', '15955663322', '12345678', '2016-05-24 00:00:00', '2016-05-24 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerentes`
--

CREATE TABLE `gerentes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `endereco` varchar(50) NOT NULL,
  `depto` varchar(20) NOT NULL,
  `datanasc` datetime NOT NULL,
  `foto` varchar(30) NOT NULL,
  `criacao` datetime NOT NULL,
  `modificacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `gerentes`
--

INSERT INTO `gerentes` (`id`, `nome`, `endereco`, `depto`, `datanasc`, `foto`, `criacao`, `modificacao`) VALUES
(1, 'Ana Oliveira', 'Avenida das Palmeiras 456, Rio de Janeiro RJ', 'Logística', '1975-08-28 00:00:00', 'ger_AnaO.jpg', '2023-11-11 00:00:00', '2024-03-20 00:00:00'),
(2, 'Beatriz Santos', 'Rua dos Girassóis 345, Florianópolis SC', 'Produção', '1982-03-25 00:00:00', 'ger_BeatrizS.jpg', '2024-09-03 00:00:00', '2023-12-24 00:00:00'),
(3, 'Camila Souza', 'Rua das Violetas 901, Vitória ES', 'Engenharia', '1984-09-05 00:00:00', 'ger_CamilaS.jpg', '2023-10-19 00:00:00', '2024-06-10 00:00:00'),
(4, 'Carla Costa', 'Avenida dos Bandeirantes 321, Brasília DF', 'Desenvolvimento', '1978-06-03 00:00:00', 'ger_CarlaC.jpg', '2024-02-15 00:00:00', '2024-04-10 00:00:00'),
(5, 'Carolina Lima', 'Rua dos Jasmins 789, Belém PA', 'Finanças', '1975-08-15 00:00:00', 'ger_CarolinaL.jpg', '2023-12-27 00:00:00', '2024-01-27 00:00:00'),
(6, 'Eluana Jorge', 'Rua Abel B. Kart 332, Sorocaba SP', 'Administrativo', '1982-06-06 00:00:00', 'ger_EluanaJ.jpg', '2024-05-28 00:00:00', '2023-11-07 00:00:00'),
(7, 'Felipe Pereira', 'Avenida das Tulipas 234, Teresina PI', 'TI', '1990-11-28 00:00:00', 'ger_FelipeP.jpg', '2024-06-07 00:00:00', '2024-05-25 00:00:00'),
(8, 'Fernando Almeida', 'Avenida da Liberdade 654, Porto Alegre RS', 'Vendas', '1972-12-12 00:00:00', 'ger_FernandoA.jpg', '2024-08-01 00:00:00', '2024-07-25 00:00:00'),
(9, 'Guilherme Silva', 'Avenida dos Lírios 210, Natal RN', 'Desenvolvimento', '1977-12-18 00:00:00', 'ger_GuilhermeS.jpg', '2024-03-19 00:00:00', '2023-09-20 00:00:00'),
(10, 'Gustavo Marques', 'Rua Abel B. Kart 332, Sorocaba SP', 'Recursos Humanos', '2008-02-23 00:00:00', 'ger_GustavoM.jpg', '2024-04-13 00:00:00', '2024-02-16 00:00:00'),
(11, 'Isabela Costa', 'Rua das Orquídeas 543, João Pessoa PB', 'Marketing', '1986-06-29 00:00:00', 'ger_IsabelaC.jpg', '2024-05-19 00:00:00', '2024-03-17 00:00:00'),
(12, 'Juliana Pereira', 'Rua dos Pinheiros 567, Curitiba PR', 'Marketing', '1985-09-20 00:00:00', 'ger_JulianaP.jpg', '2024-03-10 00:00:00', '2023-12-11 00:00:00'),
(13, 'Larissa Ferreira', 'Rua das Margaridas 876, Maceió AL', 'Atendimento', '1988-04-07 00:00:00', 'ger_LarissaF.jpg', '2024-06-20 00:00:00', '2023-11-29 00:00:00'),
(14, 'Leonardo Oliveira', 'Avenida das Rosas 678, Goiânia GO', 'Logística', '1979-07-14 00:00:00', 'ger_LeonardoO.jpg', '2023-12-18 00:00:00', '2024-03-03 00:00:00'),
(15, 'Miguel Silva', 'Rua do Comércio 123, São Paulo SP', 'Produção', '1980-04-15 00:00:00', 'ger_MiguelS.jpg', '2024-02-24 00:00:00', '2024-01-05 00:00:00'),
(16, 'Patrícia Sousa', 'Rua do Rosário 432, Salvador BA', 'Atendimento', '1987-07-25 00:00:00', 'ger_PatriciaS.jpg', '2023-10-16 00:00:00', '2024-01-06 00:00:00'),
(17, 'Renan Bezerra', 'Rua Capitais 667, Sorocaba SP', 'Administrativo', '2008-03-13 00:00:00', 'ger_RenanB.jpg', '2024-08-24 00:00:00', '2024-03-31 00:00:00'),
(18, 'Roberto Santos', 'Avenida dos Cravos 456, Palmas TO', 'Qualidade', '1980-01-20 00:00:00', 'ger_RobertoS.jpg', '2023-12-26 00:00:00', '2024-01-12 00:00:00'),
(19, 'Rodrigo Santos', 'Rua das Flores 789, Belo Horizonte MG', 'Engenharia', '1983-11-10 00:00:00', 'ger_RodrigoS.jpg', '2024-08-10 00:00:00', '2023-10-30 00:00:00'),
(20, 'Thiago Almeida', 'Avenida das Hortênsias 123, Cuiabá MT', 'Vendas', '1973-10-10 00:00:00', 'ger_ThiagoA.jpg', '2024-03-29 00:00:00', '2023-11-13 00:00:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `gerentes`
--
ALTER TABLE `gerentes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `gerentes`
--
ALTER TABLE `gerentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Script para criar e cadastrar na tabela usuários (execute no banco do projeto):
CREATE TABLE usuarios(
    id int AUTO_INCREMENT not null PRIMARY KEY,
    nome varchar(50) not null,
    user varchar(50) not null,
    password varchar(100) not null,
    foto varchar(50)
);

INSERT INTO `usuarios`(`nome`, `user`, `password`, `foto`) 
VALUES ('admin','admin','2aAnwG7BO/.7I', 'adminfoto.jpg'),
('Zé Lele','zelele','2aOABjBEl7QTs', 'joaozin.jpg'),
('Mary Zica','mazi','2arYtpnwCuBMA', 'Lucao123.jpg'),
('Fugiru Nakombi','fugina','2adyrUhvaL6o2', 'semimagem.jpg'),
('Usuário 5','user5','2aKCRSFibUheY', 'semimagem.jpg'),
('Usuário 6','user6','2a4bjV7mR4Fxs', 'semimagem.jpg'),
('Usuário 7','user7','2a2vwxMhnBjZQ', 'semimagem.jpg'),
('Usuário 8','user8','2arYfwbnnD51E', 'semimagem.jpg');



COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;