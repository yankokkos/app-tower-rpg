<?php
// Captura o ID da ficha a ser visualizada
$id = $_GET['id'] ?? null; // Usando o operador de coalescência nula para evitar erros
if (!$id) {
    die("ID não fornecido.");
}

// Modal para Adicionar Perícia
?>
<div id="addSkillsModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addSkillsModal')">&times;</span>
        <h2>Adicionar Perícia</h2>
        <form method="POST" action="view_ficha.php?id=<?= $id ?>">
            <label for="nome_pericia">Nome:</label>
            <input type="text" name="nome_pericia" id="nome_pericia" class="w-full p-2 border rounded" required/>

            <label for="nivel_disponivel">Nível Disponível:</label>
            <input type="number" name="nivel_disponivel" id="nivel_disponivel" class="w-full p-2 border rounded" required/>

            <label for="nivel_pericia">Nível da Perícia:</label>
            <input type="number" name="nivel_pericia" id="nivel_pericia" class="w-full p-2 border rounded" required/>

            <label for="formula">Fórmula:</label>
            <input type="text" name="formula" id="formula" class="w-full p-2 border rounded" required/>

            <label for="bonus">Bônus:</label>
            <input type="number" name="bonus" id="bonus" class="w-full p-2 border rounded" required/>

            <button type="submit" name="add_skills" class="w-full bg-green-800 text-white p-2 rounded">Adicionar</button>
        </form>
    </div>
</div>