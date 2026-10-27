<?php
require_once 'config.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    // Não está logado, manda pro login
    header("Location: index.php");
    exit;
}
// Se chegou aqui, está logado, pode continuar
$user_logado = $_SESSION['user'];