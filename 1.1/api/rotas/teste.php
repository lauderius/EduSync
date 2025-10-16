<?php
declare(strict_types=1);

require __DIR__ . '/../base/base_de_dados.php';
$pdo = bd();

$texto = trim(query('texto',''));
if ($texto === '') sair(['erro'=>'Forneça o parâmetro texto'],400);

// Lista oficial de províncias
$provincias = ['Bengo','Benguela','Bié','Cabinda','Cuando Cubango','Cuanza Norte','Cuanza Sul','Cunene',
               'Huambo','Huíla','Luanda','Lunda Norte','Lunda Sul','Malanje','Moxico','Namibe','Uíge','Zaire'];

$provincia = null;
foreach ($provincias as $p) {
    if (stripos($texto, $p)!==false) { $provincia = $p; break; }
}

// Níveis possíveis
$mapaNiveis = [
    'técnico'=>'Técnico','tecnico'=>'Técnico',
    'superior'=>'Superior',
    'profissional'=>'Profissionalizante','profissionalizante'=>'Profissionalizante'
];
$nivel = null;
foreach ($mapaNiveis as $ch=>$val) {
    if (stripos($texto,$ch)!==false) { $nivel = $val; break; }
}

// Extrair área/curso por palavras-chave (simplificado)
$tokens = preg_split('/[\s,.;:!?]+/u', mb_strtolower($texto));
$ignorar = ['quero','saber','cursos','curso','escolas','em','de','da','do','das','dos','para'];
$palavras = array_diff($tokens,$ignorar);
$possivelArea = count($palavras) ? ucfirst(reset($palavras)) : null;

// Montar query
$params = [];
$where = [];
if ($provincia) { $where[]="e.provincia=:prov"; $params[':prov']=$provincia; }
if ($nivel)     { $where[]="c.nivel=:niv";     $params[':niv']=$nivel; }
if ($possivelArea) { $where[]="(c.nome LIKE :area OR c.area LIKE :area)"; $params[':area']="%$possivelArea%"; }

$sql = "SELECT c.*, e.nome AS escola_nome, e.provincia
        FROM cursos c JOIN escolas e ON e.id=c.escola_id";
if ($where) $sql.=" WHERE ".implode(" AND ",$where);
$sql.=" ORDER BY e.provincia,e.nome,c.nome";

$st = $pdo->prepare($sql);
$st->execute($params);
$resultados = $st->fetchAll();

if (!$resultados) {
    sair(['mensagem'=>'Não foram encontrados cursos para esta pesquisa. Tente outra formulação.']);
}

sair($resultados);
