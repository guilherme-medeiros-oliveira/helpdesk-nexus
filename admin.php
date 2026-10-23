<?php
include 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['tipo'] != 'admin') { 
  http_response_code(403);
  die("Acesso negado. Logue como admin@helpdesk.com"); 
}

if(isset($_POST['responder'])){
  // SEGURO: Prepared Statement
  $id = (int)$_POST['id'];
  $resp = $_POST['resposta'];
  $status = $_POST['status'];
  
  // Valida status permitido
  $status_permitidos = ['aberto', 'em_atendimento', 'fechado'];
  if (!in_array($status, $status_permitidos)) {
    $status = 'aberto';
  }

  $stmt = $conn->prepare("UPDATE chamados SET resposta = ?, status = ? WHERE id = ?");
  $stmt->bind_param("ssi", $resp, $status, $id);
  $stmt->execute();
}

$todos = $conn->query("SELECT c.*, u.nome as usuario, u.email FROM chamados c JOIN usuarios u ON c.usuario_id = u.id ORDER BY c.id DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Técnico - HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  background: #f4f6f9;
  padding: 20px;
  max-width: 1000px;
  margin: 0 auto;
}

.page-header {
  background: white;
  padding: 20px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.page-header h2 {
  margin: 0;
  color: #111827;
}

.chamado-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  margin-bottom: 20px;
  overflow: hidden;
  border-left: 4px solid #e5e7eb;
  transition: 0.2s;
}

.chamado-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.chamado-card.prioridade-alta { border-left-color: #ef4444; }
.chamado-card.prioridade-media { border-left-color: #f59e0b; }
.chamado-card.prioridade-baixa { border-left-color: #10b981; }

.chamado-header {
  padding: 16px 20px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
  background: #fafafa;
}

.chamado-id {
  font-weight: 800;
  color: #1a73e8;
}

.chamado-meta {
  padding: 16px 20px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px;
  font-size: 14px;
}

.meta-item {
  display: flex;
  flex-direction: column;
}

.meta-label {
  font-size: 11px;
  text-transform: uppercase;
  color: #9ca3af;
  font-weight: 700;
  letter-spacing: 0.5px;
}

.meta-value {
  font-weight: 600;
  color: #111827;
  margin-top: 2px;
}

.descricao-box {
  margin: 0 20px;
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  font-size: 14px;
  line-height: 1.5;
}

.form-resposta {
  padding: 20px;
}

.form-resposta textarea {
  width: 100%;
  min-height: 90px;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-family: inherit;
  font-size: 14px;
  resize: vertical;
}

.form-resposta-row {
  display: flex;
  gap: 12px;
  margin-top: 12px;
  flex-wrap: wrap;
}

.form-resposta-row select {
  flex: 1;
  min-width: 160px;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
}

.form-resposta-row button {
  flex: 1;
  min-width: 160px;
  background: #1a73e8;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  min-height: 44px;
}

.badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: bold;
  text-transform: uppercase;
}

.badge-aberto { background: #dbeafe; color: #1e40af; }
.badge-em_atendimento { background: #fef3c7; color: #92400e; }
.badge-fechado { background: #dcfce7; color: #166534; }

@media (max-width: 768px) {
  body { padding: 12px; }
  .page-header { flex-direction: column; align-items: stretch; text-align: center; }
  .chamado-meta { grid-template-columns: 1fr 1fr; }
  .form-resposta-row { flex-direction: column; }
  .form-resposta-row select, .form-resposta-row button { width: 100%; }
}
</style>
</head>
<body>

<div class="page-header">
  <h2>🛠️ Painel Técnico - <?= $todos->num_rows ?> chamados</h2>
  <div>
    <a href="dashboard.php" style="background:#f3f4f6; padding:10px 16px; border-radius:8px; text-decoration:none; color:#333; font-weight:600;">Voltar</a>
    <a href="logout.php" style="margin-left:8px; color:#ef4444; text-decoration:none; font-weight:600;">Sair</a>
  </div>
</div>

<?php while($c = $todos->fetch_assoc()){ 
  $prioridadeClass = 'prioridade-'.strtolower($c['prioridade']);
?>
<div class="chamado-card <?= $prioridadeClass ?>">
  <div class="chamado-header">
    <div>
      <span class="chamado-id">#<?= (int)$c['id'] ?></span> - 
      <strong><?= htmlspecialchars($c['titulo']) ?></strong>
      <span style="color:#6b7280; font-size:13px;"> por <?= htmlspecialchars($c['usuario']) ?> (<?= htmlspecialchars($c['email']) ?>)</span>
    </div>
    <span class="badge badge-<?= $c['status'] ?>"><?= htmlspecialchars(str_replace('_',' ', $c['status'])) ?></span>
  </div>

  <div class="chamado-meta">
    <div class="meta-item"><span class="meta-label">Categoria</span><span class="meta-value"><?= htmlspecialchars($c['categoria']) ?></span></div>
    <div class="meta-item"><span class="meta-label">Prioridade</span><span class="meta-value"><?= htmlspecialchars($c['prioridade']) ?></span></div>
    <div class="meta-item"><span class="meta-label">Data</span><span class="meta-value"><?= date('d/m/Y H:i', strtotime($c['criado_em'] ?? 'now')) ?></span></div>
    <div class="meta-item"><span class="meta-label">Usuário ID</span><span class="meta-value">#<?= (int)$c['usuario_id'] ?></span></div>
  </div>

  <div class="descricao-box">
    <span class="meta-label">Descrição do usuário:</span><br>
    <?= nl2br(htmlspecialchars($c['descricao'])) ?>
  </div>

  <form method="POST" class="form-resposta">
    <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
    <span class="meta-label">Sua resposta:</span>
    <textarea name="resposta" placeholder="Digite a resposta técnica aqui..."><?= htmlspecialchars($c['resposta'] ?? '') ?></textarea>
    <div class="form-resposta-row">
      <select name="status">
        <option value="aberto" <?= $c['status']=='aberto' ? 'selected' : '' ?>>Aberto</option>
        <option value="em_atendimento" <?= $c['status']=='em_atendimento' ? 'selected' : '' ?>>Em Atendimento</option>
        <option value="fechado" <?= $c['status']=='fechado' ? 'selected' : '' ?>>Fechado</option>
      </select>
      <button name="responder">💾 Salvar Resposta</button>
    </div>
  </form>
</div>

<?php } ?>

<?php if($todos->num_rows === 0): ?>
  <div style="background:white; padding:40px; text-align:center; border-radius:12px; color:#777;">Nenhum chamado no sistema ainda.</div>
<?php endif; ?>

</body>
</html>