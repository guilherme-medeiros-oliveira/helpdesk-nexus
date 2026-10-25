<?php
include 'config.php';
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$user = $_SESSION['user'];
$user_id = $user['id'];
$tipo = $user['tipo'];

if (isset($_GET['delete']) && $tipo == 'admin') {
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM chamado_mensagens WHERE chamado_id = $del_id");
    $conn->query("DELETE FROM chamados WHERE id = $del_id");
    header("Location: dashboard.php"); exit;
}

$stmt = $conn->prepare("SELECT c.*, u.nome as nome_usuario FROM chamados c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows==0) die("Chamado não encontrado");
$ch = $res->fetch_assoc();

if ($tipo != 'admin' && $ch['usuario_id'] != $user_id) {
    die("Sem permissão");
}

if (isset($_POST['enviar_msg'])) {
    $msg = trim($_POST['mensagem']);
    if ($msg != '') {
        $ins = $conn->prepare("INSERT INTO chamado_mensagens (chamado_id, usuario_id, mensagem) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $id, $user_id, $msg);
        $ins->execute();
        header("Location: ver_chamado.php?id=$id"); exit;
    }
}

if(isset($_POST['responder']) && $tipo=='admin'){
    $status = $_POST['status'];
    $up = $conn->prepare("UPDATE chamados SET status=? WHERE id=?");
    $up->bind_param("si", $status, $id);
    $up->execute();
    header("Location: ver_chamado.php?id=$id"); exit;
}

$msgs = $conn->prepare("SELECT m.*, u.nome, u.tipo FROM chamado_mensagens m JOIN usuarios u ON m.usuario_id = u.id WHERE m.chamado_id = ? ORDER BY m.data_envio ASC");
$msgs->bind_param("i", $id);
$msgs->execute();
$mensagens = $msgs->get_result();

$prio = strtolower($ch['prioridade']);
$statusRaw = $ch['status'];
$statusClass = strtolower(str_replace(' ', '_', $statusRaw));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Chamado #<?= $id ?> | NEXUS</title>
<link rel="stylesheet" href="style.css">
<style>
.detail-grid{display:grid;grid-template-columns:1.4fr .8fr;gap:16px}
.chat-wrap{max-height:520px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:14px;padding:14px;background:#f8fafc;display:flex;flex-direction:column;gap:10px}
.msg{padding:12px 14px;border-radius:16px;max-width:82%;line-height:1.4;font-size:14px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.msg.admin{background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;margin-left:auto;border-bottom-right-radius:6px}
.msg.usuario{background:#fff;border:1px solid #e2e8f0;border-bottom-left-radius:6px}
.msg small{display:block;font-size:11px;opacity:.75;margin-top:6px}
.form-chat{display:flex;gap:10px;margin-top:14px}
.form-chat textarea{flex:1;resize:none;min-height:52px}
.btn-danger{background:#ef4444 !important}
.meta-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;font-size:13px;color:#475569}
@media(max-width:800px){.detail-grid{grid-template-columns:1fr}}
.cliente-only{max-width:800px;margin:0 auto;width:100%}
</style>
</head>
<body>
<header class="hd-header">
  <div class="hd-brand">
    <div class="hd-logo">N</div>
    <div><strong>NEXUS <span>- HelpDesk</span></strong><small>Ponto de Conexão • Suporte Profissional</small></div>
  </div>
  <nav class="hd-nav">
    <span>Ticket #<?= $id ?></span>
    <a href="dashboard.php">Painel</a>
    <a href="novo_chamado.php">Novo</a>
    <a href="logout.php">Sair</a>
  </nav>
</header>

<main class="hd-container">

<?php if($tipo == 'admin'): ?>
  <div class="detail-grid">
    <div class="hd-card">
      <div class="hd-card-header" style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
          <h2 style="margin:0">#<?= $ch['id'] ?> - <?= htmlspecialchars($ch['titulo']) ?></h2>
          <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
            <span class="hd-badge <?= $statusClass=='aberto'?'badge-aberto':($statusClass=='fechado'?'badge-fechado':'badge-aberto') ?>"><?= htmlspecialchars($ch['status']) ?></span>
            <span class="hd-badge <?= $prio=='alta'?'badge-alta':($prio=='media'?'badge-media':'badge-baixa') ?>"><?= htmlspecialchars($ch['prioridade']) ?></span>
            <span class="hd-badge" style="background:#f1f5f9;color:#334155"><?= htmlspecialchars($ch['categoria']) ?></span>
          </div>
        </div>
        <a class="hd-btn-primary btn-danger" href="ver_chamado.php?id=<?= $id ?>&delete=<?= $id ?>" onclick="return confirm('Excluir chamado?')">Excluir</a>
      </div>
      <div style="padding:20px">
        <div class="meta-box" style="margin-bottom:16px">
          <b>👤 Solicitante:</b> <?= htmlspecialchars($ch['nome_usuario']) ?> | 
          <b>📅 Abertura:</b> <?= date('d/m/Y H:i', strtotime($ch['data_abertura'])) ?> | 
          <b>🛠️ Protocol:</b> NX-<?= str_pad($id,6,'0',STR_PAD_LEFT) ?>
        </div>
        <p style="font-size:14px;color:#334155;white-space:pre-wrap;line-height:1.6"><?= nl2br(htmlspecialchars($ch['descricao'])) ?></p>
        <div style="margin-top:20px;padding-top:18px;border-top:1px solid #e2e8f0">
          <label style="font-weight:700;font-size:13px;display:block;margin-bottom:6px">⚙️ Gerenciamento Admin</label>
          <form method="POST" style="display:flex;gap:10px">
            <select name="status" style="flex:1">
              <option value="Aberto" <?= $ch['status']=='Aberto'?'selected':'' ?>>Aberto</option>
              <option value="Em Andamento" <?= $ch['status']=='Em Andamento'?'selected':'' ?>>Em Andamento</option>
              <option value="Fechado" <?= $ch['status']=='Fechado'?'selected':'' ?>>Fechado</option>
            </select>
            <button name="responder" class="hd-btn-primary">Atualizar</button>
          </form>
        </div>
      </div>
    </div>

    <div class="hd-card" style="display:flex;flex-direction:column">
      <div class="hd-card-header"><h3 style="margin:0">💬 Conversa NEXUS</h3><p>Suporte em tempo real • SLA 4h úteis</p></div>
      <div style="padding:16px;display:flex;flex-direction:column;flex:1">
        <div class="chat-wrap" id="chat">
          <?php if($mensagens->num_rows==0): ?>
            <p style="text-align:center;color:#94a3b8;font-size:13px;padding:30px 10px">Nenhuma mensagem ainda.</p>
          <?php else: while($m=$mensagens->fetch_assoc()): $classe=($m['tipo']=='admin')?'admin':'usuario'; ?>
            <div class="msg <?= $classe ?>"><?= nl2br(htmlspecialchars($m['mensagem'])) ?><small><?= htmlspecialchars($m['nome']) ?> • <?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></small></div>
          <?php endwhile; endif; ?>
        </div>
        <form method="POST" class="form-chat"><textarea name="mensagem" rows="2" placeholder="Digite sua resposta..." required></textarea><button name="enviar_msg" style="min-width:90px">Enviar</button></form>
      </div>
    </div>
  </div>

<?php else: ?>
  <div class="cliente-only">
    <div class="hd-card" style="display:flex;flex-direction:column">
      <div class="hd-card-header">
        <h3 style="margin:0">💬 Conversa NEXUS - Ticket #<?= $id ?></h3>
        <p><?= htmlspecialchars($ch['titulo']) ?> • Suporte em tempo real</p>
      </div>
      <div style="padding:16px;display:flex;flex-direction:column;flex:1">
        <div class="chat-wrap" id="chat">
          <?php if($mensagens->num_rows==0): ?>
            <p style="text-align:center;color:#94a3b8;font-size:13px;padding:30px 10px">Nenhuma mensagem ainda.<br>Aguarde nosso suporte.</p>
          <?php else: while($m=$mensagens->fetch_assoc()): $classe=($m['tipo']=='admin')?'admin':'usuario'; ?>
            <div class="msg <?= $classe ?>"><?= nl2br(htmlspecialchars($m['mensagem'])) ?><small><?= htmlspecialchars($m['nome']) ?> • <?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></small></div>
          <?php endwhile; endif; ?>
        </div>
        <form method="POST" class="form-chat"><textarea name="mensagem" rows="2" placeholder="Digite sua resposta..." required></textarea><button name="enviar_msg" style="min-width:90px">Enviar</button></form>
        <div class="hd-info" style="margin-top:12px">✅ NEXUS: Ponto de conexão entre o problema e a solução</div>
      </div>
    </div>
  </div>
<?php endif; ?>

<a href="dashboard.php" style="display:inline-block;margin-top:18px;color:#64748b;text-decoration:none;font-size:14px">← Voltar ao Painel NEXUS</a>
</main>
<script>document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;</script>
</body>
</html>