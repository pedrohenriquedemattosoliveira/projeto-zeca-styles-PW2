-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Dez-2024 às 01:01
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `barbearia`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `barbeiros`
--

CREATE TABLE `barbeiros` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `experiencia` int(11) NOT NULL,
  `horarios` varchar(20) NOT NULL,
  `sobre` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `barbeiros`
--

INSERT INTO `barbeiros` (`id`, `nome`, `status`, `cpf`, `email`, `telefone`, `experiencia`, `horarios`, `sobre`, `foto`) VALUES
(1, 'João Silva', 1, '', '', '', 0, '', NULL, NULL),
(2, 'Pedro Santos', 1, '', '', '', 0, '', NULL, NULL),
(3, 'Carlos Oliveira', 1, '', '', '', 0, '', NULL, NULL),
(4, 'Pedro Henrique de Mattos Oliveira', 1, '466.443.638-65', 'ph.mattosoliveira@gmail.com', '(11) 95691-7394', 3, 'integral', 'teste', NULL),
(5, 'Pedro Henrique de Mattos', 1, '466.443.638-65', 'ph.mattosoliveira@gmail.com', '(11) 95691-7394', 3, 'integral', 'teste', NULL),
(6, 'Pedro Henrique de Mattos', 1, '466.443.638-65', 'ph.mattosoliveira@gmail.com', '(11) 95691-7394', 3, 'integral', 'teste', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `barbeiro_especialidades`
--

CREATE TABLE `barbeiro_especialidades` (
  `id` int(11) NOT NULL,
  `barbeiro_id` int(11) NOT NULL,
  `especialidade` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `barbeiro_especialidades`
--

INSERT INTO `barbeiro_especialidades` (`id`, `barbeiro_id`, `especialidade`) VALUES
(1, 4, 'corte'),
(2, 4, 'barba'),
(3, 4, 'quimica'),
(4, 4, 'pigmentacao'),
(5, 5, 'corte'),
(6, 5, 'barba'),
(7, 5, 'quimica'),
(8, 5, 'pigmentacao'),
(9, 6, 'corte'),
(10, 6, 'barba'),
(11, 6, 'quimica'),
(12, 6, 'pigmentacao');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `endereco` text DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `barbeiro_preferido` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `data_nascimento`, `email`, `telefone`, `senha`, `endereco`, `cidade`, `estado`, `barbeiro_preferido`, `observacoes`, `foto`, `created_at`) VALUES
(1, 'Richard', '2007-10-04', 'teste@gmail.com', '(11) 99532-5631', '$2y$10$AWt5qFf8vBnJp4XWT/170OFK2XYs8F9h7nzOC3RIOlJ5Ulzdf.lpy', 'teste, teste, teste', 'sao paulo', 'AL', 2, 'teste', NULL, '2024-11-28 22:33:19'),
(2, 'Pedro Henriqye', '5456-08-12', 'barbeiro@gmail.com', '(11) 99282-3924', '$2y$10$Ukk4ZOqEQi2/Ot1f8otdB.ZLDMYoMTNuxeQqezdJDUQ3cPiIo65yu', 'teste', 'teste', 'AC', 1, 'teste', NULL, '2024-12-09 23:58:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencias_cliente`
--

CREATE TABLE `preferencias_cliente` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `servico` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `preferencias_cliente`
--

INSERT INTO `preferencias_cliente` (`id`, `cliente_id`, `servico`) VALUES
(1, 1, 'pigmentacao'),
(2, 2, 'barba'),
(3, 2, 'pigmentacao'),
(4, 2, 'hidratacao');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `barbeiros`
--
ALTER TABLE `barbeiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `barbeiro_especialidades`
--
ALTER TABLE `barbeiro_especialidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barbeiro_id` (`barbeiro_id`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `preferencias_cliente`
--
ALTER TABLE `preferencias_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `barbeiros`
--
ALTER TABLE `barbeiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `barbeiro_especialidades`
--
ALTER TABLE `barbeiro_especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `preferencias_cliente`
--
ALTER TABLE `preferencias_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `barbeiro_especialidades`
--
ALTER TABLE `barbeiro_especialidades`
  ADD CONSTRAINT `barbeiro_especialidades_ibfk_1` FOREIGN KEY (`barbeiro_id`) REFERENCES `barbeiros` (`id`);

--
-- Limitadores para a tabela `preferencias_cliente`
--
ALTER TABLE `preferencias_cliente`
  ADD CONSTRAINT `preferencias_cliente_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
