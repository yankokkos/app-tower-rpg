<!-- add_equipment_modal.php -->
<div id="addEquipmentModal" class="modal">
    <form method="POST">
        <h3>Adicionar Equipamentos</h3>
        <label for="nome_equipamento">Nome:</label>
        <input type="text" name="nome_equipamento" id="nome_equipamento" class="w-full p-2 border rounded"/>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" class="w-full p-2 border rounded"/>

        <label for="peso_por_unidade">Peso por Unidade:</label>
        <input type="number" step="0.01" name="peso_por_unidade" id="peso_por_unidade" class="w-full p-2 border rounded"/>

        <label for="log_ultima_edicao">Log de Última Edição:</label>
        <textarea name="log_ultima_edicao" id="log_ultima_edicao" class="w-full p-2 border rounded"></textarea>

        <button type="submit" name="add_equipment" class="w-full bg-green-800 text-white p-2 rounded">Adicionar</button>
    </form>
</div>