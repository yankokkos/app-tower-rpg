<?php
// Verificar se a solicitação de edição ou adição de Seed foi feita
if (isset($_POST['edit_seed']) || isset($_POST['add_seed'])) {
    $plano_origem_id = $_POST['plano_origem_id'];
    $tipo_seed_id = $_POST['tipo_seed_id'];
    $patrono_item = $_POST['patrono_item'];
    $classe_ameaca_id = $_POST['classe_ameaca_id'];

    if (isset($_POST['edit_seed'])) {
        $seed_id = $_POST['seed_id']; // ID da Seed a ser editada

        // Preparar e executar a consulta para atualizar a Seed
        $stmt = $pdo->prepare("UPDATE seeds SET plano_origem_id = ?, tipo_seed_id = ?, patrono_item = ?, classe_ameaca_id = ? WHERE id = ?");
        $stmt->execute([$plano_origem_id, $tipo_seed_id, $patrono_item, $classe_ameaca_id, $seed_id]);
    } else {
        // Preparar e executar a consulta para adicionar a Seed
        $stmt = $pdo->prepare("INSERT INTO seeds (ficha_id, plano_origem_id, tipo_seed_id, patrono_item, classe_ameaca_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $plano_origem_id, $tipo_seed_id, $patrono_item, $classe_ameaca_id]);
    }

    // Redirecionar após a edição ou adição
    header("Location: view_ficha.php?id=$id");
    exit;
}
?>


<!-- edit_seed_modal.php -->
<div id="editSeedModal" class="modal">
    <form method="POST">
        <h3><?= $seedExists ? 'Editar Seed' : 'Adicionar Seed' ?></h3>
        <input type="hidden" name="seed_id" value="<?= $seedExists ? $seed['id'] : '' ?>" /> <!-- ID da Seed, se existir -->
        
        <label for="plano_origem">Plano de Origem:</label>
        <select name="plano_origem_id" id="plano_origem" class="w-full p-2 border rounded">
            <?php
            // Consultar planos
            $planos = $pdo->query("SELECT * FROM planos")->fetchAll();
            foreach ($planos as $plano) {
                $selected = $seedExists && $plano['id'] == $seed['plano_origem_id'] ? 'selected' : '';
                echo "<option value=\"{$plano['id']}\" $selected>{$plano['nome']}</option>";
            }
            ?>
        </select>

        <label for="tipo_seed">Tipo de Seed:</label>
        <select name="tipo_seed_id" id="tipo_seed" class="w-full p-2 border rounded">
            <?php
            // Consultar tipos de seed
            $tipos_seed = $pdo->query("SELECT * FROM tipos_seed")->fetchAll();
            foreach ($tipos_seed as $tipo) {
                $selected = $seedExists && $tipo['id'] == $seed['tipo_seed_id'] ? 'selected' : '';
                echo "<option value=\"{$tipo['id']}\" $selected>{$tipo['nome']}</option>";
            }
            ?>
        </select>

        <label for="classe_ameaca">Classe de Ameaça do Patrono:</label>
        <select name="classe_ameaca_id" id="classe_ameaca" class="w-full p-2 border rounded">
            <?php
            // Consultar classes de ameaça
            $classes_ameaca = $pdo->query("SELECT * FROM classe_ameaca")->fetchAll();
            foreach ($classes_ameaca as $classe) {
                $selected = $seedExists && $classe['id'] == $seed['classe_ameaca_id'] ? 'selected' : '';
                echo "<option value=\"{$classe['id']}\" $selected>{$classe['classe']}</option>";
            }
            ?>
        </select>

        <label for="patrono_item">Patrono ou Item:</label>
        <input type="text" name="patrono_item" id="patrono_item" class="w-full p-2 border rounded" value="<?= $seedExists ? htmlspecialchars($seed['patrono_item']) : '' ?>" required/>

        <button type="submit" name="<?= $seedExists ? 'edit_seed' : 'add_seed' ?>" class="mt-4 p-2 bg-blue-600 text-white rounded"><?= $seedExists ? 'Salvar' : 'Adicionar' ?></button>
    </form>
</div>