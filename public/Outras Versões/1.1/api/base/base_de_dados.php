<?php
declare(strict_types=1);

/**
 * Retorna a instância PDO da base de dados.
 */
function bd(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $cfg = require __DIR__ . '/configuracao.php';
        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['bd']};charset={$cfg['charset']}";

        try {
            $pdo = new PDO($dsn, $cfg['usuario'], $cfg['senha']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Mensagem clara de erro de conexão
            exit(json_encode([
                'erro' => 'Erro na conexão com a base de dados',
                'detalhes' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
    }

    return $pdo;
}

/**
 * Retorna o método HTTP da requisição
 */
function metodo(): string {
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
}

/**
 * Pega um parâmetro GET ou retorna valor default
 */
function query(string $k, $def = null) {
    return $_GET[$k] ?? $def;
}

/**
 * Lê o corpo JSON da requisição
 */
function corpo_json(): array {
    return json_decode(file_get_contents('php://input') ?: '[]', true) ?? [];
}

/**
 * Envia resposta JSON e finaliza execução
 */
function sair($data, int $code = 200): void {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Exige que determinados campos estejam presentes no array
 */
function exigir(array $d, array $campos): void {
    foreach ($campos as $c) {
        if (!isset($d[$c]) || $d[$c] === '') {
            sair(['erro' => "Campo obrigatório: $c"], 400);
        }
    }
}

