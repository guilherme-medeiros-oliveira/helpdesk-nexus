<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user'])) { http_response_code(403); exit; }

$id = intval($_GET['id'] ?? 0);
$last_id = intval($_GET['last_id'] ?? 0);

$user = $_SESSION['user'];
$ch = $conn->query("SELECT usuario_id FROM chamados WHERE id=$id")->fetch_assoc();
if(!$ch) exit;
if($user['tipo'] != 'admin' && $ch['usuario_id'] != $user['id']) exit;

if($last_id > 0){
    $stmt = $conn->prepare("SELECT m.*, u.nome, u.tipo FROM chamado_mensagens m JOIN usuarios u ON m.usuario_id=u.id WHERE m.chamado_id=? AND m.id>? ORDER BY m.data_envio ASC");
    $stmt->bind_param("ii", $id, $last_id);
} else {
    $stmt = $conn->prepare("SELECT m.*, u.nome, u.tipo FROM chamado_mensagens m JOIN usuarios u ON m.usuario_id=u.id WHERE m.chamado_id=? ORDER BY m.data_envio ASC");
    $stmt->bind_param("i", $id);
}
$stmt->execute();
$res = $stmt->get_result();
$msgs = [];
while($r = $res->fetch_assoc()){
    $msgs[] = $r;
}
header('Content-Type: application/json');
echo json_encode($msgs);