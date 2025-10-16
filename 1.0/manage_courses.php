<?php
session_start();
include 'db.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$cursos = $conn->query("SELECT courses.*, schools.name as school_name FROM courses JOIN schools ON courses.school_id = schools.id ORDER BY courses.course_name ASC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Gerenciar Cursos - EduSync</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f2f5;padding:20px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:10px;text-align:left;}
button{padding:6px 12px;border:none;border-radius:6px;cursor:pointer;margin:2px;color:white;}
.edit{background:#3b82f6;}
.delete{background:#ef4444;}
.update{background:#10b981;}
</style>
</head>
<body>
<h2>Gerenciar Cursos</h2>
<table>
<tr>
<th>Curso</th>
<th>Escola</th>
<th>Vagas</th>
<th>Requisitos</th>
<th>Ações</th>
</tr>
<?php while($curso = $cursos->fetch_assoc()): ?>
<tr>
<td><?php echo $curso['course_name']; ?></td>
<td><?php echo $curso['school_name']; ?></td>
<td><?php echo $curso['vacancies']; ?></td>
<td><?php echo $curso['course_requirements']; ?></td>
<td>
<button class="edit" onclick="location.href='edit_course.php?id=<?php echo $curso['id']; ?>'">Editar</button>
<a href="delete_course.php?id=<?php echo $curso['id']; ?>" onclick="return confirm('Deseja realmente excluir este curso?');"><button class="delete">Eliminar</button></a>
<button class="update" onclick="location.href='update_vagas.php?id=<?php echo $curso['id']; ?>'">Atualizar Vagas</button>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
