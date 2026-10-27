<?php
date_default_timezone_set('America/Sao_Paulo');

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 86400);
    ini_set('session.cookie_lifetime', 86400);
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'samesite' => 'Lax',
        'secure' => false,
        'httponly' => true
    ]);
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "sql10.freesqldatabase.com";
$user_db = "sql10839480";
$pass_db = "J7F3FVdIEW";
$dbname = "sql10839480";

try {
    $conn = new mysqli($host, $user_db, $pass_db, $dbname);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Não derruba o site, só mostra log
    error_log("Erro DB: " . $e->getMessage());
    $conn = null;
    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'chat_fetch.php') === false) {
        die("Erro ao conectar no banco. O host InfinityFree não aceita conexão do Vercel. Veja abaixo.");
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'DB offline']);
        exit;
    }
}