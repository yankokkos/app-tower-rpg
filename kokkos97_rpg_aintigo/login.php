<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Nome = $_POST['Nome'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Nome = ?");
    $stmt->execute([$Nome]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['funcao'] = $usuario['função']; // Armazena a função na sessão

        // Redireciona com base na função
        if ($usuario['função'] == 1) {
            header("Location: dashboard_master.php");
        } elseif ($usuario['função'] == 2) {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard_visitante.php");
        }
        exit; // Adicione exit após o redirecionamento
    } else {
        echo "Nome de usuário ou senha inválidos."; // Mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
        <style>
        body {
            background: linear-gradient(to right, #1a3323, #1a1a1a);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            background-color: #1f3729;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 60px rgba(0, 255, 0, 0.3);
            width: 100%;
            max-width: 400px;
            border: 1px solid #00b894;
        }
        .logo {
            width: 6rem;
            height: 6rem;
        }
        .logod {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: medium;
            color: #fdcb6e;
        }
        input {
            margin-top: 0.25rem;
            width: 100%;
            padding: 0.5rem 0.75rem;
            background-color: #2d3436;
            color: white;
            border-radius: 0.375rem;
            border: none;
            outline: none;
        }
        input:focus {
            ring: 2px solid #00b894;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
        }
        .checkbox-label input {
            height: 1rem;
            width: 1rem;
            margin-right: 0.5rem;
        }
        .link {
            font-size: 0.875rem;
            color: #00b894;
            text-decoration: none;
        }
        .link:hover {
            color: #00b884;
        }
        button {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 0.5rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: medium;
            color: white;
            background-color: #00b894;
            cursor: pointer;
        }
        button:hover {
            background-color: #00b884;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logod">
            <img alt="Logotipo da agência com um emblema secreto e cores escuras" class="logo" src=.\logo.png >
        </div>
        <form method="POST">
            <div>
                <label for="username">Nome de Usuário</label>
                <input type="Nome" name="Nome" placeholder="Nome" required>
            </div>
            <div>
                <label for="password">Senha</label>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <div class="flex items-center justify-between">
                <div class="checkbox-label">
                    <input id="remember_me" name="remember_me" type="checkbox"/>
                    <label for="remember_me">Lembrar-me</label>
                </div>
                <div>
                    <a class="link" href="#">Esqueceu sua senha? 
                    problema seu mandei anotar</a>
                </div>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
