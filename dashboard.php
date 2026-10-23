<?php 
include 'config.php'; 
if(!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user'];

// SEGURO: Prepared Statement
$stmt = $conn->prepare("SELECT * FROM chamados WHERE usuario_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$chamados = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>
/* HEADER CONFORTÁVEL */
.top-bar {
  background: white;
  padding: 16px 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  max-width: 1000px;
  margin-left: auto;
  margin-right: auto;
}

.top-bar h2 {
  margin: 0;
  font-size: 20px;
  color: #1a73e8;
}

.top-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.btn-link {
  background: #1a73e8;
  color: white;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
}

.btn-link.secondary {
  background: #f1f3f5;
  color: #333;
}

.btn-link.admin {
  background: #111827;
  color: white;
}

.table-wrapper {
  max-width: 1000px;
  margin: 0 auto;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  overflow: hidden;
  padding: 0;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
}

.status-aberto { background: #e8f5e9; color: #2e7d32; }
.status-pendente { background: #fff3e0; color: #ef6c00; }
.status-fechado { background: #f5f5f5; color: #757575; }
.status-em_andamento { background: #e3f2fd; color: #1565c0; }

.prioridade-alta { color: #d32f2f; font-weight: bold; }
.prioridade-media { color: #f57c00; }
.prioridade-baixa { color: #388e3c; }

@media (max-width: 768px) {
  .top-bar {
    flex-direction: column;
    align-items: stretch;
  }
  .top-bar h2 {
    text-align: center;
  }
  .top-actions {
    justify-content: center;
  }
  .top-actions a {
    flex: 1;
    justify-content: center;
    text-align: center;
  }
}
</style>
</head>
<body>

<div class="top-bar">
  <h2>Olá, <?= htmlspecialchars($user['nome']) ?> 👋</h2>
  <div class="top-actions">
    <a href="abrir_chamado.php" class="btn-link">+ Novo Chamado</a>
    <?php if($user['tipo']=='admin'): ?>
      <a href="admin.php" class="btn-link admin">Painel Admin</a>
    <?php endif; ?>
    <a href="logout.php" class="btn-link secondary">Sair</a>
  </div>
</div>

<div class="table-wrapper">
<table>
<thead>
<tr>
  <th>ID</th>
  <th>Título</th>
  <th>Categoria</th>
  <th>Prioridade</th>
  <th>Status</th>
  <th>Resposta</th>
</tr>
</thead>
<tbody>
<?php while($c = $chamados->fetch_assoc()){ ?>
<tr>
  <td data-label="ID">#<?= (int)$c['id'] ?></td>
  <td data-label="Título"><?= htmlspecialchars($c['titulo']) ?></td>
  <td data-label="Categoria"><?= htmlspecialchars($c['categoria']) ?></td>
  <td data-label="Prioridade"><span class="prioridade-<?= strtolower(htmlspecialchars($c['prioridade'])) ?>"><?= htmlspecialchars($c['prioridade']) ?></span></td>
  <td data-label="Status"><span class="status-badge status-<?= strtolower(str_replace(' ', '_', $c['status'])) ?>"><?= htmlspecialchars($c['status']) ?></span></td>
  <td data-label="Resposta"><?= $c['resposta'] ? htmlspecialchars($c['resposta']) : '<span style="color:#999">-</span>' ?></td>
</tr>
<?php } ?>
<?php if($chamados->num_rows === 0): ?>
<tr>
  <td colspan="6" style="text-align:center; padding: 40px 20px; color: #777;">
    Nenhum chamado ainda.<br>
    <a href="abrir_chamado.php" style="color:#1a73e8; font-weight:bold;">Abrir seu primeiro chamado</a>
  </td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

</body>
</html>