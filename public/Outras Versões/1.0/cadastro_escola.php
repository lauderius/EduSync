<?php
include 'db.php';

// Processamento do formulário
if(isset($_POST['save'])){
    // Dados da escola
    $name = $_POST['school_name'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $requirements = $_POST['school_requirements'];

    // Inserir escola
    $stmt = $conn->prepare("INSERT INTO schools (name, province, city, address, school_requirements) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $name, $province, $city, $address, $requirements);
    $stmt->execute();
    $school_id = $stmt->insert_id;

    // Inserir cursos múltiplos
    if(isset($_POST['course_name']) && is_array($_POST['course_name'])){
        foreach($_POST['course_name'] as $index => $course_name){
            $vacancies = $_POST['course_vacancies'][$index];
            $course_req = $_POST['course_requirements'][$index];
            $stmt2 = $conn->prepare("INSERT INTO courses (school_id, course_name, vacancies, course_requirements) VALUES (?,?,?,?)");
            $stmt2->bind_param("isis", $school_id, $course_name, $vacancies, $course_req);
            $stmt2->execute();
        }
    }

    // Redirecionar para Dashboard
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Cadastro Escola - EduSync</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial; background: #f4f6f8; margin:0; padding:0; }
header { background:#007bff; color:#fff; padding:15px 30px; text-align:center; font-size:24px; }
.container { max-width:800px; margin:30px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1);}
h2,h3{color:#007bff;}
input, textarea { width:100%; padding:10px; margin:5px 0 15px 0; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;}
button { padding:10px 20px; border:none; border-radius:6px; cursor:pointer; margin-top:10px;}
button.save { background:#28a745; color:#fff; font-weight:bold; }
button.add-course { background:#007bff; color:#fff; font-weight:bold; }
.course-item { background:#f9f9f9; padding:15px; margin-bottom:10px; border-radius:8px; position:relative; }
.course-item i { position:absolute; right:10px; top:10px; cursor:pointer; color:#dc3545; }
</style>
</head>
<body>
<header>EduSync - Cadastro de Escola</header>
<div class="container">
<form method="POST">
    <h2>Informações da Escola</h2>
    <input type="text" name="school_name" placeholder="Nome da Escola" required>
    <input type="text" name="province" placeholder="Província" required>
    <input type="text" name="city" placeholder="Cidade" required>
    <input type="text" name="address" placeholder="Morada" required>
    <textarea name="school_requirements" placeholder="Requisitos da Escola"></textarea>

    <h3>Cursos</h3>
    <div id="courses-container">
        <div class="course-item">
            <input type="text" name="course_name[]" placeholder="Nome do Curso" required>
            <input type="number" name="course_vacancies[]" placeholder="Número de Vagas" required>
            <input type="text" name="course_requirements[]" placeholder="Requisitos do Curso">
            <i class="fas fa-trash" onclick="removeCourse(this)"></i>
        </div>
    </div>
    <button type="button" class="add-course" onclick="addCourse()">+ Adicionar Outro Curso</button>
    <button type="submit" name="save" class="save">Salvar Escola e Cursos</button>
</form>
</div>

<script>
function addCourse(){
    let container = document.getElementById('courses-container');
    let div = document.createElement('div');
    div.classList.add('course-item');
    div.innerHTML = `
        <input type="text" name="course_name[]" placeholder="Nome do Curso" required>
        <input type="number" name="course_vacancies[]" placeholder="Número de Vagas" required>
        <input type="text" name="course_requirements[]" placeholder="Requisitos do Curso">
        <i class="fas fa-trash" onclick="removeCourse(this)"></i>
    `;
    container.appendChild(div);
}

function removeCourse(element){
    element.parentElement.remove();
}
</script>
</body>
</html>