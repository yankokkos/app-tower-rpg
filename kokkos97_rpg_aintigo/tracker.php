<?php
session_start();
include 'db.php';

// Ativar a exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Função para executar consultas e retornar resultados
function fetchData($pdo, $query, $params = []) {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Consultar fichas e mobs disponíveis
$fichas = fetchData($pdo, "SELECT * FROM fichas");
$mobs = fetchData($pdo, "SELECT * FROM mobs");

// Processar a atualização dos valores
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_battle'])) {
    foreach ($_POST['participants'] as $participant) {
        if (isset($participant['selected'])) { // Verifica se o participante foi selecionado
            $id = $participant['id'];
            $pvs = $participant['pvs'];
            $pss = $participant['pss'];
            $pes = $participant['pes'];
            $initiative = $participant['initiative'];
            $is_mob = isset($participant['is_mob']) ? 1 : 0; // Verifica se é um mob

            // Validação simples
            if ($pvs < 0 || $pss < 0 || $pes < 0) {
                echo "Os valores de PVs, PSs e PES não podem ser negativos.";
                exit;
            }

            // Verifica se o ID existe nas tabelas de fichas ou mobs
            $exists = fetchData($pdo, "SELECT COUNT(*) FROM fichas WHERE id = ? UNION SELECT COUNT(*) FROM mobs WHERE id = ?", [$id, $id]);
            if ($exists[0][0] == 0) {
                echo "O participante com ID $id não existe nas tabelas de fichas ou mobs.";
                exit;
            }

            // Atualiza ou insere os dados na tabela 'battle_tracker'
            $stmt = $pdo->prepare("INSERT INTO battle_tracker (participant_id, is_mob, pvs, pss, pes, initiative) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE pvs = ?, pss = ?, pes = ?, initiative = ?");
            $stmt->execute([$id, $is_mob, $pvs, $pss, $pes, $initiative, $pvs, $pss, $pes, $initiative]);
        }
    }

    // Redirecionar após a atualização
    header("Location: battle_tracker.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker de Batalha</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adicione seu CSS aqui -->
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-semibold text-yellow-500">Tracker de Batalha</h1>
        <form method="POST" id="battleForm">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Selecionar</th>
                        <th>Iniciativa</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>PVs Atuais</th>
                        <th>PSs Atuais</th>
                        <th>PES Atuais</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fichas as $ficha): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="participants[<?= $ficha['id'] ?>][selected]" value="1"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $ficha['id'] ?>][initiative]" value="" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <?php if (!empty($ficha['imagem'])): ?>
                                    <img src="<?= htmlspecialchars($ficha['imagem']) ?>" alt="<?= htmlspecialchars($ficha['nome']) ?>" class="w-16 h-16"/>
                                <?php else: ?>
                                    <span class="w-16 h-16 bg-gray-300 inline-block"></span> <!-- Espaço reservado -->
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text" name="participants[<?= $ficha['id'] ?>][name]" value="<?= htmlspecialchars($ficha['nome']) ?>" readonly class="bg-gray-200 p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $ficha['id'] ?>][pvs]" value="<?= htmlspecialchars($ficha['pvs_atual']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $ficha['id'] ?>][pss]" value="<?= htmlspecialchars($ficha['pss_atual']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $ficha['id'] ?>][pes]" value="<?= htmlspecialchars($ficha['pes_atual']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <input type="hidden" name="participants[<?= $ficha['id'] ?>][id]" value="<?= $ficha['id'] ?>"/>
                            <input type="hidden" name="participants[<?= $ficha['id'] ?>][is_mob]" value="0"/>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach ($mobs as $mob): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="participants[<?= $mob['id'] ?>][selected]" value="1"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $mob['id'] ?>][initiative]" value="" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <?php if (!empty($mob['imagem'])): ?>
                                    <img src="<?= htmlspecialchars($mob['imagem']) ?>" alt="<?= htmlspecialchars($mob['nome']) ?>" class="w-16 h-16"/>
                                <?php else: ?>
                                    <span class="w-16 h-16 bg-gray-300 inline-block"></span> <!-- Espaço reservado -->
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text" name="participants[<?= $mob['id'] ?>][name]" value="<?= htmlspecialchars($mob['nome']) ?>" readonly class="bg-gray-200 p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $mob['id'] ?>][pvs]" value="<?= htmlspecialchars($mob['pvs_atual']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $mob['id'] ?>][pss]" value="<?= htmlspecialchars($mob['pss_atual']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <td>
                                <input type="number" name="participants[<?= $mob['id'] ?>][pes]" value="<?= htmlspecialchars($mob['pes_atual ']) ?>" class="bg-[#343d32] text-white p-1 rounded w-full"/>
                            </td>
                            <input type="hidden" name="participants[<?= $mob['id'] ?>][id]" value="<?= $mob['id'] ?>"/>
                            <input type="hidden" name="participants[<?= $mob['id'] ?>][is_mob]" value="1"/>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="update_battle" class="mt-4 bg-yellow-500 text-white p-2 rounded">Atualizar Batalha</button>
        </form>
    </div>
</body>
</html>