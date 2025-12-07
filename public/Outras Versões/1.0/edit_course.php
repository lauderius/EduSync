<?php
session_start();
include 'db.php';

// Verifica se admin está logado
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php");
    exit;
}

// Pega ID do curso
if(!isset($_GET['id'])){
    die("ID do curso não fornecido!");
}

$id = intval($_GET['id']);
$error = '';
$success = '';

// Buscar curso
$stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if(!$course){
    die("Curso não encontrado!");
}

// Processar formulário de atualização
if(isset($_POST['update'])){
    $course_name = $_POST['course_name'];
    $vacancies = intval($_POST['vacancies']);
    $requirements = $_POST['course_requirements'];

    $stmt = $conn->prepare("UPDATE courses SET course_name=?, vacancies=?, course_requirements=? WHERE id=?");
    $stmt->bind_param("sisi", $course_name, $vacancies, $requirements, $id);

    if($stmt->execute()){
        $success = "Curso atualizado com sucesso!";
        // Atualizar variáveis para mostrar no formulário
        $course['course_name'] = $course_name;
        $course['vacancies'] = $vacancies;
        $course['course_requirements'] = $requirements;
    } else {
        $error = "Erro ao atualizar: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Curso - EduSync</title>
<style>
body { font-family: Arial; background:#f4f6f8; display:flex; justify-content:center; align-items:center; height:100vh; }
form { background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); width:400px; }
input, textarea { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
button { width:100%; padding:10px; border:none; border-radius:6px; background:#007bff; color:#fff; font-weight:bold; cursor:pointer; }
.success { color:green; }
.error { color:red; }
</style>
</head>
<body>

<form method="POST">
    <h2>Editar Curso</h2>
    <?php if($success) echo '<p class="success">'.$success.'</p>'; ?>
    <?php if($error) echo '<p class="error">'.$error.'</p>'; ?>
    <label>Nome do Curso</label>
    <input type="text" name="course_name" value="<?= $course['course_name'] ?>" required>

    <label>Vagas</label>
    <input type="number" name="vacancies" value="<?= $course['vacancies'] ?>" required>

    <label>Requisitos</label>
    <textarea name="course_requirements" rows="4" required><?= $course['course_requirements'] ?></textarea>

    <button type="submit" name="update">Atualizar Curso</button>
</form>

</body>
</html>
