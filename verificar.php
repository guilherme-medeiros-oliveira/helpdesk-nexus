<?php
session_start();
include 'config.php';
echo "<pre>";
print_r($_SESSION['user']);
echo "</pre>";

$res = $conn->query("SELECT id, nome, email, tipo, LENGTH(senha) as tamanho_senha FROM usuarios");
while($u = $res->fetch_assoc()){
  echo $u['id']." - ".$u['email']." - tipo: ".$u['tipo']." - senha tamanho: ".$u['tamanho_senha']."<br>";
}
?>