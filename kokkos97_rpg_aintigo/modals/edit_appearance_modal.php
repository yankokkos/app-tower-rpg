<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_appearance'])) {
    // Obter o ID da ficha
    $ficha_id = $_GET['id'] ?? null; // Certifique-se de que o ID está sendo passado corretamente

    if (!$ficha_id) {
        die("ID não fornecido.");
    }

    // Obter os valores do formulário
    $idade = $_POST['idade'] ?? null;
    $altura = $_POST['altura'] ?? null;
    $peso = $_POST['peso'] ?? null;
    $cabelos = $_POST['cabelos'] ?? null;
    $olhos = $_POST['olhos'] ?? null;

    // Obter a URL da imagem
    $aparencia_personalidade = $_POST['aparencia_personalidade'] ?? null;

    // Atualizar a ficha na tabela fichas
    $stmt = $pdo->prepare("UPDATE fichas SET 
        idade = ?, 
        altura = ?, 
        peso = ?, 
        cabelos = ?, 
        olhos = ?, 
        aparencia_personalidade = ? 
        WHERE id = ?");
    
    $stmt->execute([
        $idade,
        $altura,
        $peso,
        $cabelos,
        $olhos,
        $aparencia_personalidade,
        $ficha_id
    ]);

    // Redirecionar ou exibir uma mensagem de sucesso
    header("Location: view_ficha.php?id=$ficha_id");
    exit;
}
?>
<!-- edit_appearance_modal.php -->
<div id="editAppearanceModal" class="modal">
    <form method="POST">
        <h3>Editar Aparência</h3>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['idade']) ?>"/>

        <label for="altura">Altura:</label>
        <input type="text" name="altura" id="altura" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['altura']) ?>"/>

        <label for="peso">Peso:</label>
        <input type="text" name="peso" id="peso" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['peso']) ?>"/>

        <label for="cabelos">Cabelos:</label>
        <input type="text" name="cabelos" id="cabelos" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['cabelos']) ?>"/>

        <label for="olhos">Olhos:</label>
        <input type="text" name="olhos" id="olhos" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['olhos']) ?>"/>

        <label for="aparencia_personalidade">URL da Imagem de Aparência:</label>
        <input type="text" name="aparencia_personalidade" id="aparencia_personalidade" class="w-full p-2 border rounded" value="<?= htmlspecialchars($ficha['aparencia_personalidade']) ?>"/>

        <button type="submit" name="edit_appearance" class="w-full bg-green-800 text-white p-2 rounded">Salvar</button>
    </form>
</div>