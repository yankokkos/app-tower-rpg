<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT * FROM fichas WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$fichas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Jogador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
        }
        h1, h2 {
            color: #D4AF37;
        }
        #ivdif2 {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            object-position: top;
        }
        .ficha-card {
            background-color: #444;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }
        .ficha-name {
            color: #D4AF37;
            text-decoration: none;
            font-weight: bold;
        }
        .ficha-name:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Minhas Fichas</h1>
        <a href="create_ficha.php" class="btn btn-success mb-4">Criar Nova Ficha</a>
        
        <div class="row g-4 mb-4">  
            <?php foreach ($fichas as $ficha): ?>
                <div class="col">
                    <div class="ficha-card">
                        <img src="<?= htmlspecialchars($ficha['aparencia_personalidade']) ?>" alt="Aparência do Personagem" class="mx-auto rounded-circle mb-4" id="ivdif2">
                        <a href="view_ficha.php?id=<?= htmlspecialchars($ficha['id']) ?>" class="ficha-name"><?= htmlspecialchars($ficha['nome']) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="logout.php" class="btn btn-danger">Sair</a>
    </div>
</body>
</html>