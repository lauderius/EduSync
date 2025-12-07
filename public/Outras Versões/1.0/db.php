<?php
$host = "localhost";
$user = "root"; // utilizador padrão do XAMPP
$pass = "";     // senha em branco por padrão
$db   = "edusync_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>