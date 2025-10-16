<?php
include 'db.php'; // conexão MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $school_name = $_POST['school_name'];
    $school_requirements = $_POST['school_requirements'];
    $courses = $_POST['courses'];
    $vacancies = $_POST['vacancies'];
    $requirements = $_POST['requirements'];

    // Inserir a escola
    $stmt = $conn->prepare("INSERT INTO schools (name, requirements) VALUES (?, ?)");
    $stmt->bind_param("ss", $school_name, $school_requirements);
    $stmt->execute();
    $school_id = $stmt->insert_id;

    // Inserir os cursos ligados à escola
    $stmt_course = $conn->prepare("INSERT INTO courses (school_id, course_name, vacancies, course_requirements) VALUES (?, ?, ?, ?)");
    foreach ($courses as $index => $course_name) {
        $vacancy = (int)$vacancies[$index];
        $course_req = $requirements[$index];
        $stmt_course->bind_param("isis", $school_id, $course_name, $vacancy, $course_req);
        $stmt_course->execute();
    }

    echo "Escola e cursos cadastrados com sucesso!";
}
?>