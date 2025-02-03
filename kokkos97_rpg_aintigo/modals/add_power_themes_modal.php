<?php
// Verificar se a solicitação de edição ou adição de tema de poder foi feita
if (isset($_POST['edit_power_theme']) || isset($_POST['add_power_theme'])) {
    $titulo = $_POST['titulo'];
    $tema = $_POST ['tema'];
    $descricao = $_POST['descricao'];

    if (isset($_POST['edit_power_theme'])) {
        $tema_id = $_POST['tema_id']; // ID do tema a ser editado

        // Preparar e executar a consulta para atualizar o tema
        $stmt = $pdo->prepare("UPDATE temas_de_poder SET titulo = ?, tema = ?, descricao = ? WHERE id = ?");
        $stmt->execute([$titulo, $tema, $descricao, $tema_id]);
    } else {
        // Preparar e executar a consulta para adicionar o tema
        $stmt = $pdo->prepare("INSERT INTO temas_de_poder (ficha_id, titulo, tema, descricao) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $titulo, $tema, $descricao]);
        $tema_id = $pdo->lastInsertId(); // Captura o ID do tema recém-adicionado
    }

    // Adicionar rótulos de poder
    if (!empty($_POST['poder_nome'])) {
        foreach ($_POST['poder_nome'] as $index => $nome) {
            $nivel = $_POST['poder_nivel'][$index];
            $efeito = $_POST['poder_efeito'][$index];
            if (!empty($nome)) {
                $stmt = $pdo->prepare("INSERT INTO rotulos_poder (tema_id, nome, nivel, efeito) VALUES (?, ?, ?, ?)");
                $stmt->execute([$tema_id, $nome, $nivel, $efeito]);
            }
        }
    }

    // Adicionar rótulos de fraqueza
    if (!empty($_POST['fraqueza_nome'])) {
        foreach ($_POST['fraqueza_nome'] as $index => $nome) {
            $nivel = $_POST['fraqueza_nivel'][$index];
            $efeito = $_POST['fraqueza_efeito'][$index];
            if (!empty($nome)) {
                $stmt = $pdo->prepare("INSERT INTO rotulos_fraqueza (tema_id, nome, nivel, efeito) VALUES (?, ?, ?, ?)");
                $stmt->execute([$tema_id, $nome, $nivel, $efeito]);
            }
        }
    }

    // Redirecionar após a edição ou adição
    header("Location: view_ficha.php?id=$id");
    exit;
}


?>

<!-- Modal para Adicionar Tema de Poder -->
<meta charset="UTF-8">
<div id="addPowerThemesModal" class="modal hidden">
    <form method="POST"> <!-- Defina a ação para onde os dados serão enviados -->
        <h3>Adicionar Tema de Poder</h3>

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" class="w-full p-2 border rounded" required/>

        <label for="tema">Tema:</label>
        <select name="tema" id="tema" class="w-full p-2 border rounded" required>
            <option value="Adaptabilidade">Adaptabilidade</option>
            <option value="Fortaleza">Fortaleza</option>
            <option value="Previsibilidade">Previsibilidade</option>
            <option value="Expressividade">Expressividade</option>
            <option value="Mobilidade">Mobilidade</option>
            <option value="Relíquia">Relíquia</option>
            <option value="Subversão">Subversão</option>
        </select>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" class="w-full p-2 border rounded"></textarea>

        <label for="rotulos_poder">Rótulos de Poder:</label>
        <div id="rotulos_poder_container">
            <div class="rotulo">
                <input type="text" name="poder_nome[]" placeholder="Nome" class="w-full p-2 border rounded" required />
                <input type="number" name="poder_nivel[]" placeholder="Nível" class="w-full p-2 border rounded" required />
                <input type="text" name="poder_efeito[]" placeholder="Efeito" class="w-full p-2 border rounded" required />
            </div>
        </div>
        <button type="button" onclick="addRotulo('poder')" class="mt-2 bg-green-600 text-white p-2 rounded">Adicionar Rótulo de Poder</button>

        <label for="rotulos_fraqueza">Rótulos de Fraqueza:</label>
        <div id="rotulos_fraqueza_container">
            <div class="rotulo">
                <input type="text" name="fraqueza_nome[]" placeholder="Nome" class="w-full p-2 border rounded" required />
                <input type="number" name="fraqueza_nivel[]" placeholder="Nível" class="w-full p-2 border rounded" required />
                <input type="text" name="fraqueza_efeito[]" placeholder="Efeito" class="w-full p-2 border rounded" required />
            </div>
        </div>
        <button type="button" onclick="addRotulo('fraqueza')" class="mt-2 bg-green-600 text-white p-2 rounded">Adicionar Rótulo de Fraqueza</button>

        <button type="submit" name="add_power_theme" class="mt-4 p-2 bg-blue-600 text-white rounded">Adicionar</button>
    </form>
</div>

<script>
    function addRotulo(tipo) {
        const container = document.getElementById(`rotulos_${tipo}_container`);
        const newRotulo = document.createElement('div');
        newRotulo.classList.add('rotulo');
        newRotulo.innerHTML = `
            <input type="text" name="${tipo}_nome[]" placeholder="Nome" class="w-full p-2 border rounded" required />
            <input type="number" name="${tipo}_nivel[]" placeholder="Nível" class="w-full p-2 border rounded" required />
            <input type="text" name="${tipo}_efeito[]" placeholder="Efeito" class="w-full p-2 border rounded" required />
        `;
        container.appendChild(newRotulo);
    }
</script>