<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $course = $_POST['course'];
    $period = $_POST['period'];
    $requirements = $_POST['requirements'];

    $sql = "INSERT INTO schools (name, province, city, course, period, requirements) 
            VALUES ('$name', '$province', '$city', '$course', '$period', '$requirements')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Escola cadastrada com sucesso!');window.location.href='cadastro_escola.php';</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>