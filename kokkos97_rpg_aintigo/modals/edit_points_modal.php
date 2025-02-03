<?php
// Processa a atualização dos pontos se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_points'])) {
        $pontos_narrativos = $_POST['pontos_narrativos'];
        $pontos_acao = $_POST['pontos_acao'];
        $pontos_xp = $_POST['pontos_xp'];

        try {
            // Atualiza os pontos no banco de dados
            $stmt = $pdo->prepare("UPDATE fichas SET pontos_narrativos = ?, pontos_acao = ?, pontos_xp = ? WHERE id = ?");
            $stmt->execute([$pontos_narrativos, $pontos_acao, $pontos_xp, $ficha['id']]);
            header("Location: view_ficha.php?id=" . $ficha['id']);
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar pontos: " . $e->getMessage();
        }
    }
}
?>

<!-- Modal para Editar Pontos -->
<div id="editPointsModal" class="modal hidden">
    <form method="POST">
        <h3>Editar Pontos</h3>
        
        <label for="pontos_narrativos">Pontos Narrativos:</label>
        <input type="number" name="pontos_narrativos" id="pontos_narrativos" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pontos_narrativos']) ?>" required/>

        <label for="pontos_acao">Pontos de Ação:</label>
        <input type="number" name="pontos_acao" id="pontos_acao" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pontos_acao']) ?>" required/>

        <label for="pontos_xp">Pontos de XP:</label>
        <input type="number" name="pontos_xp" id="pontos_xp" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pontos_xp']) ?>" required/>

        <button type="submit" name="edit_points" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>