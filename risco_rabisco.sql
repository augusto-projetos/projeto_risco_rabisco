-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Nov-2025 às 01:36
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `risco_rabisco`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento_itens`
--

CREATE TABLE `orcamento_itens` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`) VALUES
(1, 'Caderno Zip', 'Caderno resistente para uso diário.', 18.00, '../img/caderno.jpg'),
(2, 'Caderno D+', 'Caderno com capa durável e papel de qualidade.', 22.00, '../img/caderno_d.jpg'),
(3, 'Caneta Fina', 'Caneta de escrita fina e precisa.', 4.50, '../img/caneta_fina.jpg'),
(4, 'Lápis de Cor Faber', 'Lápis de cor com pigmentação intensa.', 30.00, '../img/faber_castell.jpg'),
(5, 'Lápis Leo&Leo', 'Lápis de cor com cores vivas e boa cobertura.', 10.00, '../img/lapis_cor.jpg'),
(6, 'Lápis Faber Grande', 'Kit completo de lápis de cor para desenhos detalhados.', 40.00, '../img/lapis_de_cor.jpg'),
(7, 'Lápis de Escrever', 'Lápis HB ideal para escrita suave.', 1.50, '../img/lapis_escrever.jpg'),
(8, 'Kit Lapiseira', 'Kit lapiseira confortável para escrita precisa.', 35.00, '../img/lapiseira.jpg'),
(9, 'Post-it Pequeno', 'Notas adesivas pequenas para recados rápidos.', 7.00, '../img/post_it_pequeno.jpg'),
(10, 'Post-it Bloco', 'Bloco de notas adesivas para lembretes e organização.', 10.00, '../img/post_it.jpg'),
(11, 'Estojo de Canetas Coloridas', 'Conjunto de canetas coloridas para escrita e desenho artístico.', 68.50, '../img/estojo_canetas.jpg'),
(12, 'Canetas Hidrográficas', 'Marcadores com ponta de feltro para colorir e desenhar.', 25.00, '../img/marca_texto.jpg'),
(13, 'Conjunto Lapis de cor', 'Lápis coloridos para pintura e ilustração.', 140.00, '../img/conjunto_colorir.jpg'),
(14, 'Compasso', 'Um método mais fácil para fazer círculos', 25.00, '../img/compasso.jpg'),
(15, 'Conjunto de Pincéis', 'Lápis coloridos para pintura e ilustração.', 12.00, '../img/pincel_destaque.jpg'),
(16, 'Caderno de Desenho preto', 'Caderno com folhas pretas para desenhos com canetas gel ou marcadores.', 35.50, '../img/caderno_preto.jpg'),
(17, 'Conjunto de Cor Profissional', 'Lápis de alta qualidade para trabalhos artísticos detalhados.', 235.00, '../img/conjunto_cor.jpg'),
(18, 'Caderno de Anotacoes', 'Estojo simples para guardar lápis, canetas e outros materiais.', 60.00, '../img/caderno_anotacoes.jpg'),
(19, 'Caneta Faber Castel', 'Ideal para escrever em cartazes.', 24.50, '../img/caneta_faber.jpg'),
(20, 'Corretivo Escolar', 'Usado para apagar uma resposta a caneta caso tenha errado', 4.50, '../img/corretivo_escolar.jpg'),
(21, 'Caderno para Desenho', 'Caderno usado para fazer desenhos a mao', 25.00, '../img/caderno_desenho.jpg'),
(22, 'Cola Bastao', 'Serve para colar folhan no caderno', 12.00, '../img/cola_bastao.jpg'),
(23, 'Caderno Escolar Bola Preta', 'Caderno com capa decorada e espiral, ideal para anotações ou organização pessoal.', 54.00, '../img/caderno_bola_preta.jpg'),
(24, 'Grampeador', 'Ferramenta para unir folhas de papel com grampos metálicos.', 14.50, '../img/grampeador.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);

--
-- Limitadores para a tabela `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  ADD CONSTRAINT `orcamento_itens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `orcamento_itens_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
