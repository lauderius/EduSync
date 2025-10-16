<?php
include 'db.php';
$id = $_POST['id'];
$name = $_POST['name'];
$province = $_POST['province'];
$city = $_POST['city'];
$address = $_POST['address'];
$requirements = $_POST['requirements'];

if($id){ // Editar
    $stmt = $conn->prepare("UPDATE schools SET name=?, province=?, city=?, address=?, school_requirements=? WHERE id=?");
    $stmt->bind_param("sssssi",$name,$province,$city,$address,$requirements,$id);
    $stmt->execute();
    echo "Escola atualizada com sucesso!";
}else{ // Adicionar
    $stmt = $conn->prepare("INSERT INTO schools (name, province, city, address, school_requirements) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$name,$province,$city,$address,$requirements);
    $stmt->execute();
    echo "Escola adicionada com sucesso!";
}
?>

