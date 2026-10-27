<?php
session_start();
include 'config.php';

$erro = '';
$ok = '';

if(isset($_POST['criar'])){
    $n = trim($_POST['nome']);
    $e = trim($_POST['email']);
    $s_raw = $_POST['senha'];

    if($n == '' || $e == '' || $s_raw == ''){
        $erro = "Preencha todos os campos.";
    } else {

        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $e);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $erro = "Este e-mail já está cadastrado na base NEXUS.";
        } else {
            $s = md5($s_raw); 
            $stmt = $conn->prepare("INSERT INTO usuarios (nome,email,senha,tipo) VALUES (?, ?, ?, 'usuario')");
            $stmt->bind_param("sss", $n, $e, $s);
            if($stmt->execute()){
                header("Location: index.php?cadastrado=1");
                exit;
            } else {
                $erro = "Erro ao criar conta. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Criar Conta | NEXUS - HelpDesk</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="hd-header">
  <div class="hd-brand">
    <div class="hd-logo">N</div>
    <div>
      <strong>NEXUS <span>- HelpDesk</span></strong>
      <small>Ponto de Conexão • Suporte Profissional</small>
    </div>
  </div>
  <nav class="hd-nav">
    <span>Novo no NEXUS?</span>
    <a href="index.php">Fazer Login</a>
    <a href="dashboard.php">Painel</a>
  </nav>
</header>

<main class="hd-container">
  <div class="hd-card" style="max-width:480px;margin:0 auto">
    <div class="hd-card-header" style="text-align:center">
      <div class="hd-logo" style="margin:0 auto 12px">N</div>
      <h3 style="margin:0">Criar conta NEXUS</h3>
      <p>Junte-se ao ponto de conexão entre problema e solução</p>
    </div>

    <form method="POST" class="hd-form">
      <?php if($erro): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:10px;font-size:13px;border:1px solid #fecaca"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div>
        <label for="nome">Nome completo</label>
        <input id="nome" name="nome" placeholder="Ex: Guilherme Silva" required value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
      </div>

      <div>
        <label for="email">E-mail corporativo</label>
        <input id="email" name="email" type="email" placeholder="seu.email@empresa.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>

      <div>
        <label for="senha">Senha de acesso</label>
        <input id="senha" name="senha" type="password" placeholder="Mínimo 6 caracteres" required minlength="6">
      </div>

      <button name="criar" class="hd-btn-primary" style="width:100%;margin-top:6px">Criar minha conta NEXUS →</button>

      <div class="hd-info">
        Ao criar, você concorda com nosso SLA de 4h úteis e atendimento N1/N2
      </div>

      <div style="text-align:center;font-size:13px;color:#64748b">
        Já tem conta? <a href="index.php" style="color:#2563eb;font-weight:700;text-decoration:none">Fazer login</a>
      </div>
    </form>
  </div>

  <div class="hd-services">
    <div><strong>🔒 Seguro</strong><span>Dados criptografados</span></div>
    <div><strong>⚡ Rápido</strong><span>Resposta em 4h</span></div>
    <div><strong>📞 Suporte</strong><span>Humano e técnico</span></div>
    <div><strong>🛡️ NEXUS</strong><span>Protocolo NX</span></div>
  </div>
</main>

<footer class="hd-footer">
  <p><strong>NEXUS - HelpDesk</strong> | NEXUS = Ponto central onde tudo se conecta. Nosso sistema conecta você à solução.</p>
</footer>

</body>
</html>