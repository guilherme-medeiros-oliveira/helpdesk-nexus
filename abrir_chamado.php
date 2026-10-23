<?php 
include 'config.php'; 
if(!isset($_SESSION['user'])) { header("Location: index.php"); exit; }

$erro = "";
$sucesso = "";

if(isset($_POST['abrir'])){
  $titulo = trim($_POST['titulo']);
  $cat = $_POST['categoria'];
  $pri = $_POST['prioridade'];
  $desc = trim($_POST['descricao']);
  $uid = (int)$_SESSION['user']['id'];

  // Validação simples
  $cats_permitidas = ['Hardware','Software','Rede','Impressora','Email','Acesso','Outro'];
  $pri_permitidas = ['baixa','media','alta'];
  
  if (!in_array($cat, $cats_permitidas)) $cat = 'Outro';
  if (!in_array($pri, $pri_permitidas)) $pri = 'baixa';

  if(strlen($titulo) < 5) {
    $erro = "Título muito curto (mínimo 5 caracteres)";
  } else {
    // SEGURO: Prepared
    $stmt = $conn->prepare("INSERT INTO chamados (usuario_id, titulo, categoria, prioridade, descricao, status, criado_em) VALUES (?, ?, ?, ?, ?, 'aberto', NOW())");
    $stmt->bind_param("issss", $uid, $titulo, $cat, $pri, $desc);
    
    if($stmt->execute()){
      header("Location: dashboard.php?ok=1");
      exit;
    } else {
      $erro = "Erro ao abrir chamado: " . $conn->error;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Abrir Chamado - HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  background: #f4f6f9;
  padding: 16px;
  display: flex;
  justify-content: center;
  min-height: 100vh;
  align-items: flex-start;
}

.form-container {
  background: white;
  padding: 28px 24px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 560px;
  margin-top: 24px;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 12px;
}

.form-header h3 {
  margin: 0;
  font-size: 22px;
  color: #111827;
}

.form-header a {
  background: #f3f4f6;
  padding: 8px 14px;
  border-radius: 8px;
  text-decoration: none;
  color: #374151;
  font-weight: 600;
  font-size: 14px;
}

label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  color: #6b7280;
  letter-spacing: 0.5px;
  margin: 16px 0 6px;
}

input, select, textarea {
  width: 100%;
  padding: 14px 14px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 16px;
  font-family: inherit;
  background: white;
}

input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #1a73e8;
  box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
}

textarea {
  min-height: 120px;
  resize: vertical;
  line-height: 1.5;
}

.row-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

button[type="submit"] {
  width: 100%;
  margin-top: 20px;
  padding: 16px;
  background: #1a73e8;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  min-height: 52px;
  transition: 0.2s;
}

button[type="submit"]:hover {
  background: #0d62d0;
  transform: translateY(-1px);
}

.alert {
  padding: 12px 14px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 14px;
}

.alert-erro { background: #fdecea; color: #991b1b; }
.alert-ok { background: #dcfce7; color: #166534; }

@media (max-width: 600px) {
  .form-container { padding: 20px 16px; margin-top: 12px; border-radius: 12px; }
  .row-2 { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="form-container">
  <div class="form-header">
    <h3>🎫 Abrir Chamado</h3>
    <a href="dashboard.php">← Voltar</a>
  </div>

  <?php if($erro): ?>
    <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label for="titulo">Título do problema</label>
    <input id="titulo" name="titulo" placeholder="Ex: PC sem internet na sala 3" required minlength="5" value="<?= isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '' ?>">

    <div class="row-2">
      <div>
        <label for="categoria">Categoria</label>
        <select id="categoria" name="categoria">
          <option value="Hardware" <?= ($_POST['categoria']??'')=='Hardware'?'selected':'' ?>>Hardware</option>
          <option value="Software" <?= ($_POST['categoria']??'')=='Software'?'selected':'' ?>>Software</option>
          <option value="Rede" <?= ($_POST['categoria']??'')=='Rede'?'selected':'' ?>>Rede</option>
          <option value="Impressora" <?= ($_POST['categoria']??'')=='Impressora'?'selected':'' ?>>Impressora</option>
          <option value="Email" <?= ($_POST['categoria']??'')=='Email'?'selected':'' ?>>Email</option>
          <option value="Acesso" <?= ($_POST['categoria']??'')=='Acesso'?'selected':'' ?>>Acesso</option>
          <option value="Outro" <?= ($_POST['categoria']??'')=='Outro'?'selected':'' ?>>Outro</option>
        </select>
      </div>
      <div>
        <label for="prioridade">Prioridade</label>
        <select id="prioridade" name="prioridade">
          <option value="baixa" <?= ($_POST['prioridade']??'')=='baixa'?'selected':'' ?>>Baixa - Posso esperar</option>
          <option value="media" <?= ($_POST['prioridade']??'')=='media'?'selected':'' ?>>Média - Atrapalha meu trabalho</option>
          <option value="alta" <?= ($_POST['prioridade']??'')=='alta'?'selected':'' ?>>Alta - Urgente / Parado</option>
        </select>
      </div>
    </div>

    <label for="descricao">Descreva o problema</label>
    <textarea id="descricao" name="descricao" placeholder="Ex: Meu PC não conecta na Wi-Fi desde hoje de manhã, já reiniciei mas continua sem internet. Sala 3, PC 02." required><?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '' ?></textarea>

    <button name="abrir" type="submit">Abrir Chamado →</button>
  </form>
</div>

</body>
</html>