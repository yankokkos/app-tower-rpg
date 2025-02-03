<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado e se é um visitante
if (!isset($_SESSION['usuario_id']) || $_SESSION['funcao'] != 3) {
    header("Location: login.php");
    exit;
}

// Obter todas as fichas de NPCs disponíveis
$stmt = $pdo->prepare("SELECT * FROM fichas WHERE tipo = 'npc'");
$stmt->execute();
$npc_fichas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Visitante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Dashboard do Visitante</h1>
    <a href="logout.php">Sair</a>
    <h2>Fichas de NPCs Disponíveis</h2>
    <ul>
        <?php foreach ($npc_fichas as $npc): ?>
            <li>
                <a href="view_ficha.php?id=<?= $npc['id'] ?>"><?= htmlspecialchars($npc['nome']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>