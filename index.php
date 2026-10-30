<?php
session_start();
include __DIR__ . '/config.php';

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
        // SALVA O ACEITE NO BANCO SE AINDA NÃO TIVER
        if(isset($_POST['aceite_termos']) && $_POST['aceite_termos'] == 1){
            $up2 = $conn->prepare("UPDATE usuarios SET aceite_termos = 1, aceite_data = NOW() WHERE id = ?");
            $up2->bind_param("i", $user['id']);
            $up2->execute();
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
<link rel="icon" type="image/png" href="/favicon.png?v=3">
<link rel="icon" type="image/x-icon" href="/favicon.ico?v=3">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | NEXUS - HelpDesk</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="page-login">
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
    <a href="sobre.php">Sobre</a>
    <a href="termos.php">Termos</a>
    <a href="privacidade.php">Privacidade</a>
  </nav>
</header>
<main class="hd-login-main">
  <div class="hd-card hd-login-card">
    <div class="hd-card-header is-center">
      <h2>NEXUS Login</h2>
      <p>Acesse o ponto de conexão entre o problema e a solução</p>
    </div>
    <form method="POST" class="hd-form" id="formLogin">
      <?php if($cadastrado): ?>
        <div class="hd-alert hd-alert-ok">Conta criada na base NEXUS! Faça login agora.</div>
      <?php endif; ?>
      <?php if($erro): ?>
        <div class="hd-alert hd-alert-err"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <div>
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" placeholder="seu.email@empresa.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>
      <div>
        <label for="senha">Senha</label>
        <div class="hd-password-field">
          <input id="senha" name="senha" type="password" placeholder="Sua senha NEXUS" required>
          <button type="button" class="hd-eye" id="toggleSenha" aria-label="Mostrar senha">👁</button>
        </div>
      </div>
      <input type="hidden" name="aceite_termos" id="aceite_termos_input" value="">
      <button type="submit" name="login" id="btnLogin" class="hd-btn-primary hd-btn-block" disabled>Entrar no NEXUS →</button>
      <div class="hd-form-hint">
        Não tem conta? <a href="registrar.php">Criar conta NEXUS</a>
      </div>
    </form>
  </div>
</main>

<footer class="hd-login-footer">
  <a href="sobre.php">Sobre nós</a> |
  <a href="termos.php">Termos de Uso</a> |
  <a href="privacidade.php">Privacidade (LGPD)</a><br><br>
  © 2026 NEXUS - HelpDesk • medeiros.oliveira.guilherme@gmail.com
</footer>

<div id="lgpd-overlay">
  <div class="lgpd-card">
    <h3>Antes de entrar no NEXUS</h3>
    <p>Para continuar, você precisa ler e concordar com nossas políticas. Levamos sua privacidade a sério (LGPD).</p>
    <div class="lgpd-links">
      <a href="termos.php" target="_blank">Termos de Uso</a>
      <a href="privacidade.php" target="_blank">Política de Privacidade</a>
      <a href="sobre.php" target="_blank">Sobre o NEXUS</a>
    </div>
    <div class="lgpd-check">
      <input type="checkbox" id="checkLgpd">
      <label for="checkLgpd">Li e concordo com os <b>Termos de Uso</b> e a <b>Política de Privacidade (LGPD)</b> do NEXUS. Autorizo o uso dos meus dados para gestão dos chamados.</label>
    </div>
    <button id="btnAceitar" class="lgpd-btn">Aceitar e entrar no NEXUS</button>
    <p class="lgpd-note">Você só precisa aceitar uma vez neste navegador.</p>
  </div>
</div>

<script>
const overlay = document.getElementById('lgpd-overlay');
const check = document.getElementById('checkLgpd');
const btn = document.getElementById('btnAceitar');
const btnLogin = document.getElementById('btnLogin');
const aceiteInput = document.getElementById('aceite_termos_input');

function habilitarLogin(){
  aceiteInput.value = '1';
  btnLogin.disabled = false;
}

if(!localStorage.getItem('nexus_lgpd_aceito')){
  overlay.classList.add('show');
} else {
  habilitarLogin();
}

check.addEventListener('change', ()=>{
  if(check.checked) btn.classList.add('enabled');
  else btn.classList.remove('enabled');
});

btn.addEventListener('click', ()=>{
  if(!check.checked) return;
  localStorage.setItem('nexus_lgpd_aceito', '1');
  localStorage.setItem('nexus_lgpd_data', new Date().toISOString());
  overlay.classList.remove('show');
  habilitarLogin();
});

const senhaInput = document.getElementById('senha');
const toggleSenha = document.getElementById('toggleSenha');
toggleSenha.addEventListener('click', ()=>{
  const mostrar = senhaInput.type === 'password';
  senhaInput.type = mostrar ? 'text' : 'password';
  toggleSenha.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
  toggleSenha.textContent = mostrar ? '🙈' : '👁';
});
</script>
</body>
</html>