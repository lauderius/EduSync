<?php
declare(strict_types=1);
require __DIR__.'/base/base_de_dados.php';
$pdo = bd();

foreach (['criar_tabelas.sql','indices.sql','dados_iniciais.sql'] as $ficheiro) {
  $sql = file_get_contents(__DIR__."/sql/$ficheiro");
  if ($sql === false) { echo "Falha ao ler $ficheiro\n"; exit(1); }
  $pdo->exec($sql);
}
echo "Migração concluída com sucesso.\n";
