<?php
session_start();

// Verifica se o usuário está autenticado
if (isset($_SESSION['usuario_id'])) {
    // Se o usuário estiver autenticado, redireciona para o dashboard
    header("Location: dashboard.php");
    exit;
} else {
    // Se não estiver autenticado, redireciona para a página de login
    header("Location: login.php");
    exit;
}
?>