<?php
date_default_timezone_set('America/Sao_Paulo');

$host = "sql10.freesqldatabase.com"; 
$db = "sql10839480";
$user = "sql10839480";
$pass = "J7F3FVdIEW";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Erro de conexão: " . $conn->connect_error); }

$conn->set_charset("utf8mb4");

$conn->query("SET time_zone = '-03:00'");
