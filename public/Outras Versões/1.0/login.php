<?php
ob_start();
session_start();
include 'db.php'; // Conexão com banco de dados

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $error = "Por favor, preencha todos os campos.";
    } else {
        // Seleciona usuário da tabela admins
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $row = $result->fetch_assoc();

            // Verifica senha
            if(password_verify($password, $row['password']) || $password === $row['password']){
                $_SESSION['username'] = $row['username']; // só o username
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Senha incorreta!";
            }
        } else {
            $error = "Usuário não encontrado!";
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel - Login</title>
<style>
body { font-family: Arial,sans-serif; background: linear-gradient(135deg,#1e3c72,#2a5298); display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
.login-container { background:#fff; padding:40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.2); width:100%; max-width:400px; text-align:center; animation:fadeIn 1s ease-in-out; }
@keyframes fadeIn { from{opacity:0; transform:translateY(-20px);} to{opacity:1; transform:translateY(0);} }
h2 { margin-bottom:1.5rem; color:#2a5298; }
input { width:100%; padding:12px; margin:10px 0; border-radius:10px; border:1px solid #ccc; font-size:15px; }
input:focus { border:1px solid #2a5298; outline:none; }
button { width:100%; padding:12px; border:none; background:linear-gradient(135deg,#3b82f6,#2563eb); color:white; font-size:16px; border-radius:10px; cursor:pointer; transition:0.3s; }
button:hover { background:linear-gradient(135deg,#2563eb,#1d4ed8); }
.error { color:red; margin-top:15px; font-size:14px; }
</style>
</head>
<body>
<div class="login-container">
<h2>Login do Administrador</h2>
<form method="POST" action="">
<input type="text" name="username" placeholder="Nome de Usuário" required>
<input type="password" name="password" placeholder="Palavra-passe" required>
<button type="submit">Entrar</button>
</form>
<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
</div>
</body>
</html>
