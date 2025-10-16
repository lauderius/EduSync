<?php
session_start();
include 'db.php';

// Verifica se admin está logado
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php");
    exit;
}

// Pega ID da escola
if(!isset($_GET['id'])){
    die("ID da escola não fornecido!");
}

$id = intval($_GET['id']);
$error = '';
$success = '';

// Buscar escola
$stmt = $conn->prepare("SELECT * FROM schools WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$school = $result->fetch_assoc();

if(!$school){
    die("Escola não encontrada!");
}

// Processar formulário de atualização
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $requirements = $_POST['school_requirements'];

    $stmt = $conn->prepare("UPDATE schools SET name=?, province=?, city=?, address=?, school_requirements=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $province, $city, $address, $requirements, $id);

    if($stmt->execute()){
        $success = "Escola atualizada com sucesso!";
        $school['name'] = $name;
        $school['province'] = $province;
        $school['city'] = $city;
        $school['address'] = $address;
        $school['school_requirements'] = $requirements;
    } else {
        $error = "Erro ao atualizar: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Escola - EduSync</title>
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
    <h2>Editar Escola</h2>
    <?php if($success) echo '<p class="success">'.$success.'</p>'; ?>
    <?php if($error) echo '<p class="error">'.$error.'</p>'; ?>

    <label>Nome da Escola</label>
    <input type="text" name="name" value="<?= $school['name'] ?>" required>

    <label>Província</label>
    <input type="text" name="province" value="<?= $school['province'] ?>" required>

    <label>Cidade</label>
    <input type="text" name="city" value="<?= $school['city'] ?>" required>

    <label>Morada</label>
    <input type="text" name="address" value="<?= $school['address'] ?>" required>

    <label>Requisitos da Escola</label>
    <textarea name="school_requirements" rows="4"><?= $school['school_requirements'] ?></textarea>

    <button type="submit" name="update">Atualizar Escola</button>
</form>

</body>
</html>
