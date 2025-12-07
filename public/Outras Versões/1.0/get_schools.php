<?php
session_start();
include 'db.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$schools = $conn->query("SELECT * FROM schools ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Ver Escolas - EduSync</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f2f5;padding:20px;}
.card{background:white;padding:20px;margin-bottom:20px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
button{padding:6px 12px;border:none;border-radius:6px;cursor:pointer;margin:2px;color:white;}
.edit{background:#3b82f6;}
.delete{background:#ef4444;}
.add{background:#10b981;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{border:1px solid #ccc;padding:8px;text-align:left;}
</style>
</head>
<body>
<h2>Escolas Cadastradas</h2>
<?php while($school = $schools->fetch_assoc()): ?>
<div class="card">
<h3><?php echo $school['name']; ?> (<?php echo $school['province']; ?>)</h3>
<p>Morada: <?php echo $school['address']; ?></p>
<p>Requisitos da Escola: <?php echo $school['school_requiremens']; ?></p>

<button class="edit" onclick="location.href='edit_school.php?id=<?php echo $school['id']; ?>'">Editar Escola</button>
<a href="delete_school.php?id=<?php echo $school['id']; ?>" onclick="return confirm('Deseja realmente excluir esta escola?');"><button class="delete">Eliminar Escola</button></a>
<button class="add" onclick="location.href='adicionar_curso.php?school_id=<?php echo $school['id']; ?>'">Adicionar Curso</button>

<h4>Cursos:</h4>
<table>
<tr><th>Nome</th><th>Vagas</th><th>Requisitos</th><th>Ações</th></tr>
<?php
$cursos = $conn->query("SELECT * FROM courses WHERE school_id = ".$school['id']);
while($curso = $cursos->fetch_assoc()):
?>
<tr>
<td><?php echo $curso['course_name']; ?></td>
<td><?php echo $curso['vacancies']; ?></td>
<td><?php echo $curso['course_requirements']; ?></td>
<td>
<button class="edit" onclick="location.href='edit_course.php?id=<?php echo $curso['id']; ?>'">Editar</button>
<a href="delete_course.php?id=<?php echo $curso['id']; ?>" onclick="return confirm('Deseja realmente excluir este curso?');"><button class="delete">Eliminar</button></a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
<?php endwhile; ?>
</body>
</html>