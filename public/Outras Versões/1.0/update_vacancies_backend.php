<?php
include 'db.php';

if(isset($_POST['id']) && isset($_POST['vacancies'])){
    $id = intval($_POST['id']);
    $vacancies = intval($_POST['vacancies']);

    $stmt = $conn->prepare("UPDATE courses SET vacancies = ? WHERE id = ?");
    $stmt->bind_param("ii", $vacancies, $id);

    if($stmt->execute()){
        echo "Vagas atualizadas com sucesso!";
    } else {
        echo "Erro ao atualizar vagas: ".$conn->error;
    }
} else {
    echo "Dados inválidos!";
}
?>