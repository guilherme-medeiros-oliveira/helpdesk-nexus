<?php
include 'config.php';
session_start();

$erro = "";

if(isset($_POST['criar'])){
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  if (strlen($nome) < 3) {
    $erro = "Nome muito curto";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = "E-mail inválido";
  } elseif (strlen($senha) < 6) {
    $erro = "Senha mínima de 6 caracteres";
  } else {

    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
      $erro = "Este e-mail já está cadastrado";
    } else {

      $hash = password_hash($senha, PASSWORD_DEFAULT);
      $tipo = 'usuario'; // padrão
      
      if (strtolower($email) === 'admin@helpdesk.com') {
        $tipo = 'admin';
      }

      $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $nome, $email, $hash, $tipo);

      if ($stmt->execute()) {
        header("Location: index.php?criado=1");
        exit;
      } else {
        $erro = "Erro ao criar conta: " . $conn->error;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta - HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>
.registrar-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: #f4f6f9;
}

.registrar-form {
  background: white;
  padding: 32px 24px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 400px;
}

.registrar-form h3 {
  text-align: center;
  margin-bottom: 24px;
  font-size: 24px;
  color: #111827;
}

.registrar-form input {
  width: 100%;
  padding: 14px 16px;
  margin-bottom: 16px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 16px;
}

.registrar-form input:focus {
  outline: none;
  border-color: #1a73e8;
  box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
}

.registrar-form button {
  width: 100%;
  padding: 14px;
  background: #111827;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  min-height: 48px;
  margin-top: 8px;
}

.registrar-form button:hover {
  background: #000;
}

.alert-erro {
  background: #fdecea;
  color: #b91c1c;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 14px;
  text-align: center;
}

.link-login {
  display: block;
  text-align: center;
  margin-top: 20px;
  color: #6b7280;
  font-size: 14px;
}

.link-login a {
  color: #1a73e8;
  font-weight: 600;
  text-decoration: none;
}

@media (max-width: 480px) {
  .registrar-form { padding: 24px 16px; box-shadow: none; border: 1px solid #eee; }
}
</style>
</head>
<body>
<div class="registrar-wrapper">
  <form method="POST" class="registrar-form">
    <h3>Criar Conta</h3>

    <?php if($erro): ?>
      <div class="alert-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <input name="nome" placeholder="Seu nome completo" required minlength="3" value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
    <input name="email" type="email" placeholder="Seu melhor e-mail" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    <input name="senha" type="password" placeholder="Crie uma senha (mín. 6 caracteres)" required minlength="6">

    <button name="criar">Criar minha conta</button>

    <span class="link-login">Já tem conta? <a href="index.php">Fazer login</a></span>
  </form>
</div>
</body>
</html>
