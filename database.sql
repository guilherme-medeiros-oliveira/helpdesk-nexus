CREATE DATABASE IF NOT EXISTS `nexus_helpdesk` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nexus_helpdesk`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('usuario','admin') DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chamados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `prioridade` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Aberto',
  `resposta` text DEFAULT NULL,
  `data_abertura` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chamado_mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chamado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `chamado_id` (`chamado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`) VALUES
('Admin NEXUS', 'admin@nexus.com', '0192023a7bbd73250516f069df18b500', 'admin'),
('Guilherme Teste', 'guilherme@teste.com', '202cb962ac59075b964b07152d234b70', 'usuario');
admin@nexus.com / admin123guilherme@teste.com / 123

/*Importar e logar:admin@nexus.com / admin123guilherme@teste.com / 123
