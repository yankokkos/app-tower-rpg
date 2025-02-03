<?php
if (isset($_POST['add_card']) || isset($_POST['edit_card'])) {
    $titulo = $_POST['titulo'];
    $gasto_pe = $_POST['gasto_pe'];
    $tempo = $_POST['tempo'];
    $descricao = $_POST['descricao'];
    $efeitos_basicos = json_encode($_POST['efeitos_basicos']);
    $efeitos_adicionais = json_encode($_POST['efeitos_adicionais']);
    
    if (isset($_POST['edit_card'])) {
        $carta_id = $_POST['carta_id'];
        
        // Atualizar a carta
        $stmt = $pdo->prepare("UPDATE cartas_de_poder SET titulo = ?, gasto_pe = ?, tempo = ?, descricao = ?, efeitos_basicos = ?, efeitos_adicionais = ? WHERE id = ?");
        $stmt->execute([$titulo, $gasto_pe, $tempo, $descricao, $efeitos_basicos, $efeitos_adicionais, $carta_id]);
        
        // Atualizar temas associados
        $stmt = $pdo->prepare("DELETE FROM cartas_tematicas WHERE carta_id = ?");
        $stmt->execute([$carta_id]);
    } else {
        // Adicionar nova carta
        $stmt = $pdo->prepare("INSERT INTO cartas_de_poder (titulo, gasto_pe, tempo, descricao, efeitos_basicos, efeitos_adicionais, ficha_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $gasto_pe, $tempo, $descricao, $efeitos_basicos, $efeitos_adicionais, $id]);
        $carta_id = $pdo->lastInsertId(); // Captura o ID da nova carta
    }

    // Associar temas à carta
    if (!empty($_POST['temas'])) {
        foreach ($_POST['temas'] as $tema_id) {
            $stmt = $pdo->prepare("INSERT INTO cartas_tematicas (carta_id, tema_id) VALUES (?, ?)");
            $stmt->execute([$carta_id, $tema_id]);
        }
    }

    header("Location: view_ficha.php?id=$id");
    exit;
}
?>

<!-- edit_power_cards_modal.php -->
<div id="editPowerCardsModal" class="modal">
    <form method="POST">
        <h3>Editar Cartas de Poder</h3>
        <label for="nome_cartas">Nome:</label>
        <input type="text" name="nome_cartas" id="nome_cartas" class="w-full p-2 border rounded"/>

        <label for="temas_cartas">Temas:</label>
        <input type="text" name="temas_cartas" id="temas_cartas" class="w-full p-2 border rounded"/>

        <label for="gasto_pe">Gasto de PE:</label>
        <input type="number" name="gasto_pe" id="gasto_pe" class="w-full p-2 border rounded"/>

        <label for="tempo_cartas">Tempo:</label>
        <input type="text" name="tempo_cartas" id="tempo_cartas" class="w-full p-2 border rounded"/>

        <label for="descricao_cartas">Descrição:</label>
        <textarea name="descricao_cartas" id="descricao_cartas" class="w-full p-2 border rounded"></textarea>

        <label for="efeitos_basicos">Efeitos básicos:</label>
        <textarea name="efeitos_basicos" id="efeitos_basicos" class="w-full p-2 border rounded"></textarea>

        <label for="efeitos_adicionais">Efeitos adicionais:</label>
        <textarea name="efeitos_adicionais" id="efeitos_adicionais" class="w-full p-2 border rounded"></textarea>

        <button type="submit" name="edit_power_cards" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>