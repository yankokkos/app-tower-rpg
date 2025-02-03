<?php
$host = 'localhost';
$db = 'kokkos97_RPG';
$user = 'kokkos97_Rpg'; // ou seu usuário do MySQL
$pass = '(6+H6))h.tiu'; // ou sua senha do MySQL

try {
    // Cria uma nova conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Testa a conexão
    $stmt = $pdo->query("SELECT 1"); // Testa uma consulta simples
} catch (PDOException $e) {
    // Em produção, você pode querer registrar o erro em um log
    echo "Connection failed: " . $e->getMessage();
}
?>