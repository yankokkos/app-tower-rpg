<div id="addCombatModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 p-6 rounded shadow-lg">
        <h2 class="text-xl font-semibold">Adicionar Combate</h2>
        <form id="addCombatForm" method="POST">
            <input type="text" name="pericia" placeholder="Perícia" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="arma_ou_poder" placeholder="Arma ou Poder" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="ataque" placeholder="Ataque" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="pts" placeholder="Pontos" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="cargas" placeholder="Cargas" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="ataque_por_turno" placeholder="Ataque por Turno" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="distancia" placeholder="Distância" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="detalhes" placeholder="Detalhes" required class="w-full p-2 border rounded mb-2"/>
            <button type="submit" name="add_combate" class="bg-green-800 text-white p-2 rounded">Adicionar</button>
            <button type="button" id="closeAddCombatModal" class="bg-red-800 text-white p-2 rounded">Fechar</button>
        </form>
    </div>
</div>