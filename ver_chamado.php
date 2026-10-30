<?php
require_once 'config.php'; // config já dá session_start
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$user = $_SESSION['user'];
$user_id = $user['id'];
$tipo = $user['tipo'];

if (isset($_GET['delete']) && $tipo == 'admin') {
    $del_id = intval($_GET['delete']);
    $stmtDel = $conn->prepare("DELETE FROM chamado_mensagens WHERE chamado_id = ?");
    $stmtDel->bind_param("i", $del_id);
    $stmtDel->execute();
    $stmtDel2 = $conn->prepare("DELETE FROM chamados WHERE id = ?");
    $stmtDel2->bind_param("i", $del_id);
    $stmtDel2->execute();
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

// Envio via AJAX - sem reload
if (isset($_POST['enviar_msg_ajax'])) {
    $msg = trim($_POST['mensagem'] ?? '');
    if ($msg != '') {
        $ins = $conn->prepare("INSERT INTO chamado_mensagens (chamado_id, usuario_id, mensagem) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $id, $user_id, $msg);
        $ins->execute();
        $newId = $conn->insert_id;
        // devolve a mensagem real já
        $ret = $conn->prepare("SELECT m.*, u.nome, u.tipo FROM chamado_mensagens m JOIN usuarios u ON m.usuario_id=u.id WHERE m.id=?");
        $ret->bind_param("i", $newId);
        $ret->execute();
        $nova = $ret->get_result()->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($nova);
        exit;
    }
    echo json_encode(['ok'=>false]); exit;
}

// Fallback sem JS
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

$lastMsgId = 0;
$listaMsgs = [];
while($r = $mensagens->fetch_assoc()){
    $listaMsgs[] = $r;
    $lastMsgId = max($lastMsgId, $r['id']);
}

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
.msg{padding:12px 14px;border-radius:16px;max-width:82%;line-height:1.4;font-size:14px;box-shadow:0 1px 4px rgba(0,0,0,.04);animation:fadeIn .2s}
.msg.admin{background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;margin-left:auto;border-bottom-right-radius:6px}
.msg.usuario{background:#fff;border:1px solid #e2e8f0;border-bottom-left-radius:6px}
.msg small{display:block;font-size:11px;opacity:.75;margin-top:6px}
.form-chat{display:flex;gap:10px;margin-top:14px}
.form-chat textarea{flex:1;resize:none;min-height:52px}
.btn-danger{background:#ef4444 !important}
.meta-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;font-size:13px;color:#475569}
@media(max-width:800px){.detail-grid{grid-template-columns:1fr}}
.cliente-only{max-width:800px;margin:0 auto;width:100%}
@keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
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
    <a href="novo_chamado.php">Novo Chamado</a>
    <a href="sobre.php">Sobre</a>
    <a href="termos.php">Termos</a>
    <a href="privacidade.php" class="active">Privacidade</a>
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
          <?php if(empty($listaMsgs)): ?>
            <p id="empty-chat" style="text-align:center;color:#94a3b8;font-size:13px;padding:30px 10px">Nenhuma mensagem ainda.</p>
          <?php else: foreach($listaMsgs as $m): $classe=($m['tipo']=='admin')?'admin':'usuario'; ?>
            <div class="msg <?= $classe ?>" data-id="<?= $m['id'] ?>"><?= nl2br(htmlspecialchars($m['mensagem'])) ?><small><?= htmlspecialchars($m['nome']) ?> • <?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></small></div>
          <?php endforeach; endif; ?>
        </div>
        <form class="form-chat" id="chatForm"><textarea id="mensagemInput" rows="2" placeholder="Digite sua resposta..." required></textarea><button type="submit" style="min-width:90px">Enviar</button></form>
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
          <?php if(empty($listaMsgs)): ?>
            <p id="empty-chat" style="text-align:center;color:#94a3b8;font-size:13px;padding:30px 10px">Nenhuma mensagem ainda.<br>Aguarde nosso suporte.</p>
          <?php else: foreach($listaMsgs as $m): $classe=($m['tipo']=='admin')?'admin':'usuario'; ?>
            <div class="msg <?= $classe ?>" data-id="<?= $m['id'] ?>"><?= nl2br(htmlspecialchars($m['mensagem'])) ?><small><?= htmlspecialchars($m['nome']) ?> • <?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></small></div>
          <?php endforeach; endif; ?>
        </div>
        <form class="form-chat" id="chatForm"><textarea id="mensagemInput" rows="2" placeholder="Digite sua resposta..." required></textarea><button type="submit" style="min-width:90px">Enviar</button></form>
        <div class="hd-info" style="margin-top:12px">✅ NEXUS: Ponto de conexão entre o problema e a solução</div>
      </div>
    </div>
  </div>
<?php endif; ?>

<a href="dashboard.php" style="display:inline-block;margin-top:18px;color:#64748b;text-decoration:none;font-size:14px">← Voltar ao Painel NEXUS</a>
</main>
<script>
const chatDiv = document.getElementById('chat');
const form = document.getElementById('chatForm');
const input = document.getElementById('mensagemInput');
let lastId = <?= $lastMsgId ?>;
chatDiv.scrollTop = chatDiv.scrollHeight;

function escapeHtml(t){ return t.replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function appendMessage(m, isTemp = false){
    if(!isTemp && document.querySelector(`[data-id="${m.id}"]`)) return;
    const empty = document.getElementById('empty-chat');
    if(empty) empty.remove();
    const div = document.createElement('div');
    div.className = 'msg ' + (m.tipo === 'admin' ? 'admin' : 'usuario');
    div.dataset.id = isTemp ? m.tempId : m.id;
    if(isTemp) div.style.opacity = '0.7';
    const data = isTemp ? 'agora' : new Date(m.data_envio).toLocaleString('pt-BR');
    div.innerHTML = `${escapeHtml(m.mensagem).replace(/\n/g,'<br>')}<small>${escapeHtml(m.nome)} • ${data}</small>`;
    chatDiv.appendChild(div);
    chatDiv.scrollTop = chatDiv.scrollHeight;
    return div;
}

async function fetchNew(){
    try{
        const res = await fetch(`chat_fetch.php?id=<?= $id ?>&last_id=${lastId}`);
        if(!res.ok) return;
        const msgs = await res.json();
        msgs.forEach(m => {
            // remove temp com mesmo texto se houver
            document.querySelectorAll('[data-id^="temp-"]').forEach(el => {
                if(el.textContent.includes(m.mensagem.substring(0,20))) el.remove();
            });
            appendMessage(m);
            lastId = Math.max(lastId, parseInt(m.id));
        });
    } catch(e){}
}
setInterval(fetchNew, 2000);

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = input.value.trim();
    if(!msg) return;
    const tempId = 'temp-' + Date.now();
    input.value = '';
    
    // 1. aparece na hora, sem sumir
    appendMessage({mensagem: msg, nome: 'Você', tipo: '<?= $tipo ?>', tempId: tempId}, true);

    const formData = new FormData();
    formData.append('mensagem', msg);
    formData.append('enviar_msg_ajax', '1');
    
    try {
        const res = await fetch(`ver_chamado.php?id=<?= $id ?>`, {method: 'POST', body: formData});
        const novaMsg = await res.json();
        if(novaMsg.id){
            // troca o temp pelo real
            const tempEl = document.querySelector(`[data-id="${tempId}"]`);
            if(tempEl) tempEl.remove();
            appendMessage(novaMsg);
            lastId = Math.max(lastId, parseInt(novaMsg.id));
        }
    } catch(err){
        // se falhar, deixa o temp e tenta de novo
        fetchNew();
    }
    input.focus();
});
</script>
</body>
</html>