CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  senha VARCHAR(255),
  tipo ENUM('usuario','admin') DEFAULT 'usuario'
);

CREATE TABLE chamados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  titulo VARCHAR(200),
  descricao TEXT,
  categoria VARCHAR(50),
  prioridade VARCHAR(20),
  status VARCHAR(20) DEFAULT 'Aberto',
  resposta TEXT,
  data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuários de teste
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Admin', 'admin@helpdesk.com', MD5('admin123'), 'admin'),
('Guilherme Teste', 'guilherme@teste.com', MD5('123'), 'usuario');
