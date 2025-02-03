<!-- add_allies_enemies_modal.php -->
<div id="addAlliesEnemiesModal" class="modal">
    <form method="POST">
        <h3>Adicionar Aliados e Inimigos</h3>
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" class="w-full p-2 border rounded"/>

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" class="w-full p-2 border rounded"/>

        <label for="relacao">Relação:</label>
        <input type="text" name="relacao" id="relacao" class="w-full p-2 border rounded"/>

        <label for="historia">História:</label>
        <textarea name="historia" id="historia" class="w-full p-2 border rounded"></textarea>

        <button type="submit" name="add_allies_enemies" class="w-full bg-green-800 text-white p-2 rounded">Adicionar</button>
    </form>
</div>