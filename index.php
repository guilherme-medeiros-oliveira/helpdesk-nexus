<?php
include 'config.php';

$erro = "";
$cadastrado = isset($_GET['cadastrado']);

if(isset($_POST['login'])){
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if($res->num_rows > 0){
    $user = $res->fetch_assoc();
    $senha_banco = $user['senha'];
    $logou = false;

    if (password_verify($senha, $senha_banco)) {
        $logou = true;
    } else if (md5($senha) === $senha_banco) {
        $logou = true;
        $novo_hash = password_hash($senha, PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $up->bind_param("si", $novo_hash, $user['id']);
        $up->execute();
        $user['senha'] = $novo_hash;
    }

    if ($logou) {
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
<title>Login | NEXUS - HelpDesk</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="display:flex;flex-direction:column;min-height:100vh">

<header class="hd-header">
  <div class="hd-brand">
    <div class="hd-logo">N</div>
    <div>
      <strong>NEXUS <span>- HelpDesk</span></strong>
      <small>Ponto de Conexão • Suporte Profissional</small>
    </div>
  </div>
  <nav class="hd-nav">
    <span>SLA 4h úteis</span>
    <a href="registrar.php">Criar conta</a>
    <a href="#">Suporte</a>
  </nav>
</header>

<main class="hd-container" style="flex:1;display:grid;place-items:center">
  <div class="hd-card" style="max-width:420px;width:100%;margin:20px auto">
    <div class="hd-card-header" style="text-align:center;padding:32px 24px 18px">
      <div class="hd-logo" style="margin:0 auto 14px">N</div>
      <h2 style="margin:0 0 6px">NEXUS Login</h2>
      <p>Acesse o ponto de conexão entre o problema e a solução</p>
    </div>

    <form method="POST" class="hd-form" style="padding-top:10px">
      <?php if($cadastrado): ?>
        <div style="background:#dcfce7;color:#166534;padding:10px 12px;border-radius:10px;font-size:13px;border:1px solid #bbf7d0;text-align:center">✅ Conta criada na base NEXUS! Faça login agora.</div>
      <?php endif; ?>

      <?php if($erro): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:10px;font-size:13px;border:1px solid #fecaca;text-align:center"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div>
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" placeholder="seu.email@empresa.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>

      <div>
        <label for="senha">Senha</label>
        <input id="senha" name="senha" type="password" placeholder="Sua senha NEXUS" required>
      </div>

      <button name="login" class="hd-btn-primary" style="width:100%;min-height:48px;margin-top:4px">Entrar no NEXUS →</button>

      <div style="text-align:center;font-size:13px;color:#64748b;margin-top:2px">
        Não tem conta? <a href="registrar.php" style="color:#2563eb;font-weight:700;text-decoration:none">Criar conta NEXUS</a>
      </div>

      <div class="hd-info">
        🔒 Login seguro • Conversão automática MD5 → Bcrypt • Protocolo NX
      </div>
    </form>
  </div>
</main>

<footer class="hd-footer" style="margin-top:0">
  <p><strong>NEXUS - HelpDesk</strong> | NEXUS = Ponto de conexão central • Suporte N1/N2 • Osasco e Região • Atendimento Profissional</p>
</footer>

</body>
</html>
