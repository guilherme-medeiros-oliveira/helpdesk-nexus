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
        if(isset($_POST['aceite_termos'])){
            $up2 = $conn->prepare("UPDATE usuarios SET aceite_termos = 1, aceite_data = NOW() WHERE id = ?");
            $up2->bind_param("i", $user['id']);
            $up2->execute();
        }
        $_SESSION['user'] = $user;
        header("Location: /dashboard.php");
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
<style>
/* MODAL LGPD NEXUS */
#lgpd-overlay{position:fixed;inset:0;background:rgba(15,23,42,.75);backdrop-filter:blur(6px);z-index:9999;display:none;place-items:center;padding:16px}
#lgpd-overlay.show{display:grid}
.lgpd-card{background:white;max-width:480px;width:100%;border-radius:20px;padding:24px;box-shadow:0 20px 60px rgba(0,0,0,.3);animation:pop .3s ease}
@keyframes pop{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
.lgpd-card h3{margin:0 0 8px;font-size:18px;display:flex;align-items:center;gap:8px}
.lgpd-card p{font-size:14px;color:#475569;line-height:1.5;margin:0 0 16px}
.lgpd-links{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap}
.lgpd-links a{background:#eff6ff;color:#2563eb;padding:8px 12px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;border:1px solid #dbeafe}
.lgpd-check{display:flex;gap:10px;align-items:flex-start;background:#f8fafc;padding:12px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:16px}
.lgpd-check input{margin-top:3px;width:18px;height:18px}
.lgpd-check label{font-size:13px;color:#334155}
.lgpd-btn{width:100%;background:#2563eb;color:white;border:0;padding:14px;border-radius:12px;font-weight:800;cursor:pointer;opacity:.5;pointer-events:none;transition:.2s}
.lgpd-btn.enabled{opacity:1;pointer-events:auto}
.lgpd-btn.enabled:hover{background:#1d4ed8}
</style>
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
    <a href="/registrar.php">Criar conta</a>
    <a href="/">Login</a>
    <a href="/sobre.php">Sobre</a>
    <a href="/termos.php">Termos</a>
    <a href="/privacidade.php" class="active">Privacidade</a>
  </nav>
</header>
<main class="hd-container" style="flex:1;display:grid;place-items:center">
  <div class="hd-card" style="max-width:420px;width:100%;margin:20px auto">
    <div class="hd-card-header" style="text-align:center;padding:32px 24px 18px">
      <div class="hd-logo" style="margin:0 auto 14px">N</div>
      <h2 style="margin:0 0 6px">NEXUS Login</h2>
      <p>Acesse o ponto de conexão entre o problema e a solução</p>
    </div>
    <form method="POST" class="hd-form" id="formLogin" style="padding-top:10px">
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
      <input type="hidden" name="aceite_termos" id="aceite_termos_input" value="1">
      <button name="login" class="hd-btn-primary" style="width:100%;min-height:48px;margin-top:4px">Entrar no NEXUS →</button>
      <div style="text-align:center;font-size:13px;color:#64748b;margin-top:2px">
        Não tem conta? <a href="/registrar.php" style="color:#2563eb;font-weight:700;text-decoration:none">Criar conta NEXUS</a>
      </div>
    </form>
  </div>
</main>

<footer style="background:#0f172a;color:#94a3b8;padding:20px;text-align:center;font-size:12px;">
  <a href="/sobre.php" style="color:#e2e8f0;text-decoration:none;margin:0 6px;">Sobre nós</a> |
  <a href="/termos.php" style="color:#e2e8f0;text-decoration:none;margin:0 6px;">Termos de Uso</a> |
  <a href="/privacidade.php" style="color:#e2e8f0;text-decoration:none;margin:0 6px;">Privacidade (LGPD)</a><br><br>
  © 2026 NEXUS - HelpDesk • medeiros.oliveira.guilherme@gmail.com
</footer>

<div id="lgpd-overlay">
  <div class="lgpd-card">
    <h3>🔒 Antes de entrar no NEXUS</h3>
    <p>Para continuar, você precisa ler e concordar com nossas políticas. Levamos sua privacidade a sério (LGPD).</p>
    <div class="lgpd-links">
      <a href="/termos.php" target="_blank">📄 Termos de Uso</a>
      <a href="/privacidade.php" target="_blank">🔐 Política de Privacidade</a>
      <a href="/sobre.php" target="_blank">ℹ️ Sobre o NEXUS</a>
    </div>
    <div class="lgpd-check">
      <input type="checkbox" id="checkLgpd">
      <label for="checkLgpd">Li e concordo com os <b>Termos de Uso</b> e a <b>Política de Privacidade (LGPD)</b> do NEXUS. Autorizo o uso dos meus dados para gestão dos chamados.</label>
    </div>
    <button id="btnAceitar" class="lgpd-btn">Aceitar e entrar no NEXUS</button>
    <p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:10px">Você só precisa aceitar uma vez neste navegador.</p>
  </div>
</div>

<script>
const overlay = document.getElementById('lgpd-overlay');
const check = document.getElementById('checkLgpd');
const btn = document.getElementById('btnAceitar');

// MOSTRA SÓ SE AINDA NÃO ACEITOU
if(!localStorage.getItem('nexus_lgpd_aceito')){
  overlay.classList.add('show');
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
});
</script>
</body>
</html>