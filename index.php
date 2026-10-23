<?php
include 'config.php';
session_start();

$erro = "";

if(isset($_POST['login'])){
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  $stmt = $conn->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if($res->num_rows > 0){
    $user = $res->fetch_assoc();
    

    if (password_verify($senha, $user['senha']) || md5($senha) === $user['senha']) {
      

      if (md5($senha) === $user['senha']) {
        $novoHash = password_hash($senha, PASSWORD_DEFAULT);
        $upd = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $upd->bind_param("si", $novoHash, $user['id']);
        $upd->execute();
      }

      $_SESSION['user'] = $user;
      header("Location: dashboard.php"); 
      exit;
    } else {
      $erro = "Senha incorreta";
    }
  } else { 
    $erro = "E-mail não encontrado"; 
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>

.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  background: #f4f6f9;
}

.login-form {
  background: white;
  padding: 32px 24px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 400px;
}

.login-form h2 {
  text-align: center;
  margin-bottom: 24px;
  font-size: 24px;
}

.login-form input {
  width: 100%;
  padding: 14px 16px;
  margin-bottom: 16px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 16px;
}

.login-form button {
  width: 100%;
  padding: 14px;
  background: #1a73e8;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  margin-top: 8px;
  min-height: 48px;
}

.login-form button:hover {
  background: #0d62d0;
}

.erro {
  background: #fdecea;
  color: #b91c1c;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 14px;
  text-align: center;
}

.criar-conta {
  display: block;
  text-align: center;
  margin-top: 20px;
  color: #1a73e8;
  font-size: 14px;
}

@media (max-width: 480px) {
  .login-form {
    padding: 24px 16px;
    box-shadow: none;
    border: 1px solid #eee;
  }
}
</style>
</head>
<body>
<div class="login-wrapper">
  <form method="POST" class="login-form">
    <h2>HelpDesk Login</h2>

    <?php if($erro): ?>
      <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <input name="email" type="email" placeholder="Seu e-mail" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    <input name="senha" type="password" placeholder="Sua senha" required>
    <button name="login">Entrar</button>
    <a href="registrar.php" class="criar-conta">Não tem conta? Criar conta</a>
  </form>
</div>
</body>
</html>
