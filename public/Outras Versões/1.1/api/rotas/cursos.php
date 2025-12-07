<?php
declare(strict_types=1);

require __DIR__ . '/../base/base_de_dados.php';
$pdo = bd();

if (metodo() === 'GET') {
    // Filtros opcionais
    $params = [];
    $where = [];
    if ($prov = query('provincia')) { $where[]="e.provincia LIKE :prov"; $params[':prov']="%$prov%"; }
    if ($area = query('area'))      { $where[]="c.area LIKE :area";     $params[':area']="%$area%"; }
    if ($nivel = query('nivel'))    { $where[]="c.nivel LIKE :nivel";   $params[':nivel']="%$nivel%"; }
    if ($idEscola = query('escola_id')) { $where[]="c.escola_id=:eid"; $params[':eid']=(int)$idEscola; }

    $sql = "SELECT c.*, e.nome AS escola_nome, e.provincia
            FROM cursos c JOIN escolas e ON e.id = c.escola_id";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY e.provincia, e.nome, c.nome";

    $st = $pdo->prepare($sql);
    $st->execute($params);
    sair($st->fetchAll());
}

if (metodo() === 'POST') {
    // Criar curso
    $dados = corpo_json();
    exigir($dados, ['escola_id','nome','area','nivel']);
    $st = $pdo->prepare("INSERT INTO cursos(escola_id, nome, area, nivel, requisitos, vagas) 
                         VALUES(:eid,:nome,:area,:nivel,:req,:vagas)");
    $st->execute([
        ':eid'   => (int)$dados['escola_id'],
        ':nome'  => $dados['nome'],
        ':area'  => $dados['area'],
        ':nivel' => $dados['nivel'],
        ':req'   => $dados['requisitos'] ?? '',
        ':vagas' => (int)($dados['vagas'] ?? 0),
    ]);
    sair(['id'=>(int)$pdo->lastInsertId()], 201);
}

if (metodo() === 'PUT') {
    // Atualizar curso
    $id = (int)query('id', 0);
    if ($id <= 0) sair(['erro'=>'ID inválido'], 400);
    $dados = corpo_json();
    $st = $pdo->prepare("UPDATE cursos 
                         SET escola_id=:eid, nome=:nome, area=:area, nivel=:nivel, requisitos=:req, vagas=:vagas
                         WHERE id=:id");
    $st->execute([
        ':id'    => $id,
        ':eid'   => (int)($dados['escola_id'] ?? 0),
        ':nome'  => $dados['nome'] ?? '',
        ':area'  => $dados['area'] ?? '',
        ':nivel' => $dados['nivel'] ?? '',
        ':req'   => $dados['requisitos'] ?? '',
        ':vagas' => (int)($dados['vagas'] ?? 0),
    ]);
    sair(['estado'=>'ok']);
}

if (metodo() === 'DELETE') {
    // Apagar curso
    $id = (int)query('id', 0);
    if ($id <= 0) sair(['erro'=>'ID inválido'], 400);
    $st = $pdo->prepare("DELETE FROM cursos WHERE id=?");
    $st->execute([$id]);
    sair(['estado'=>'ok']);
}

sair(['erro'=>'Método não suportado'], 405);
