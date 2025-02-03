<!-- edit_advantages_disadvantages_modal.php -->
<div id="editAdvantagesDisadvantagesModal" class="modal">
    <form method="POST">
        <h3>Editar Vantagens e Desvantagens</h3>
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" class="w-full p-2 border rounded"/>

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" class="w-full p-2 border rounded"/>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" class="w-full p-2 border rounded"></textarea>

        <label for="efeito_jogo">Efeito no Jogo:</label>
        <textarea name="efeito_jogo" id="efeito_jogo" class="w-full p-2 border rounded"></textarea>

        <button type="submit" name="edit_advantages_disadvantages" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>