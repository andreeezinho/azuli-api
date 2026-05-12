-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: May 11, 2026 at 11:10 PM
-- Server version: 9.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `documento` varchar(18) DEFAULT NULL,
  `telefone` varchar(15) NOT NULL,
  `ie_rg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'ISENTO',
  `contribuinte` int NOT NULL DEFAULT '0',
  `enderecos_id` int DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cofins`
--

CREATE TABLE `cofins` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `codigo` varchar(3) NOT NULL,
  `tributacao` float(7,2) NOT NULL,
  `valor` float(7,2) NOT NULL DEFAULT '0.00',
  `vbc` float(7,2) NOT NULL DEFAULT '0.00',
  `ativo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinatarios`
--

CREATE TABLE `destinatarios` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `empresas_id` int NOT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emitentes`
--

CREATE TABLE `emitentes` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `empresas_id` int NOT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `telefone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ie_rg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `num_serie_nfe` int NOT NULL,
  `enderecos_id` int NOT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `cep` varchar(10) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `codigo` int DEFAULT NULL,
  `cidade` varchar(150) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `numero` varchar(10) NOT NULL DEFAULT 'S/N',
  `complemento` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grupo_produto`
--

CREATE TABLE `grupo_produto` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `icms`
--

CREATE TABLE `icms` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `orig` int NOT NULL DEFAULT '0',
  `tipo` varchar(45) NOT NULL,
  `codigo` varchar(3) NOT NULL,
  `tributacao` float(7,2) NOT NULL,
  `valor` float(7,2) NOT NULL DEFAULT '0.00',
  `vbc` float(7,2) NOT NULL DEFAULT '0.00',
  `ativo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipi`
--

CREATE TABLE `ipi` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `cEnq` int NOT NULL DEFAULT '999',
  `codigo` varchar(3) NOT NULL,
  `tributacao` float(7,2) NOT NULL,
  `valor` float(7,2) NOT NULL DEFAULT '0.00',
  `vbc` float(7,2) NOT NULL DEFAULT '0.00',
  `ativo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nota_fiscal`
--

CREATE TABLE `nota_fiscal` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `nat_op` varchar(255) NOT NULL,
  `chave` varchar(44) NOT NULL,
  `num_nf` int NOT NULL,
  `situacao` varchar(100) NOT NULL,
  `vendas_id` int NOT NULL,
  `destinatarios_id` int DEFAULT NULL,
  `total` float(7,2) NOT NULL,
  `xml_path` varchar(255) NOT NULL,
  `xml_evento_path` varchar(255) DEFAULT NULL,
  `num_evento` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nota_fiscal_entrada`
--

CREATE TABLE `nota_fiscal_entrada` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `chave` varchar(44) NOT NULL,
  `num_nf` int NOT NULL,
  `nat_op` varchar(255) NOT NULL,
  `gravada` tinyint NOT NULL DEFAULT '0',
  `data_emissao` timestamp NOT NULL,
  `emitentes_id` int NOT NULL,
  `total` float(7,2) NOT NULL,
  `xml_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `forma` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissao_usuario`
--

CREATE TABLE `permissao_usuario` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `permissoes_id` int NOT NULL,
  `usuarios_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('visualizar','cadastrar','editar','deletar') NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pis`
--

CREATE TABLE `pis` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `codigo` varchar(3) NOT NULL,
  `tributacao` float(7,2) NOT NULL,
  `valor` float(7,2) NOT NULL DEFAULT '0.00',
  `vbc` float(7,2) NOT NULL DEFAULT '0.00',
  `ativo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` varchar(13) NOT NULL,
  `preco` float(7,2) NOT NULL,
  `estoque` float(7,2) NOT NULL DEFAULT '0.00',
  `tipo` enum('UN','KG','CX','PC') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quant_entrada` float(7,2) NOT NULL,
  `quant_saida` float(7,2) NOT NULL,
  `grupo_produto_id` int NOT NULL,
  `icms_id` int DEFAULT NULL,
  `ipi_id` int DEFAULT NULL,
  `pis_id` int DEFAULT NULL,
  `cofins_id` int DEFAULT NULL,
  `cfop` int NOT NULL,
  `ncm` int DEFAULT NULL,
  `cest` int DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recuperar_senha`
--

CREATE TABLE `recuperar_senha` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `usuarios_id` int NOT NULL,
  `codigo` int NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `cargo` enum('Administrativo','Frente de Caixa','Repositor','Entregador') NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `icone` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendas`
--

CREATE TABLE `vendas` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `desconto` int NOT NULL DEFAULT '0',
  `total` float(7,2) NOT NULL DEFAULT '0.00',
  `troco` float(7,2) NOT NULL DEFAULT '0.00',
  `usuarios_id` int NOT NULL,
  `situacao` enum('cancelada','em andamento','em espera','concluida') NOT NULL DEFAULT 'em andamento',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venda_cliente`
--

CREATE TABLE `venda_cliente` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `clientes_id` int NOT NULL,
  `vendas_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venda_pagamento`
--

CREATE TABLE `venda_pagamento` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `valor` float(7,2) NOT NULL,
  `vendas_id` int NOT NULL,
  `pagamento_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venda_produto`
--

CREATE TABLE `venda_produto` (
  `id` int NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `quantidade` float(7,2) NOT NULL DEFAULT '1.00',
  `vendas_id` int NOT NULL,
  `produtos_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_clientes_enderecos1_idx` (`enderecos_id`);

--
-- Indexes for table `cofins`
--
ALTER TABLE `cofins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `destinatarios`
--
ALTER TABLE `destinatarios`
  ADD PRIMARY KEY (`id`,`empresas_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_destinatarios_empresas1_idx` (`empresas_id`);

--
-- Indexes for table `emitentes`
--
ALTER TABLE `emitentes`
  ADD PRIMARY KEY (`id`,`empresas_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_emitentes_empresas1_idx` (`empresas_id`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_destinatarios_enderecos1_idx` (`enderecos_id`);

--
-- Indexes for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `grupo_produto`
--
ALTER TABLE `grupo_produto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `icms`
--
ALTER TABLE `icms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `ipi`
--
ALTER TABLE `ipi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_nota_fiscal_vendas1_idx` (`vendas_id`),
  ADD KEY `fk_nota_fiscal_destinatarios1_idx` (`destinatarios_id`);

--
-- Indexes for table `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD PRIMARY KEY (`id`,`emitentes_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_nota_fiscal_entrada_emitentes1_idx` (`emitentes_id`);

--
-- Indexes for table `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `permissao_usuario`
--
ALTER TABLE `permissao_usuario`
  ADD PRIMARY KEY (`id`,`permissoes_id`,`usuarios_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_permissao_usuario_permissoes1_idx` (`permissoes_id`),
  ADD KEY `fk_permissao_usuario_usuarios1_idx` (`usuarios_id`);

--
-- Indexes for table `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `pis`
--
ALTER TABLE `pis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_produtos_grupo_produto1_idx` (`grupo_produto_id`),
  ADD KEY `fk_produtos_icms1_idx` (`icms_id`),
  ADD KEY `fk_produtos_ipi1_idx` (`ipi_id`),
  ADD KEY `fk_produtos_pis1_idx` (`pis_id`),
  ADD KEY `fk_produtos_cofins1_idx` (`cofins_id`);

--
-- Indexes for table `recuperar_senha`
--
ALTER TABLE `recuperar_senha`
  ADD PRIMARY KEY (`id`,`usuarios_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_recuperar_senha_usuarios1_idx` (`usuarios_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  ADD UNIQUE KEY `usuario_UNIQUE` (`usuario`);

--
-- Indexes for table `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_vendas_usuarios1_idx` (`usuarios_id`);

--
-- Indexes for table `venda_cliente`
--
ALTER TABLE `venda_cliente`
  ADD PRIMARY KEY (`id`,`clientes_id`,`vendas_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_venda_fiado_clientes1_idx` (`clientes_id`),
  ADD KEY `fk_venda_fiado_vendas1_idx` (`vendas_id`);

--
-- Indexes for table `venda_pagamento`
--
ALTER TABLE `venda_pagamento`
  ADD PRIMARY KEY (`id`,`vendas_id`,`pagamento_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_venda_pagamento_vendas1_idx` (`vendas_id`),
  ADD KEY `fk_venda_pagamento_pagamento1_idx` (`pagamento_id`);

--
-- Indexes for table `venda_produto`
--
ALTER TABLE `venda_produto`
  ADD PRIMARY KEY (`id`,`vendas_id`,`produtos_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD KEY `fk_venda_produto_vendas1_idx` (`vendas_id`),
  ADD KEY `fk_venda_produto_produtos1_idx` (`produtos_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cofins`
--
ALTER TABLE `cofins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinatarios`
--
ALTER TABLE `destinatarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emitentes`
--
ALTER TABLE `emitentes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupo_produto`
--
ALTER TABLE `grupo_produto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `icms`
--
ALTER TABLE `icms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipi`
--
ALTER TABLE `ipi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissao_usuario`
--
ALTER TABLE `permissao_usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pis`
--
ALTER TABLE `pis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recuperar_senha`
--
ALTER TABLE `recuperar_senha`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venda_cliente`
--
ALTER TABLE `venda_cliente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venda_pagamento`
--
ALTER TABLE `venda_pagamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venda_produto`
--
ALTER TABLE `venda_produto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_enderecos1` FOREIGN KEY (`enderecos_id`) REFERENCES `enderecos` (`id`);

--
-- Constraints for table `destinatarios`
--
ALTER TABLE `destinatarios`
  ADD CONSTRAINT `fk_destinatarios_empresas1` FOREIGN KEY (`empresas_id`) REFERENCES `empresas` (`id`);

--
-- Constraints for table `emitentes`
--
ALTER TABLE `emitentes`
  ADD CONSTRAINT `fk_emitentes_empresas1` FOREIGN KEY (`empresas_id`) REFERENCES `empresas` (`id`);

--
-- Constraints for table `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `fk_destinatarios_enderecos1` FOREIGN KEY (`enderecos_id`) REFERENCES `enderecos` (`id`);

--
-- Constraints for table `nota_fiscal`
--
ALTER TABLE `nota_fiscal`
  ADD CONSTRAINT `fk_nota_fiscal_destinatarios1` FOREIGN KEY (`destinatarios_id`) REFERENCES `destinatarios` (`id`),
  ADD CONSTRAINT `fk_nota_fiscal_vendas1` FOREIGN KEY (`vendas_id`) REFERENCES `vendas` (`id`);

--
-- Constraints for table `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD CONSTRAINT `fk_nota_fiscal_entrada_emitentes1` FOREIGN KEY (`emitentes_id`) REFERENCES `emitentes` (`id`);

--
-- Constraints for table `permissao_usuario`
--
ALTER TABLE `permissao_usuario`
  ADD CONSTRAINT `fk_permissao_usuario_permissoes1` FOREIGN KEY (`permissoes_id`) REFERENCES `permissoes` (`id`),
  ADD CONSTRAINT `fk_permissao_usuario_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_cofins1` FOREIGN KEY (`cofins_id`) REFERENCES `cofins` (`id`),
  ADD CONSTRAINT `fk_produtos_grupo_produto1` FOREIGN KEY (`grupo_produto_id`) REFERENCES `grupo_produto` (`id`),
  ADD CONSTRAINT `fk_produtos_icms1` FOREIGN KEY (`icms_id`) REFERENCES `icms` (`id`),
  ADD CONSTRAINT `fk_produtos_ipi1` FOREIGN KEY (`ipi_id`) REFERENCES `ipi` (`id`),
  ADD CONSTRAINT `fk_produtos_pis1` FOREIGN KEY (`pis_id`) REFERENCES `pis` (`id`);

--
-- Constraints for table `recuperar_senha`
--
ALTER TABLE `recuperar_senha`
  ADD CONSTRAINT `fk_recuperar_senha_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `fk_vendas_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `venda_cliente`
--
ALTER TABLE `venda_cliente`
  ADD CONSTRAINT `fk_venda_fiado_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_venda_fiado_vendas1` FOREIGN KEY (`vendas_id`) REFERENCES `vendas` (`id`);

--
-- Constraints for table `venda_pagamento`
--
ALTER TABLE `venda_pagamento`
  ADD CONSTRAINT `fk_venda_pagamento_pagamento1` FOREIGN KEY (`pagamento_id`) REFERENCES `pagamentos` (`id`),
  ADD CONSTRAINT `fk_venda_pagamento_vendas1` FOREIGN KEY (`vendas_id`) REFERENCES `vendas` (`id`);

--
-- Constraints for table `venda_produto`
--
ALTER TABLE `venda_produto`
  ADD CONSTRAINT `fk_venda_produto_produtos1` FOREIGN KEY (`produtos_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `fk_venda_produto_vendas1` FOREIGN KEY (`vendas_id`) REFERENCES `vendas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
