<?php
session_start();
ob_start();

date_default_timezone_set('America/Sao_Paulo');

$host = "sql306.infinityfree.com"; 
$db = "nexus_db";
$user = "if0_43249033";
$pass = "Gemrtkls9x8e";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Erro de conexão: " . $conn->connect_error); }

$conn->set_charset("utf8mb4");

$conn->query("SET time_zone = '-03:00'");
