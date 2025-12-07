<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../base/base_de_dados.php';

$texto = $_GET['texto'] ?? '';

if ($texto === '') {
    echo json_encode(["erro" => "Parâmetro 'texto' em falta"]);
    exit;
}

$pdo = bd();

// Exemplo muito simples: procura por cursos com LIKE no nome
$sql = "SELECT c.id, c.nome AS curso, c.area, c.nivel, e.nome AS escola, e.provincia
        FROM cursos c
        JOIN escolas e ON e.id = c.escola_id
        WHERE c.nome LIKE :texto OR c.area LIKE :texto OR e.provincia LIKE :texto
        ORDER BY e.provincia, e.nome, c.nome";

$stmt = $pdo->prepare($sql);
$stmt->execute([":texto" => "%$texto%"]);
$resultados = $stmt->fetchAll();

echo json_encode([
    "query" => $texto,
    "total" => count($resultados),
    "resultados" => $resultados
], JSON_UNESCAPED_UNICODE);
