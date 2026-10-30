<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$user = $_SESSION['user'];
$tipo = $user['tipo'];

if ($tipo === 'admin') {
    $sql = "SELECT c.*, u.nome as nome_usuario FROM chamados c LEFT JOIN usuarios u ON c.usuario_id = u.id ORDER BY c.id DESC";
    $res = $conn->query($sql);
    $titulo = "Painel HelpDesk - Admin";
} else {
    $stmt = $conn->prepare("SELECT * FROM chamados WHERE usuario_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $titulo = "Painel HelpDesk - Meus Chamados";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $titulo ?> | NEXUS - HelpDesk</title>
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
    <span>Olá, <?= htmlspecialchars($user['nome']) ?> (<?= $tipo ?>)</span>
    <a href="novo_chamado.php">Abrir Chamado</a>
    <a href="sobre.php">Sobre</a>
    <a href="termos.php">Termos</a>
    <a href="privacidade.php">Privacidade</a>
    <a href="logout.php">Sair</a>
  </nav>
</header>

<main class="hd-container">
  <div class="hd-card hd-mb">
    <div class="hd-card-header is-split">
      <div>
        <h3><?= $titulo ?></h3>
        <p>NEXUS = Ponto de conexão entre o problema e a solução | Total: <?= $res->num_rows ?> | SLA 4h</p>
      </div>
      <a href="novo_chamado.php" class="hd-btn-primary">+ Abrir Chamado</a>
    </div>
  </div>

  <?php if($res->num_rows==0): ?>
    <div class="hd-card"><div class="hd-empty"><h3>Nenhum chamado</h3><p>Clique em Novo Chamado para solicitar suporte.</p></div></div>
  <?php else: ?>
    <div class="hd-ticket-list">
    <?php while($c=$res->fetch_assoc()): 
      $prio = strtolower($c['prioridade']);
      $statusClass = strtolower(str_replace(' ', '_', $c['status']));
    ?>
      <div class="hd-card">
        <div class="hd-ticket">
          <div class="hd-ticket-body">
            <div class="hd-ticket-top">
              <strong>#<?= $c['id'] ?> - <?= htmlspecialchars($c['titulo']) ?></strong>
              <span class="hd-badge <?= $statusClass == 'aberto' ? 'badge-aberto' : 'badge-fechado' ?>"><?= htmlspecialchars($c['status']) ?></span>
              <span class="hd-badge <?= $prio == 'alta' ? 'badge-alta' : ($prio == 'media' ? 'badge-media' : 'badge-baixa') ?>"><?= htmlspecialchars($c['prioridade']) ?></span>
            </div>
            <p><?= htmlspecialchars(substr($c['descricao'],0,110)) ?>...</p>
            <small>
              <?= htmlspecialchars($c['categoria']) ?> ·
              <?php if($tipo==='admin'): ?><?= htmlspecialchars($c['nome_usuario']) ?> · <?php endif; ?>
              <?= htmlspecialchars($c['data_abertura']) ?>
            </small>
          </div>
          <div class="hd-ticket-actions">
            <a href="ver_chamado.php?id=<?= $c['id'] ?>" class="hd-btn-primary">Ver detalhes →</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
    </div>
  <?php endif; ?>

  <div class="hd-services">
    <div><strong>Hardware</strong><span>Formatação e manutenção</span></div>
    <div><strong>Redes</strong><span>Wi-Fi e cabeamento</span></div>
    <div><strong>Impressoras</strong><span>Instalação e driver</span></div>
    <div><strong>Sistemas</strong><span>Office, Email, ERP</span></div>
  </div>
</main>

<footer class="hd-footer">
  <p><strong>NEXUS - HelpDesk</strong> | Ponto de conexão entre o problema e a solução | Osasco e Região • Remoto e Presencial • SLA Garantido</p>
  <p>NEXUS vem do latim: ponto central onde tudo se conecta. Nosso suporte conecta o usuário à resolução.</p>
</footer>

</body>
</html>