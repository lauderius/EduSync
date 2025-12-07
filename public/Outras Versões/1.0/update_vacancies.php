<?php
session_start();
include 'db.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $vagas = intval($_POST['vacancies']);
        $stmt = $conn->prepare("UPDATE courses SET vacancies=? WHERE id=?");
        $stmt->bind_param("ii",$vagas,$id);
        $stmt->execute();
        header("Location: gerenciar_cursos.php");
        exit();
    }
    $curso = $conn->query("SELECT * FROM courses WHERE id=$id")->fetch_assoc();
} else {
    header("Location: gerenciar_cursos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Atualizar Vagas - EduSync</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f2f5;display:flex;justify-content:center;align-items:center;height:100vh;}
.form-container{background:white;padding:30px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.15);width:300px;text-align:center;}
input{width:100%;padding:10px;margin:10px 0;border-radius:8px;border:1px solid #ccc;}
button{padding:10px 20px;border:none;border-radius:8px;background:#10b981;color:white;cursor:pointer;}
button:hover{background:#059669;}
</style>
</head>
<body>
<div class="form-container">
<h2>Atualizar Vagas: <?php echo $curso['course_name']; ?></h2>
<form method="POST">
<input type="number" name="vacancies" value="<?php echo $curso['vacancies']; ?>" min="0" required>
<button type="submit">Atualizar</button>
</form>
</div>
</body>
</html>
