<?php include 'config.php'; if(!isset($_SESSION['user'])) header("Location: index.php");
if(isset($_POST['abrir'])){
 $titulo=$conn->real_escape_string($_POST['titulo']);
 $cat=$_POST['categoria']; $pri=$_POST['prioridade']; $desc=$conn->real_escape_string($_POST['descricao']);
 $uid=$_SESSION['user']['id'];
 $conn->query("INSERT INTO chamados (usuario_id,titulo,categoria,prioridade,descricao) VALUES ('$uid','$titulo','$cat','$pri','$desc')");
 header("Location: dashboard.php");
}
?>
<form method="POST"><h3>Abrir Chamado</h3>
<input name="titulo" placeholder="Ex: PC sem internet" required><br>
<select name="categoria"><option>Hardware</option><option>Software</option><option>Rede</option><option>Impressora</option><option>Email</option></select>
<select name="prioridade"><option value="baixa">Baixa</option><option value="media">Média</option><option value="alta">Alta</option></select><br>
<textarea name="descricao" placeholder="Descreva o problema" required></textarea><br>
<button name="abrir">Abrir</button></form>
<link rel="stylesheet" href="style.css">