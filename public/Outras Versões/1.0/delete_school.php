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

$conn->begin_transaction();

try {
    // Apaga cursos vinculados (funciona mesmo que o FK não tenha ON DELETE CASCADE)
    $stmt1 = $conn->prepare('DELETE FROM courses WHERE school_id = ?');
    $stmt1->bind_param('i', $id);
    $stmt1->execute();

    // Apaga a escola
    $stmt2 = $conn->prepare('DELETE FROM schools WHERE id = ?');
    $stmt2->bind_param('i', $id);
    $stmt2->execute();

    if ($stmt2->affected_rows === 0) {
        throw new Exception('Escola não encontrada.');
    }

    $conn->commit();
    echo json_encode(['status' => 'ok', 'message' => 'Escola excluída com sucesso!']);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir escola: '.$e->getMessage()]);
}
