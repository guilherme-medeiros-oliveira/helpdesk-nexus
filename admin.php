<?php
include 'config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['tipo'] != 'admin') { die("Acesso negado. Logue como admin@helpdesk.com"); }

if(isset($_POST['responder'])){
  $id = $_POST['id']; $resp = $conn->real_escape_string($_POST['resposta']); $status = $_POST['status'];
  $conn->query("UPDATE chamados SET resposta='$resp', status='$status' WHERE id='$id'");
}

$todos = $conn->query("SELECT c.*, u.nome as usuario FROM chamados c JOIN usuarios u ON c.usuario_id = u.id ORDER BY c.id DESC");
?>
<h2>Painel Help Desk - Técnico</h2>
<a href="dashboard.php">Voltar</a> | <a href="logout.php">Sair</a>
<hr>
<?php while($c = $todos->fetch_assoc()){ ?>
<div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
  <b>#<?php echo $c['id']; ?> - <?php echo $c['titulo']; ?></b> (<?php echo $c['usuario']; ?>) - <?php echo $c['status']; ?><br>
  Categoria: <?php echo $c['categoria']; ?> | Prioridade: <?php echo $c['prioridade']; ?><br>
  Descrição: <?php echo $c['descricao']; ?><br><br>
  <form method="POST">
    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
    <textarea name="resposta" placeholder="Resposta do técnico"><?php echo $c['resposta']; ?></textarea><br>
    <select name="status">
      <option <?php if($c['status']=='aberto') echo 'selected'; ?> value="aberto">Aberto</option>
      <option <?php if($c['status']=='em_atendimento') echo 'selected'; ?> value="em_atendimento">Em Atendimento</option>
      <option <?php if($c['status']=='fechado') echo 'selected'; ?> value="fechado">Fechado</option>
    </select>
    <button name="responder">Salvar Resposta</button>
  </form>
</div>
<link rel="stylesheet" href="style.css">
<?php } ?>