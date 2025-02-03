  <!-- Modal de Edição de Perícia -->
    <div id="editSkillsModal<?= $pericia['id'] ?>" class="modal hidden">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editSkillsModal<?= $pericia['id'] ?>')">&times;</span>
            <h2>Editar Perícia</h2>
            <form method="POST" action="view_ficha.php?id=<?= $id ?>">
                <input type="hidden" name="pericia_id" value="<?= $pericia['id'] ?>" />
                <label for="nome_pericia">Nome:</label>
                <input type="text" name="nome_pericia" id="nome_pericia" value="<?= htmlspecialchars($pericia['nome']) ?>" class="w-full p-2 border rounded" required/>

                <label for="nivel_disponivel">Nível Disponível:</label>
                <input type="number" name="nivel_disponivel" id="nivel_disponivel" value="<?= htmlspecialchars($pericia['nivel_disponivel']) ?>" class="w-full p-2 border rounded" required/>

                <label for="nivel_pericia">Nível da Perícia:</label>
                <input type="number" name="nivel_pericia" id="nivel_pericia" value="<?= htmlspecialchars($pericia['nivel_pericia']) ?>" class="w-full p-2 border rounded" required/>

                <label for="formula">Fórmula:</label>
                <input type="text" name="formula" id="formula" value="<?= htmlspecialchars($pericia['formula']) ?>" class="w-full p-2 border rounded" required/>

                <label for="bonus">Bônus:</label>            
                <input type="number" name="bonus" id="bonus" value="<?= htmlspecialchars($pericia['bonus']) ?>" class="w-full p-2 border rounded" required/>

                <button type="submit" name="edit_skills" class="w-full bg-blue-800 text-white p-2 rounded">Salvar Alterações</button>
            </form>
        </div>
    </div>