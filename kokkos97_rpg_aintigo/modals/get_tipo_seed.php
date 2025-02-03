<?php
require 'db.php'; // Inclua seu arquivo de conexПлкo ao banco de dados

if (isset($_GET['id'])) {
    $tipo_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT cartas_de_poder, custo_pe, facilidade_controle, poder_inato FROM tipos_seed WHERE id = ?");
    $stmt->execute([$tipo_id]);
    $tipo_seed = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tipo_seed) {
        echo json_encode($tipo_seed);
    } else {
        echo json_encode([]);
    }
}
?>