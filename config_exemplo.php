<?php
$host = ""; 
$db = "";
$user = "";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Erro: " . $conn->connect_error); }
session_start();
?>