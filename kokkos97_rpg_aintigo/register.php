<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $funcao = 3; // Todos os novos usuários serão registrados como Visitantes

    $stmt = $pdo->prepare("INSERT INTO usuarios (Nome, senha, função) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $senha, $funcao]);

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registrar Usuário</h1>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>