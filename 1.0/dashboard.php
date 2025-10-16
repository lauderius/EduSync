<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

include 'db.php'; // Conexão com banco
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - EduSync</title>
<style>
body { font-family: Arial,sans-serif; background:#f0f2f5; margin:0; }
header { background:#2563eb; color:white; padding:15px 20px; display:flex; justify-content:space-between; align-items:center; }
header h1 { margin:0; font-size:20px; }
nav button { margin-left:10px; padding:8px 15px; border:none; border-radius:8px; cursor:pointer; background:#3b82f6; color:white; transition:0.3s; }
nav button:hover { background:#1d4ed8; }
.container { padding:20px; }
.card { background:white; padding:20px; margin-bottom:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<header>
    <h1>Bem-vindo, <?php echo $_SESSION['username']; ?>!</h1>
    <nav>
        <button onclick="location.href='cadastro_escola.php'">Adicionar Escola</button>
        <button onclick="location.href='get_schools.php'">Ver Escolas</button>
        <button onclick="location.href='edit_course.php'">Gerenciar Cursos</button>
        <button onclick="location.href='update_vacancies.php'">Atualizar Vagas</button>
        <button onclick="location.href='logout.php'">Sair</button>
    </nav>
</header>

<div class="container">
    <div class="card">
        <h2>Dashboard EduSync</h2>
        <p>Escolha uma opção acima para começar a gerenciar escolas, cursos e vagas.</p>
    </div>
</div>

</body>
</html>