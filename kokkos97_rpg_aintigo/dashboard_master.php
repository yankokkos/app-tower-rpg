<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado e se é um mestre
if (!isset($_SESSION['usuario_id']) || $_SESSION['funcao'] != 1) {
    header("Location: login.php");
    exit;
}

// Obter todas as fichas
$stmt = $pdo->prepare("SELECT * FROM fichas");
$stmt->execute();
$fichas = $stmt->fetchAll();

// Obter todos os usuários
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll();

$message = ""; // Variável para armazenar mensagens de feedback

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $nova_funcao = $_POST['nova_funcao'];

    // Validação da função
    if (!in_array($nova_funcao, [1, 2, 3])) {
        $message = "Função inválida.";
    } else {
        try {
            // Atualiza a função do usuário
            $stmt = $pdo->prepare("UPDATE usuarios SET função = ? WHERE id = ?");
            if ($stmt->execute([$nova_funcao, $usuario_id])) {
                $message = "Função do usuário alterada com sucesso!";
            } else {
                $message = "Erro ao alterar a função do usuário.";
            }
        } catch (PDOException $e) {
            $message = "Erro ao alterar a função: " . $e->getMessage();
        }
    }

    // Redireciona após a atualização
    header("Location: dashboard_master.php"); // Redireciona para evitar reenvio do formulário
    exit;
}
?>

<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <title>Dashboard do Mestre</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1a1a1a;
            color: #ecf0f1;
        }
        .container {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            border: 1px solid #00b89400;
        }
        h1, h2, h3 {
            color: #D4AF37;
        }
        #ivdif2 {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            object-position: top;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-center mb-4">Dashboard do Mestre</h1>
        <a class="text-danger" href="logout.php">Sair</a>

        <h2 class="mt-4">Gerenciar Fichas</h2>
        <a class="btn btn-success mb-4" href="create_ficha.php">Criar Nova Ficha</a>

        <h2 class="mt-4">Fichas de Personagens</h2>
        <div class="row g-4 mb-4">
            <?php foreach ($fichas as $ficha): ?>
                <div class="col">
                    <div class="card bg-dark text-white">
                        <img src="<?= htmlspecialchars($ficha['aparencia_personalidade']) ?>" alt="Aparência do Personagem" class="card-img-top rounded-circle mx-auto mt-3" id="ivdif2">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($ficha['nome']) ?></h5>
                            <p class="card-text">Jogador: <?= htmlspecialchars($ficha['jogador']) ?></p>
                            <div class="d-flex justify-content-center">
                                <a href="view_ficha.php?id=<?= htmlspecialchars($ficha['id']) ?>" class="btn btn-success me-2">Visualizar</a>
                                <a href="edit_ficha.php?id=<?= htmlspecialchars($ficha['id']) ?>" class="btn btn-success">Editar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="mt-4">Gerenciar Usuários</h2>

        <?php if ($message): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <ul class="list-group mb-4">
            <?php foreach ($usuarios as $usuario): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-white">
                    <span><?= htmlspecialchars($usuario['Nome']) ?></span>
                    <form class="d-flex align-items-center" method="POST">
                        <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario['id']) ?>">
                        <select name="nova_funcao" required class="form-select bg-dark text-white border-secondary me-2">
                            <option value="1" <?= $usuario['função'] == 1 ? 'selected' : '' ?>>Mestre</option>
                            <option value="2" <?= $usuario['função'] == 2 ? 'selected' : '' ?>>Jogador</option>
                            <option value="3" <?= $usuario['função'] == 3 ? 'selected' : '' ?>>Visitante</option>
                        </select>
                        <button type="submit" class="btn btn-success">Alterar Função</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <a href="register.php" class="btn btn-success mb-4">Criar Novo Usuário</a>
    </div>
</body>
</html>