<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_background'])) {
        $sonhos = $_POST['sonhos'];
        $medos = $_POST['medos'];
        $historia = $_POST['historia'];

        try {
            // Atualiza a hist��ria na tabela fichas
            $stmt = $pdo->prepare("UPDATE fichas SET historia = ? WHERE id = ?");
            $stmt->execute([$historia, $ficha['id']]);

            // Atualiza os sonhos
            $sonhosArray = explode(',', $sonhos); // Supondo que os sonhos sejam separados por v��rgula
            foreach ($sonhosArray as $sonho) {
                // Verifica se o sonho j�� existe
                $stmt = $pdo->prepare("SELECT id FROM sonhos WHERE ficha_id = ? AND sonho = ?");
                $stmt->execute([$ficha['id'], trim($sonho)]);
                $existing = $stmt->fetch();

                if ($existing) {
                    // Se j�� existe, n�0�0o faz nada ou pode atualizar se necess��rio
                    continue;
                } else {
                    // Se n�0�0o existe, insere
                    $stmt = $pdo->prepare("INSERT INTO sonhos (ficha_id, sonho) VALUES (?, ?)");
                    $stmt->execute([$ficha['id'], trim($sonho)]);
                }
            }

            // Atualiza os medos
            $medosArray = explode(',', $medos); // Supondo que os medos sejam separados por v��rgula
            foreach ($medosArray as $medo) {
                // Verifica se o medo j�� existe
                $stmt = $pdo->prepare("SELECT id FROM medos WHERE ficha_id = ? AND medo = ?");
                $stmt->execute([$ficha['id'], trim($medo)]);
                $existing = $stmt->fetch();

                if ($existing) {
                    // Se j�� existe, n�0�0o faz nada ou pode atualizar se necess��rio
                    continue;
                } else {
                    // Se n�0�0o existe, insere
                    $stmt = $pdo->prepare("INSERT INTO medos (ficha_id, medo) VALUES (?, ?)");
                    $stmt->execute([$ficha['id'], trim($medo)]);
                }
            }

            header("Location: view_ficha.php?id=" . $ficha['id']);
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar background: " . $e->getMessage();
        }
    }
}

// Transformar os arrays em strings separadas por vírgula
$sonhosString = implode(', ', $sonhos);
$medosString = implode(', ', $medos);

?>

<!-- Modal para Editar Background -->
<div id="editBackgroundModal" class="modal hidden">
    <form method="POST">
        <h3>Editar Background</h3>

        <label for="sonhos">Sonhos:</label>
        <textarea name="sonhos" id="sonhos" class="w-full p-2 border rounded" required><?= isset($sonhosString) ? htmlspecialchars($sonhosString) : '' ?></textarea>

        <label for="medos">Medos:</label>
        <textarea name="medos" id="medos" class="w-full p-2 border rounded" required><?= isset($medosString) ? htmlspecialchars($medosString) : '' ?></textarea>

        <label for="historia">História:</label>
        <textarea name="historia" id="historia" class="w-full p-2 border rounded" required><?= isset($ficha['historia']) ? htmlspecialchars($ficha['historia']) : '' ?></textarea>

        <button type="submit" name="edit_background" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>