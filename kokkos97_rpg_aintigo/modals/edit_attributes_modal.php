<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_attributes'])) {
    // Obter o ID da ficha
    $ficha_id = $_GET['id']; // Certifique-se de que o ID está sendo passado corretamente

    // Obter os valores do formulário
    $forca_adicional = $_POST['forca_adicional'] ?? 0;
    $destreza_adicional = $_POST['destreza_adicional'] ?? 0;
    $constituicao_adicional = $_POST['constituicao_adicional'] ?? 0;
    $inteligencia_adicional = $_POST['inteligencia_adicional'] ?? 0;
    $sabedoria_adicional = $_POST['sabedoria_adicional'] ?? 0;
    $carisma_adicional = $_POST['carisma_adicional'] ?? 0;
    $pvs_adicionais = $_POST['pvs_adicionais'] ?? 0;
    $pss_adicionais = $_POST['pss_adicionais'] ?? 0;
    $pes_adicionais = $_POST['pes_adicionais'] ?? 0;
    $poder_adicional = $_POST['poder_adicional'] ?? 0; // Adicione esta linha

    // Atualizar a ficha na tabela fichas
    $stmt = $pdo->prepare("UPDATE fichas SET 
        forca_adicional = ?, 
        destreza_adicional = ?, 
        constituicao_adicional = ?, 
        inteligencia_adicional = ?, 
        sabedoria_adicional = ?, 
        carisma_adicional = ?, 
        pvs_adicionais = ?, 
        pss_adicionais = ?, 
        pes_adicionais = ?,
        poder_adicional = ?  -- Adicione esta linha
        WHERE id = ?");
    
    $stmt->execute([
        $forca_adicional,
        $destreza_adicional,
        $constituicao_adicional,
        $inteligencia_adicional,
        $sabedoria_adicional,
        $carisma_adicional,
        $pvs_adicionais,
        $pss_adicionais,
        $pes_adicionais,
        $poder_adicional, // Adicione esta linha
        $ficha_id
    ]);

    // Redirecionar ou exibir uma mensagem de sucesso
    header("Location: edit_ficha.php?id=$ficha_id");
    exit;
}
?>
<!-- edit_attributes_modal.php -->
<div id="editAttributesModal" class="modal">
    <form method="POST">
        <h3>Editar Atributos</h3>
        <label for="forca_adicional">Força Adicional:</label>
        <input type="number" name="forca_adicional" id="forca_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['forca_adicional']) ?>"/>

        <label for="destreza_adicional">Destreza Adicional:</label>
        <input type="number" name="destreza_adicional" id="destreza_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['destreza_adicional']) ?>"/>

        <label for="constituicao_adicional">Constituição Adicional:</label>
        <input type="number" name="constituicao_adicional" id="constituicao_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['constituicao_adicional']) ?>"/>

        <label for="inteligencia_adicional">Inteligência Adicional:</label>
        <input type="number" name="inteligencia_adicional" id="inteligencia_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['inteligencia_adicional']) ?>"/>

        <label for="sabedoria_adicional">Sabedoria Adicional:</label>
        <input type="number" name="sabedoria_adicional" id="sabedoria_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['sabedoria_adicional']) ?>"/>

        <label for="carisma_adicional">Carisma Adicional:</label>
        <input type="number" name="carisma_adicional" id="carisma_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['carisma_adicional']) ?>"/>

        <label for="pvs_adicionais">PVs Adicionais:</label>
        <input type="number" name="pvs_adicionais" id="pvs_adicionais" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pvs_adicionais']) ?>"/>

        <label for="pss_adicionais">PSs Adicionais:</label>
        <input type="number" name="pss_adicionais" id="pss_adicionais" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pss_adicionais']) ?>"/>

        <label for="pes_adicionais">PES Adicionais:</label>
        <input type="number" name="pes_adicionais" id="pes_adicionais" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['pes_adicionais']) ?>"/>

        <label for="poder_adicional">Poder Adicional:</label>
        <input type="number" name="poder_adicional" id="poder_adicional" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['poder_adicional']) ?>"/>

        <button type="submit" name="edit_attributes" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>