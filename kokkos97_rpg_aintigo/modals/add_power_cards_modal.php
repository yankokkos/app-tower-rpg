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

<!-- Modal para Adicionar Carta de Poder -->
<div id="addCardModal" class="modal hidden">
    <form method="POST">
        <h3>Adicionar Carta de Poder</h3>
        
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" class="w-full p-2 border rounded" required/>

        <label for="temas">Temas:</label>
        <select name="temas[]" id="temas" class="w-full p-2 border rounded" multiple>
            <?php
            // Consultar temas disponíveis
            $stmt = $pdo->prepare("SELECT * FROM temas_de_poder");
            $stmt->execute();
            $temas = $stmt->fetchAll();

            foreach ($temas as $tema): ?>
                <option value="<?= $tema['id'] ?>"><?= htmlspecialchars($tema['nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="gasto_pe">Gasto de PE:</label>
        <input type="number" name="gasto_pe" id="gasto_pe" class="w-full p-2 border rounded" required/>

        <label for="tempo">Tempo:</label>
        <input type="text" name="tempo" id="tempo" class="w-full p-2 border rounded" />

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" class="w-full p-2 border rounded"></textarea>

        <label for="efeitos_basicos">Efeitos Básicos:</label>
        <div id="efeitos_basicos_container">
            <div class="efeito">
                <input type="text" name="efeitos_basicos[]" placeholder="Efeito Básico" class="w-full p-2 border rounded" />
            </div>
        </div>
        <button type="button" onclick="addEfeito('efeitos_basicos_container')" class="mt-2 bg-green-600 text-white p-2 rounded">Adicionar Efeito Básico</button>

        <label for="efeitos_adicionais">Efeitos Adicionais:</label>
        <div id="efeitos_adicionais_container">
            <div class="efeito">
                <input type="text" name="efeitos_adicionais[]" placeholder="Efeito Adicional" class="w-full p-2 border rounded" />
            </div>
        </div>
        <button type="button" onclick="addEfeito('efeitos_adicionais_container')" class="mt-2 bg-green-600 text-white p-2 rounded">Adicionar Efeito Adicional</button>

        <button type="submit" name="add_card" class="mt-4 p-2 bg-blue-600 text-white rounded">Adicionar</button>
    </form>
</div>

<script>
    function addEfeito(containerId) {
        const container = document.getElementById(containerId);
        const newEfeito = document.createElement('div');
        newEfeito.classList.add('efeito');
        newEfeito.innerHTML = '<input type="text" name="' + containerId.replace('_container', '') + '[]" placeholder="Efeito" class="w-full p-2 border rounded" />';
        container.appendChild(newEfeito);
    }
</script>