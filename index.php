<?php include 'config.php';
if(isset($_POST['login'])){
  $email = $_POST['email']; $senha = md5($_POST['senha']);
  $res = $conn->query("SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'");
  if($res->num_rows > 0){
    $_SESSION['user'] = $res->fetch_assoc();
    header("Location: dashboard.php"); exit;
  } else { $erro = "Login inválido"; }
}
?>
<link rel="stylesheet" href="style.css">
<form method="POST">
  <h2>Login HelpDesk</h2>
  <input name="email" placeholder="Email" required><br>
  <input name="senha" type="password" placeholder="Senha" required><br>
  <button name="login">Entrar</button>
  <a href="registrar.php">Criar conta</a>
</form>