<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

if (empty($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
    exit;
}

$id = (int) $_POST['id'];

$stmt = $conn->prepare('DELETE FROM courses WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'ok', 'message' => 'Curso excluído com sucesso!']);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Curso não encontrado.']);
}
