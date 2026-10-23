<?php 
include 'config.php'; 
if(!isset($_SESSION['user'])) { header("Location: index.php"); exit; }
$user = $_SESSION['user'];
$chamados = $conn->query("SELECT * FROM chamados WHERE usuario_id = {$user['id']} ORDER BY id DESC");
?>
<h2>Olá, <?php echo $user['nome']; ?> - Meus Chamados</h2>
<a href="abrir_chamado.php">+ Abrir Novo Chamado</a> | 
<?php if($user['tipo']=='admin') echo '<a href="admin.php"><b>Painel Admin</b></a> | '; ?>
<a href="logout.php">Sair</a>
<hr>
<table border="1" cellpadding="8">
<tr><th>ID</th><th>Título</th><th>Categoria</th><th>Prioridade</th><th>Status</th><th>Resposta</th></tr>
<?php while($c = $chamados->fetch_assoc()){ ?>
<tr>
<td>#<?php echo $c['id']; ?></td>
<td><?php echo $c['titulo']; ?></td>
<td><?php echo $c['categoria']; ?></td>
<td><?php echo $c['prioridade']; ?></td>
<td><b><?php echo $c['status']; ?></b></td>
<td><?php echo $c['resposta'] ? $c['resposta'] : '-'; ?></td>
</tr>
<?php } ?>
</table>
 <link rel="stylesheet" href="style.css">