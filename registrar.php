<?php include 'config.php';
if(isset($_POST['criar'])){ $n=$_POST['nome']; $e=$_POST['email']; $s=md5($_POST['senha']); $conn->query("INSERT INTO usuarios (nome,email,senha) VALUES ('$n','$e','$s')"); header("Location: index.php"); }
?>
<link rel="stylesheet" href="style.css">
<form method="POST"><h3>Criar Conta</h3><input name="nome" placeholder="Nome" required><br><input name="email" placeholder="Email" required><br><input name="senha" type="password" placeholder="Senha" required><br><button name="criar">Criar</button></form>