<?php
declare(strict_types=1);

require __DIR__ . '/../base/base_de_dados.php';
$pdo = bd();

switch (metodo()) {
    case 'GET':
        // Lista todas as escolas
        $st = $pdo->query("SELECT * FROM escolas ORDER BY nome");
        sair($st->fetchAll());

    case 'POST':
        // Cria nova escola
        $dados = corpo_json();
        exigir($dados, ['nome','provincia']);
        $st = $pdo->prepare("INSERT INTO escolas(nome, provincia, endereco, telefone, email, website) 
                             VALUES(:nome,:prov,:endereco,:tel,:email,:web)");
        $st->execute([
            ':nome'    => $dados['nome'],
            ':prov'    => $dados['provincia'],
            ':endereco'=> $dados['endereco'] ?? '',
            ':tel'     => $dados['telefone'] ?? '',
            ':email'   => $dados['email'] ?? '',
            ':web'     => $dados['website'] ?? '',
        ]);
        sair(['id' => (int)$pdo->lastInsertId()], 201);

    case 'PUT':
        // Atualiza escola
        $id = (int)query('id', 0);
        if ($id <= 0) sair(['erro'=>'ID inválido'], 400);
        $dados = corpo_json();
        $st = $pdo->prepare("UPDATE escolas 
                             SET nome=:nome, provincia=:provincia, endereco=:endereco, telefone=:tel, email=:email, website=:web
                             WHERE id=:id");
        $st->execute([
            ':id'       => $id,
            ':nome'     => $dados['nome'] ?? '',
            ':provincia'=> $dados['provincia'] ?? '',
            ':endereco' => $dados['endereco'] ?? '',
            ':tel'      => $dados['telefone'] ?? '',
            ':email'    => $dados['email'] ?? '',
            ':web'      => $dados['website'] ?? '',
        ]);
        sair(['estado'=>'ok']);

    case 'DELETE':
        // Remove escola
        $id = (int)query('id', 0);
        if ($id <= 0) sair(['erro'=>'ID inválido'], 400);
        $st = $pdo->prepare("DELETE FROM escolas WHERE id=?");
        $st->execute([$id]);
        sair(['estado'=>'ok']);

    default:
        sair(['erro'=>'Método não suportado'], 405);
}
