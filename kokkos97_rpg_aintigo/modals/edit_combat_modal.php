<!-- Modal de Edição de Combate -->
<div id="editCombatModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 p-6 rounded shadow-lg">
        <h2 class="text-xl font-semibold">Editar Combate</h2>
        <form id="editCombatForm" method="POST">
            <input type="hidden" name="id" id="editCombatId" />
            <input type="text" name="pericia" id="editPericia" placeholder="Perícia" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="arma_ou_poder" id="editArma" placeholder="Arma ou Poder" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="ataque" id="editAtaque" placeholder="Ataque" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="pts" id="editPts" placeholder="Pontos" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="cargas" id="editCargas" placeholder="Cargas" required class="w-full p-2 border rounded mb-2"/>
            <input type="number" name="ataque_por_turno" id="editAtaqueTurno" placeholder="Ataque por Turno" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="distancia" id="editDistancia" placeholder="Distância" required class="w-full p-2 border rounded mb-2"/>
            <input type="text" name="detalhes" id="editDetalhes" placeholder="Detalhes" required class="w-full p-2 border rounded mb-2"/>
            <button type="submit" name="edit_combate" class="bg-green-800 text-white p-2 rounded">Salvar</button>
            <button type="button" id="closeEditCombatModal" class="bg-red-800 text-white p-2 rounded">Fechar</button>
        </form>
    </div>
</div>
