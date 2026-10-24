<?php
include 'config.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$user = $_SESSION['user'];
$erro = '';

if (isset($_POST['criar'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $categoria = $_POST['categoria'];
    $prioridade = $_POST['prioridade'];
    $usuario_id = $user['id'];
    
    if($titulo == '' || $descricao == '' || $categoria == '' || $prioridade == ''){
        $erro = "Preencha todos os campos obrigatórios do protocolo NEXUS.";
    } else {
        $stmt = $conn->prepare("INSERT INTO chamados (usuario_id, titulo, descricao, categoria, prioridade, status) VALUES (?, ?, ?, ?, ?, 'Aberto')");
        $stmt->bind_param("issss", $usuario_id, $titulo, $descricao, $categoria, $prioridade);
        if ($stmt->execute()) {
            header("Location: dashboard.php?criado=1"); exit;
        } else { 
            $erro = "Erro ao abrir protocolo: " . $conn->error; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Novo Chamado | NEXUS - HelpDesk</title>
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
    <span>Olá, <?= htmlspecialchars($user['nome']) ?></span>
    <a href="dashboard.php">Painel</a>
    <a href="logout.php">Sair</a>
  </nav>
</header>

<main class="hd-container" style="max-width:700px">
  <div class="hd-card">
    <div class="hd-card-header">
      <h2>🎫 Novo Chamado NEXUS</h2>
      <p>Abra um protocolo NX - Nossa equipe responde em até 4h úteis com SLA N1/N2</p>
    </div>

    <form method="POST" class="hd-form">
      <?php if($erro): ?>
        <div style="background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:10px;font-size:13px;border:1px solid #fecaca"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <div>
        <label for="titulo">Título do problema *</label>
        <input id="titulo" name="titulo" placeholder="Ex: PC não liga / Outlook não envia / Impressora offline" required value="<?= isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '' ?>">
      </div>

      <div>
        <label for="descricao">Descrição técnica detalhada *</label>
        <textarea id="descricao" name="descricao" rows="5" placeholder="Descreva: O que aconteceu? Quando começou? Qual mensagem de erro? O que já tentou? Quanto mais detalhes, mais rápido a resolução NEXUS." required><?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '' ?></textarea>
      </div>

      <div class="hd-row">
        <div>
          <label for="categoria">Categoria *</label>
          <select id="categoria" name="categoria" required>
            <option value="">Selecione a categoria</option>
            <option value="Suporte Técnico" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Suporte Técnico')?'selected':'' ?>>🖥️ Suporte Técnico</option>
            <option value="Rede" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Rede')?'selected':'' ?>>🌐 Rede / Wi-Fi / Internet</option>
            <option value="Software" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Software')?'selected':'' ?>>💾 Software / Sistema / ERP</option>
            <option value="Hardware" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Hardware')?'selected':'' ?>>🔧 Hardware / Manutenção</option>
            <option value="Email" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Email')?'selected':'' ?>>📧 E-mail / Office 365</option>
            <option value="Impressora" <?= (isset($_POST['categoria']) && $_POST['categoria']=='Impressora')?'selected':'' ?>>🖨️ Impressora / Scanner</option>
          </select>
        </div>
        <div>
          <label for="prioridade">Prioridade / Impacto *</label>
          <select id="prioridade" name="prioridade" required>
            <option value="">Defina a urgência</option>
            <option value="Baixa" <?= (isset($_POST['prioridade']) && $_POST['prioridade']=='Baixa')?'selected':'' ?>>🟢 Baixa - Dúvida / Melhoria</option>
            <option value="Média" <?= (isset($_POST['prioridade']) && $_POST['prioridade']=='Média')?'selected':'' ?>>🟡 Média - Afeta 1 usuário</option>
            <option value="Alta" <?= (isset($_POST['prioridade']) && $_POST['prioridade']=='Alta')?'selected':'' ?>>🔴 Alta - Setor parado / Crítico</option>
          </select>
        </div>
      </div>

      <button name="criar" class="hd-btn-primary" style="width:100%;padding:15px;font-size:15px">Abrir Protocolo NEXUS →</button>

      <div class="hd-info">
        📋 NEXUS = Ponto de conexão entre o problema e a solução • Protocolo gerado automaticamente como NX-000000
      </div>

      <a href="dashboard.php" style="display:block;text-align:center;font-size:14px;color:#64748b;text-decoration:none">← Voltar ao Painel NEXUS</a>
    </form>
  </div>

  <div class="hd-services">
    <div><strong>🕒 SLA 4h</strong><span>Resposta garantida</span></div>
    <div><strong>👨‍💻 N1/N2</strong><span>Equipe especializada</span></div>
    <div><strong>📱 Remoto</strong><span>AnyDesk / TeamViewer</span></div>
    <div><strong>🏢 Presencial</strong><span>Osasco e região</span></div>
  </div>
</main>

<footer class="hd-footer">
  <p><strong>NEXUS - HelpDesk</strong> | Todo chamado gera um protocolo NX com histórico completo e chat em tempo real</p>
</footer>

</body>
</html>
