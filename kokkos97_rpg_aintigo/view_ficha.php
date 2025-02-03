<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Captura o ID da ficha a ser visualizada
$id = $_GET['id'] ?? null; // Usando o operador de coalescência nula para evitar erros
if (!$id) {
    die("ID não fornecido.");
}

// Prepara e executa a consulta para obter os dados da ficha
$stmt = $pdo->prepare("SELECT * FROM fichas WHERE id = ?");
$stmt->execute([$id]);
$ficha = $stmt->fetch();

// Verifica se a ficha foi encontrada
if (!$ficha) {
    echo "Ficha não encontrada.";
    exit;
}

// Consultar dados da Seed
$stmt = $pdo->prepare("
    SELECT s.*, 
           p.nome AS plano_origem, 
           p.atributo1, 
           p.atributo2, 
           p.atributo3, 
           t.nome AS tipo_seed, 
           t.cartas_de_poder, 
           t.custo_pe, 
           t.facilidade_controle, 
           t.poder_inato, 
           c.classe AS classe_ameaca, 
           c.poder_inato AS poder_inato_classe, 
           c.facilidade_controle AS controle_classe 
    FROM seeds s
    JOIN planos p ON s.plano_origem_id = p.id
    JOIN tipos_seed t ON s.tipo_seed_id = t.id
    JOIN classe_ameaca c ON s.classe_ameaca_id = c.id
    WHERE s.ficha_id = ?
");
$stmt->execute([$id]);
$seed = $stmt->fetch();

// Diagnóstico

// Verifica se a Seed foi encontrada
$seedExists = $seed !== false;

// Consultar temas de poder
$stmt = $pdo->prepare("SELECT * FROM temas_de_poder WHERE ficha_id = ?");
$stmt->execute([$id]);
$temas_de_poder = $stmt->fetchAll();

// Captura o ID da ficha a ser visualizada
$id = $_GET['id'] ?? null; // Usando o operador de coalescência nula para evitar erros
if (!$id) {
    die("ID não fornecido.");
}


// Consultar aliados
$stmt = $pdo->prepare("SELECT * FROM aliados WHERE ficha_id = ?");
$stmt->execute([$id]);
$aliados = $stmt->fetchAll();

// Consultar inimigos
$stmt = $pdo->prepare("SELECT * FROM inimigos WHERE ficha_id = ?");
$stmt->execute([$id]);
$inimigos = $stmt->fetchAll();

// Consultar anotações
$stmt = $pdo->prepare("SELECT * FROM anotacoes WHERE ficha_id = ?");
$stmt->execute([$id]);
$anotacoes = $stmt->fetchAll();

// Consultar equipamentos
$stmt = $pdo->prepare("SELECT * FROM equipamento WHERE ficha_id = ?");
$stmt->execute([$id]);
$equipamentos = $stmt->fetchAll();

// Consultar combate
$stmt = $pdo->prepare("SELECT * FROM combate WHERE ficha_id = ?");
$stmt->execute([$id]);
$combate = $stmt->fetchAll();

// Consultar desvantagens
$stmt = $pdo->prepare("SELECT * FROM desvantagens WHERE ficha_id = ?");
$stmt->execute([$id]);
$desvantagens = $stmt->fetchAll();

// Consultar vantagens
$stmt = $pdo->prepare("SELECT * FROM vantagens WHERE ficha_id = ?");
$stmt->execute([$id]);
$vantagens = $stmt->fetchAll();

// Consultar perícias
$stmt = $pdo->prepare("SELECT * FROM pericias WHERE ficha_id = ?");
$stmt->execute([$id]);
$pericias = $stmt->fetchAll();

// Consultar sonhos
$stmt = $pdo->prepare("SELECT * FROM sonhos WHERE ficha_id = ?");
$stmt->execute([$id]);
$sonhos = $stmt->fetchAll();

// Consultar medos
$stmt = $pdo->prepare("SELECT * FROM medos WHERE ficha_id = ?");
$stmt->execute([$id]);
$medos = $stmt->fetchAll();

function markdown_to_html($text) {
    // Negrito
    $text = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $text);

    // Itálico
    $text = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $text);

    // Lista não ordenada
    $text = preg_replace('/ -(.*)/', '<li>$1</li>', $text);

    // Lista ordenada
    $text = preg_replace('/(\d+)\. (.*)/', '<li>$2</li>', $text);

    // Citação
    $text = preg_replace('/>(.*)/', '<blockquote>$1</blockquote>', $text);

    // Links
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $text);

    return $text;
}

// Atualizar perícia
if (isset($_POST['edit_skills'])) {
    $pericia_id = $_POST['pericia_id']; // ID da perícia a ser editada
    $nome_pericia = $_POST['nome_pericia'];
    $nivel_disponivel = $_POST['nivel_disponivel'];
    $nivel_pericia = $_POST['nivel_pericia'];
    $formula = $_POST['formula'];
    $bonus = $_POST['bonus'];

    $stmt = $pdo->prepare("UPDATE pericias SET nome = ?, nivel_disponivel = ?, nivel_pericia = ?, formula = ?, bonus = ? WHERE id = ?");
    $stmt->execute([$nome_pericia, $nivel_disponivel, $nivel_pericia, $formula, $bonus, $pericia_id]);
    
    header("Location: view_ficha.php?id=$id");
    exit;
}

// Verificar se a solicitação de adição foi feita
if (isset($_POST['add_skills'])) {
    $nome_pericia = $_POST['nome_pericia'];
    $nivel_disponivel = $_POST['nivel_disponivel'];
    $nivel_pericia = $_POST['nivel_pericia'];
    $formula = $_POST['formula'];
    $bonus = $_POST['bonus'];

    // Preparar e executar a consulta para adicionar a perícia
    $stmt = $pdo->prepare("INSERT INTO pericias (ficha_id, nome, nivel_disponivel, nivel_pericia, formula, bonus) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $nome_pericia, $nivel_disponivel, $nivel_pericia, $formula, $bonus]);

    // Redirecionar após a adição
    header("Location: view_ficha.php?id=$id");
    exit;
}

// Verificar se a solicitação de exclusão foi feita
if (isset($_GET['delete_pericia'])) {
    $pericia_id = $_GET['delete_pericia'];

    // Preparar e executar a consulta para excluir a perícia
    $stmt = $pdo->prepare("DELETE FROM pericias WHERE id = ?");
    if ($stmt->execute([$pericia_id])) {
        // Debug: Verifique o valor de $id
        header("Location: view_ficha.php?id=$id");
        exit;
    } else {
        echo "Erro ao excluir a perícia.";
    }
}

// Verificar se a solicitação de edição de tema de poder foi feita
if (isset($_POST['edit_power_theme'])) {
    $titulo = $_POST['titulo'];
    $tema = $_POST['tema'];
    $descricao = $_POST['descricao'];
    $tema_id = $_POST['tema_id']; // ID do tema a ser editado

    // Preparar e executar a consulta para atualizar o tema
    $stmt = $pdo->prepare("UPDATE temas_de_poder SET titulo = ?, tema = ?, descricao = ? WHERE id = ?");
    $stmt->execute([$titulo, $tema, $descricao, $tema_id]);

    // Atualizar rótulos de poder
    if (!empty($_POST['poder_nome'])) {
        foreach ($_POST['poder_nome'] as $index => $nome) {
            $nivel = $_POST['poder_nivel'][$index];
            $efeito = $_POST['poder_efeito'][$index];
            if (!empty($nome)) {
                // Verifica se o rótulo já existe e atualiza ou insere
                $stmt = $pdo->prepare("INSERT INTO rotulos_poder (tema_id, nome, nivel, efeito) VALUES (?, ?, ?, ?)");
                $stmt->execute([$tema_id, $nome, $nivel, $efeito]);
            }
        }
    }

    // Atualizar rótulos de fraqueza
    if (!empty($_POST['fraqueza_nome'])) {
        foreach ($_POST['fraqueza_nome'] as $index => $nome) {
            $nivel = $_POST['fraqueza_nivel'][$index];
            $efeito = $_POST['fraqueza_efeito'][$index];
            if (!empty($nome)) {
                // Verifica se o rótulo já existe e atualiza ou insere
                $stmt = $pdo->prepare("INSERT INTO rotulos_fraqueza (tema_id, nome, nivel, efeito) VALUES (?, ?, ?, ?)");
                $stmt->execute([$tema_id, $nome, $nivel, $efeito]);
            }
        }
 }

    // Redirecionar após a edição
    header("Location: view_ficha.php?id=$id");
    exit;
}

// Atualizar tema
if (isset($_POST['edit_power_theme'])) {
    $tema_id = $_POST['tema_id']; // ID do tema a ser editado
    $titulo = $_POST['titulo'];
    $tema = $_POST['tema'];
    $descricao = $_POST['descricao'];

    $stmt = $pdo->prepare("UPDATE temas_de_poder SET titulo = ?, tema = ?, descricao = ? WHERE id = ?");
    $stmt->execute([$titulo, $tema, $descricao, $tema_id]);
    
    header("Location: view_ficha.php?id=$id");
    exit;
}

// Verificar se a solicitação de exclusão foi feita
if (isset($_GET['delete_tema'])) {
    $tema_id = $_GET['delete_tema'];

    // Excluir rótulos de poder associados
    $stmt = $pdo->prepare("DELETE FROM rotulos_poder WHERE tema_id = ?");
    $stmt->execute([$tema_id]);

    // Excluir rótulos de fraqueza associados
    $stmt = $pdo->prepare("DELETE FROM rotulos_fraqueza WHERE tema_id = ?");
    $stmt->execute([$tema_id]);

    // Agora, excluir o tema
    $stmt = $pdo->prepare("DELETE FROM temas_de_poder WHERE id = ?");
    if ($stmt->execute([$tema_id])) {
        header("Location: view_ficha.php?id=$id");
        exit;
    } else {
        echo "Erro ao excluir o tema.";
    }
}

// Captura o ID da ficha a ser visualizada
$id = $_GET['id'] ?? null; // Usando o operador de coalescência nula para evitar erros
if (!$id) {
    die("ID não fornecido.");
}

// Prepara e executa a consulta para obter os dados da ficha
$stmt = $pdo->prepare("SELECT * FROM fichas WHERE id = ?");
$stmt->execute([$id]);
$ficha = $stmt->fetch();
// Verifica se a ficha foi encontrada
if (!$ficha) {
    echo "Ficha não encontrada.";
    exit;
}

// Debug: Verifique o conteúdo da ficha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    // Captura os valores dos campos
    $pvs_atual = $_POST['pvs_atual'];
    $pss_atual = $_POST['pss_atual'];
    $pes_atual = $_POST['pes_atual'];

    // Atualiza os dados na tabela 'fichas'
    $stmt = $pdo->prepare("UPDATE fichas SET pvs_atual = ?, pss_atual = ?, pes_atual = ? WHERE id = ?");
    $stmt->execute([$pvs_atual, $pss_atual, $pes_atual, $id]); // Certifique-se de que $id está definido

    // Redireciona após a atualização
    header("Location: view_ficha.php?id=$id");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Ficha</title>
    
    <!-- Importando CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Importando Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#343d32] text-gray-300" id="imaotr">
    <div class="container mx-auto p-4" id="iuy77h">


<!-- Incluindo os modais no corpo da página -->
<?php include 'modals/edit_appearance_modal.php'; ?>
<?php include 'modals/edit_attributes_modal.php'; ?>
<?php include 'modals/edit_seed_modal.php'; ?>
<?php include 'modals/edit_power_theme_modal.php'; ?>
<?php include 'modals/add_power_themes_modal.php'; ?>
<?php include 'modals/edit_power_cards_modal.php'; ?>
<?php include 'modals/add_power_cards_modal.php'; ?>
<?php include 'modals/edit_points_modal.php'; ?>
<?php include 'modals/edit_background_modal.php'; ?>
<?php include 'modals/edit_skills_modal.php'; ?>
<?php include 'modals/add_skills_modal.php'; ?>
<?php include 'modals/edit_equipment_modal.php'; ?>
<?php include 'modals/add_equipment_modal.php'; ?>
<?php include 'modals/edit_advantages_disadvantages_modal.php'; ?>
<?php include 'modals/add_advantages_disadvantages_modal.php'; ?>
<?php include 'modals/edit_allies_enemies_modal.php'; ?>
<?php include 'modals/add_allies_enemies_modal.php'; ?>
<?php include 'modals/edit_notes_modal.php'; ?>
<?php include 'modals/add_notes_modal.php'; ?>

        <div class="flex flex-wrap gap-4" id="i9oqhp">
            <div class="Aparencia" id="i6il4d">
                <h1 class="text-3xl font-bold text-yellow-500" id="id4hap">
                    <?= htmlspecialchars($ficha['nome']) ?>
                </h1>
                <img src="<?= htmlspecialchars($ficha['aparencia_personalidade']) ?>" alt="Aparência do Personagem" id="ivdif2" class="mx-auto rounded-full mt-4"/>
            </div>
            <div class="flex flex-wrap gap-4">
                <?php include 'modals/edit_appearance_modal.php'; ?>
                <div class="bg-[#2c332b] p-4 rounded Aparencia">
                    <h2 class="text-2xl font-semibold text-yellow-500">Aparência</h2>
                    <p><strong>Idade:</strong> <?= htmlspecialchars($ficha['idade']) ?></p>
                    <p><strong>Altura:</strong> <?= htmlspecialchars($ficha['altura']) ?></p>
                    <p><strong>Peso:</strong> <?= htmlspecialchars($ficha['peso']) ?></p>
                    <p><strong>Cabelos:</strong> <?= htmlspecialchars($ficha['cabelos']) ?></p>
                    <p><strong>Olhos:</strong> <?= htmlspecialchars($ficha['olhos']) ?></p>
                    <button onclick="openModal('editAppearanceModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Aparência</button>
                </div>
                <div class="bg-[#2c332b] p-4 rounded atributos">
                    <h2 class="text-2xl font-semibold text-yellow-500">Atributos</h2>
                    <ul class="list-disc list-inside">
                        <li>
                            <i class="fas fa-fist-raised text-yellow-500"></i> <!-- Ícone para Força -->
                            <strong>Força:</strong> 
                            <span><?= htmlspecialchars(($ficha['for'] ?? 0) + ($ficha['forca_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['for'] ?? 0) + ($ficha['forca_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['for']) ?>, Adicional: <?= htmlspecialchars($ficha['forca_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-running text-yellow-500"></i> <!-- Ícone para Destreza -->
                            <strong>Destreza:</strong> 
                            <span><?= htmlspecialchars(($ficha['des'] ?? 0) + ($ficha['destreza_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['des'] ?? 0) + ($ficha['destreza_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['des']) ?>, Adicional: <?= htmlspecialchars($ficha['destreza_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-heart text-yellow-500"></i> <!-- Ícone para Constituição -->
                            <strong>Constituição:</strong> 
                            <span><?= htmlspecialchars(($ficha['con'] ?? 0) + ($ficha['constituicao_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['con'] ?? 0) + ($ficha['constituicao_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['con']) ?>, Adicional: <?= htmlspecialchars($ficha['constituicao_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-brain text-yellow-500"></i> <!-- Ícone para Inteligência -->
                            <strong>Inteligência:</strong> 
                            <span><?= htmlspecialchars(($ficha['int'] ?? 0) + ($ficha['inteligencia_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['int'] ?? 0) + ($ficha['inteligencia_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['int']) ?>, Adicional: <?= htmlspecialchars($ficha['inteligencia_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-eye text-yellow-500"></i> <!-- Ícone para Sabedoria -->
                            <strong>Sabedoria:</strong> 
                            <span><?= htmlspecialchars(($ficha['sab'] ?? 0) + ($ficha['sabedoria_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['sab'] ?? 0) + ($ficha['sabedoria_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['sab']) ?>, Adicional: <?= htmlspecialchars($ficha['sabedoria_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-smile text-yellow-500"></i> <!-- Ícone para Carisma -->
                            <strong>Carisma:</strong> 
                            <span><?= htmlspecialchars(($ficha['car'] ?? 0) + ($ficha['carisma_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['car'] ?? 0) + ($ficha['carisma_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['car']) ?>, Adicional: <?= htmlspecialchars($ficha['carisma_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                        <li>
                            <i class="fas fa-star text-yellow-500"></i> <!-- Ícone para Poder -->
                            <strong>Poder:</strong> 
                            <span><?= htmlspecialchars(($ficha['poder'] ?? 0) + ($ficha['poder_adicional'] ?? 0)) ?></span>
                            <span class="tooltip">
                                <i class="fas fa-info-circle text-yellow-500" style="cursor: pointer;"></i> <!-- Ícone de Informação -->
                                <span class="tooltiptext">Total: <?= htmlspecialchars(($ficha['poder'] ?? 0) + ($ficha['poder_adicional'] ?? 0)) ?>, Base: <?= htmlspecialchars($ficha['poder']) ?>, Adicional: <?= htmlspecialchars($ficha['poder_adicional'] ?? 0) ?></span>
                            </span>
                        </li>
                    </ul>
                    <button onclick="openModal('editAttributesModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Atributos</button>
                </div>
                <div class="bg-[#2c332b] p-4 rounded status-container">
                    <h2 class="text-2xl font-semibold text-yellow-500">Status</h2>
                    <form method="POST" id="statusForm">
                        <ul class="list-disc list-inside">
                            <li class="mb-2">
                                <strong>PVs Atuais:</strong>
                                <input type="number" name="pvs_atual" id="pvs_atual" 
                                       value="<?= htmlspecialchars($ficha['pvs_atual']) ?>" 
                                       required 
                                       class="bg-[#343d32] text-white p-1 rounded w-full"/>
                                <span class="text-gray-400">
                                    (Máx: <?= htmlspecialchars(($ficha['for'] + ($ficha['con'] * 2) + $ficha['pvs_adicionais']) * 3) ?>)
                                </span>
                            </li>
                            <li class="mb-2">
                                <strong>PSs Atuais:</strong>
                                <input type="number" name="pss_atual" id="pss_atual" 
                                       value="<?= htmlspecialchars($ficha['pss_atual']) ?>" 
                                       required 
                                       class="bg-[#343d32] text-white p-1 rounded w-full"/>
                                <span class="text-gray-400">
                                    (Máx: <?= htmlspecialchars(($ficha['sab'] + ($ficha['con'] * 2) + $ficha['pss_adicionais']) * 3) ?>)
                                </span>
                            </li>
                            <li class="mb-2">
                                <strong>PES Atuais:</strong>
                                <input type="number" name="pes_atual" id="pes_atual" 
                                       value="<?= htmlspecialchars($ficha['pes_atual']) ?>" 
                                       required 
                                       class="bg-[#343d32] text-white p-1 rounded w-full"/>
                                <span class="text-gray-400">
                                    (Máx: <?= htmlspecialchars($ficha['int'] + floor($ficha['poder'] * M_PI) + $ficha['pes_adicionais']) ?>)
                                </span>
                            </li>
                        </ul>
                        <button type="submit" name="update_status" class="w-full bg-yellow-500 text-white p-2 rounded mt-2 hover:bg-yellow-600 transition duration-200">Atualizar Status</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="ixuhqr" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            <div class="seed bg-[#2c332b] p-4 rounded" id="iwdkrf">
                <h2 class="text-2xl font-semibold text-yellow-500">Seed</h2>
                <?php if ($seed): ?>
                    <p><i class="fas fa-map-marker-alt text-yellow-500"></i> <strong>Plano de Origem:</strong> <?= htmlspecialchars($seed['plano_origem']) ?></p>
                    <p><i class="fas fa-seedling text-yellow-500"></i> <strong>Tipo de Seed:</strong> <?= htmlspecialchars($seed['tipo_seed']) ?></p>
                    <p><i class="fas fa-id-card text-yellow-500"></i> <strong>Cartas de Poder:</strong> <?= htmlspecialchars($seed['cartas_de_poder']) ?></p>
                    <p><i class="fas fa-coins text-yellow-500"></i> <strong>Custo PE:</strong> <?= htmlspecialchars($seed['custo_pe']) ?></p>
                    <p><i class="fas fa-exchange-alt text-yellow-500"></i> <strong>Controle:</strong> <?= htmlspecialchars($seed['controle_classe'] . ' , ' . $seed['facilidade_controle']) ?></p>
                    <p><i class="fas fa-bolt text-yellow-500"></i> <strong>Poder:</strong> <?= htmlspecialchars($seed['poder_inato_classe'] . ' , ' . $seed['poder_inato']) ?></p>
                    <p><i class="fas fa-gem text-yellow-500"></i> <strong>Patrono ou Item:</strong> <?= htmlspecialchars($seed['patrono_item']) ?></p>
                    <p><i class="fas fa-exclamation-triangle text-yellow-500"></i> <strong>Classe de Ameaça do Patrono:</strong> <?= htmlspecialchars($seed['classe_ameaca']) ?></p>
                    <p><i class="fas fa-arrow-up text-yellow-500"></i> <strong>Bônus:</strong> <?= htmlspecialchars($seed['atributo1'] . ', ' . $seed['atributo2'] . ', ' . $seed['atributo3']) ?></p>
                    <button onclick="openModal('editSeedModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar</button>
                <?php else: ?>
                    <p>Nenhuma Seed encontrada. Adicione uma nova Seed:</p>
                    <button onclick="openModal('editSeedModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Seed</button>
                <?php endif; ?>
            </div>
            <div class="temas-de-poder" id="im4gma">
                <h2 class="text-2xl font-semibold text-yellow-500">Temas de Poder</h2>
                <div class="flex flex-wrap gap-4">
                    <?php foreach ($temas_de_poder as $tema): ?>
                        <div class="bg-[#2c332b] p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 mb-4">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-magic text-yellow-500 text-3xl mr-3"></i>
                                <h3 class="text-xl font-bold text-yellow-500"><?= htmlspecialchars($tema['titulo']) ?> (<?= htmlspecialchars($tema['tema']) ?>)</h3>
                            </div>
                            <p class="text-gray-300 mb-2"><strong>Descrição:</strong> <?= htmlspecialchars($tema['descricao']) ?></p>
                            <h4 class="font-semibold text-yellow-500">Rótulos de Poder:</h4>
                            <ul class="list-disc list-inside mb-4">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM rotulos_poder WHERE tema_id = ?");
                                $stmt->execute([$tema['id']]);
                                $rotulos_poder = $stmt->fetchAll();
                                foreach ($rotulos_poder as $rotulo): ?>
                                    <li class="text-gray-300">
                                        <?= htmlspecialchars($rotulo['nome']) ?> (Nível: <?= htmlspecialchars($rotulo['nivel']) ?>) - <?= htmlspecialchars($rotulo['efeito']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <h4 class="font-semibold text-yellow-500">Rótulos de Fraqueza:</h4>
                            <ul class="list-disc list-inside text-gray-300">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM rotulos_fraqueza WHERE tema_id = ?");
                                $stmt->execute([$tema['id']]);
                                $rotulos_fraqueza = $stmt->fetchAll();
                                foreach ($rotulos_fraqueza as $rotulo): ?>
                                    <li>
                                        <?= htmlspecialchars($rotulo['nome']) ?> (Nível: <?= htmlspecialchars($rotulo['nivel']) ?>) - <?= htmlspecialchars($rotulo['efeito']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <button onclick="openModal('editPowerThemesModal<?= $tema['id'] ?>')" class="text-yellow-500">Editar</button>
                            <?php include 'modals/edit_power_theme_modal.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button onclick="openModal('addPowerThemesModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Tema de Poder</button>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mt-4">
            <div class="cartas-de-poder">
                <h2 class="text-2xl font-semibold text-yellow-500">Cartas de Poder</h2>
                <div class="flex flex-wrap gap-4">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM cartas_de_poder WHERE ficha_id = ?");
                    $stmt->execute([$id]);
                    $cartas = $stmt->fetchAll();
                    foreach ($cartas as $carta): ?>
                        <div class="bg-[#2c332b] p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 mb-4">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-book text-yellow-500 text-3xl mr-3"></i>
                                <h2 class="text-2xl font-bold text-yellow-500"><?= htmlspecialchars($carta['titulo']) ?></h2>
                            </div>
                            <?php
                            $stmtTemas = $pdo->prepare("SELECT t.titulo FROM cartas_tematicas ct JOIN temas_de_poder t ON ct.tema_id = t.id WHERE ct.carta_id = ?");
                            $stmtTemas->execute([$carta['id']]);
                            $temas = $stmtTemas->fetchAll();
                            ?>
                            <p class="text-gray-300 mb-2"><i class="fas fa-bookmark mr-2"></i><strong>Temas:</strong> 
                                <?php if ($temas): ?>
                                    <?= implode(', ', array_map(fn($tema) => htmlspecialchars($tema['titulo']), $temas)); ?>
                                <?php else: ?>
                                    Nenhum tema associado.
                                <?php endif; ?>
                            </p>
                            <p class="text-gray-300 mb-2"><i class="fas fa-bolt mr-2"></i><strong>Gasto de PE:</strong> <?= htmlspecialchars($carta['gasto_pe']) ?></p>
                            <p class="text-gray-300 mb-2"><i class="fas fa-hourglass-start mr-2"></i><strong>Tempo:</strong> <?= htmlspecialchars($carta['tempo']) ?></p>
                            <p class="text-gray-300 mb-4"><i class="fas fa-info-circle mr-2"></i><strong>Descrição:</strong> <?= htmlspecialchars($carta['descricao']) ?></p>
                            <h3 class="text-xl font-semibold mb-2 text-yellow-500"><i class="fas fa-star mr-2"></i>Efeitos Básicos:</h3>
                            <ul class="list-disc list-inside text-gray-300 mb-4">
                                <?php 
                                $efeitos_basicos = json_decode($carta['efeitos_basicos'], true);
                                if ($efeitos_basicos): 
                                    foreach ($efeitos_basicos as $efeito): ?>
                                        <li> <?= htmlspecialchars($efeito) ?></li>
                                <?php endforeach; 
                                else: ?>
                                    <li>Nenhum efeito básico.</li>
                                <?php endif; ?>
                            </ul>
                            <h3 class="text-xl font-semibold mb-2 text-yellow-500"><i class="fas fa-plus-circle mr-2"></i>Efeitos Adicionais:</h3>
                            <ul class="list-disc list-inside text-gray-300">
                                <?php 
                                $efeitos_adicionais = json_decode($carta['efeitos_adicionais'], true);
                                if ($efeitos_adicionais): 
                                    foreach ($efeitos_adicionais as $efeito): ?>
                                        <li> <?= htmlspecialchars($efeito) ?></li>
                                <?php endforeach; 
                                else: ?>
                                    <li>Nenhum efeito adicional.</li>
                                <?php endif; ?>
                            </ul>
                            <div class="mt-4">
                                <button onclick="openModal('editCardModal<?= $carta['id'] ?>')" class="text-yellow-500">Editar</button>
                                <a href="?delete_card=<?= $carta['id'] ?>" class="text-red-500" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button onclick="openModal('addCardModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Carta de Poder</button>
            </div>
        </div>

        <!-- Div Horizontal 5 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4" id="ixuhqr">
            <div class="pontos-narrativos bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Pontos Narrativos</h2>
                <p><?= htmlspecialchars($ficha['pontos_narrativos']) ?></p>
                <button onclick="openModal('editPointsModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Pontos Narrativos</button>
            </div>
            <div class="pontos-acao bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Pontos de Ação</h2>
                <p><?= htmlspecialchars($ficha['pontos_acao']) ?></p>
                <button onclick="openModal('editPointsModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Pontos de Ação</button>
            </div>
            <div class="pontos-xp bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Pontos de XP</h2>
                <p><?= htmlspecialchars($ficha['pontos_xp']) ?></p>
                <button onclick="openModal('editPointsModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Pontos de XP</button>
            </div>
            <div class="patente mt-4">
                <h2 class="text-2xl font-semibold text-yellow-500">Patente</h2>
                <p><?= htmlspecialchars($ficha['patente']) ?></p>
                <button onclick="openModal('editPointsModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Patente</button>
            </div>
        </div>

        <!-- Div Horizontal 6 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4" id="i5t87i">
            <div class="sonhos bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Sonhos</h2>
                <ul class="list-disc list-inside">
                    <?php foreach ($sonhos as $sonho): ?>
                    <li><?= htmlspecialchars($sonho['sonho']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="openModal('editBackgroundModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Sonhos</button>
            </div>
            <div class="medos bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Medos</h2>
                <ul class="list-disc list-inside">
                    <?php foreach ($medos as $medo): ?>
                    <li><?= htmlspecialchars($medo['medo']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="openModal('editBackgroundModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar Medos</button>
            </div>
        </div>

        <!-- Div Horizontal 7 -->
        <div class="mt-4" id="i5t87i">
            <div class="historia bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">História</h2>
                <p><?= nl2br(htmlspecialchars($ficha['historia'])) ?></p>
                <button onclick="openModal('editBackgroundModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Editar História</button>
            </div>
        </div>
        
        <!-- Div Horizontal 8 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <div class="pericias bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Perícias</h2>
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2"><i class="fas fa-user text-yellow-500"></i> Nome</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-level-up-alt text-yellow-500"></i> Nível Disponível</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-star text-yellow-500"></i> Nível da Perícia</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-calculator text-yellow-500"></i> Fórmula</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-plus text-yellow-500"></i> Bônus</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM pericias WHERE ficha_id = ?");
                        $stmt->execute([$id]);
                        $pericias = $stmt->fetchAll();
                
                        foreach ($pericias as $pericia): ?>
                            <tr>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($pericia['nome']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($pericia['nivel_disponivel']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($pericia['nivel_pericia']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($pericia['formula']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($pericia['bonus']) ?></td>
                                <td class='border border-gray-300 p-2'>
                                    <button onclick="openModal('editSkillsModal<?= $pericia['id'] ?>')" class='text-yellow-500'>
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <a href="?id=<?= $id ?>&delete_pericia=<?= $pericia['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button onclick="openModal('addSkillsModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Perícias</button>
            </div>
            <!-- Div Horizontal para Equipamentos -->
            <div class="equipamentos bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Equipamentos</h2>
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2"><i class="fas fa-box text-yellow-500"></i> Nome</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-sort-numeric-up text-yellow-500"></i> Quantidade</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-weight text-yellow-500"></i> Peso por Unidade</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Supondo que você já tenha uma consulta para obter os dados de equipamentos
                        $stmt = $pdo->prepare("SELECT * FROM equipamento WHERE ficha_id = ?");
                        $stmt->execute([$id]);
                        $equipamentos = $stmt->fetchAll();
            
                        foreach ($equipamentos as $equipamento): ?>
                            <tr>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($equipamento['nome']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($equipamento['quantidade']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($equipamento['peso_por_unidade']) ?></td>
                                <td class='border border-gray-300 p-2'>
                                    <button onclick="openEditEquipmentModal(<?= json_encode($equipamento) ?>)" class='text-yellow-500'>
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <a href="?delete_equipamento=<?= $equipamento['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button onclick="openModal('addEquipmentModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Equipamento</button>
            </div>
        </div>

        <!-- Div Horizontal 9 -->
        <div class="combate mt-4 bg-[#2c332b] p-4 rounded">
            <h2 class="text-2xl font-semibold text-yellow-500">Combate</h2>
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2"><i class="fas fa-user text-yellow-500"></i> Perícia</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-sword text-yellow-500"></i> Arma ou Poder</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-bolt text-yellow-500"></i> Ataque</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-star text-yellow-500"></i> Pontos</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-battery-full text-yellow-500"></i> Cargas</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-arrow-right text-yellow-500"></i> Ataque por Turno</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-ruler-combined text-yellow-500"></i> Distância</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-info-circle text-yellow-500"></i> Detalhes</th>
                        <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Supondo que você já tenha uma consulta para obter os dados de combate
                    $stmt = $pdo->prepare("SELECT * FROM combate WHERE ficha_id = ?");
                    $stmt->execute([$id]);
                    $combate = $stmt->fetchAll();
        
                    foreach ($combate as $c): ?>
                        <tr>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['pericia']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['arma_ou_poder']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['ataque']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['pts']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['cargas']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['ataque_por_turno']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['distancia']) ?></td>
                            <td class='border border-gray-300 p-2'><?= htmlspecialchars($c['detalhes']) ?></td>
                            <td class='border border-gray-300 p-2'>
                                <button onclick="openEditCombatModal(<?= json_encode($c) ?>)" class='text-yellow-500'>
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <a href="?delete_combate=<?= $c['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="openModal('addCombatModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Combate</button>
        </div>

        <!-- Div Horizontal 10 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
    <!-- Tabela de Vantagens -->
    <div class="vantagens bg-[#2c332b] p-4 rounded">
        <h2 class="text-2xl font-semibold text-yellow-500">Vantagens</h2>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2"><i class="fas fa-check-circle text-yellow-500"></i> Nome</th>
                    <th class="border border-gray-300 p-2"><i class="fas fa-info-circle text-yellow-500"></i> Descrição</th>
                    <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vantagens as $vantagem): ?>
                    <tr>
                        <td class='border border-gray-300 p-2'><?= htmlspecialchars($vantagem['nome']) ?></td>
                        <td class='border border-gray-300 p-2'><?= htmlspecialchars($vantagem['descricao']) ?></td>
                        <td class='border border-gray-300 p-2'>
                            <button onclick="openEditAdvantagesDisadvantagesModal(<?= json_encode($vantagem) ?>)" class='text-yellow-500'>
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <a href="?delete_vantagem=<?= $vantagem['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                <i class="fas fa-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="openModal('addAdvantagesDisadvantagesModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Vantagem</button>
    </div>

    <!-- Tabela de Desvantagens -->
    <div class="desvantagens bg-[#2c332b] p-4 rounded">
        <h2 class="text-2xl font-semibold text-yellow-500">Desvantagens</h2>
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2"><i class="fas fa-exclamation-triangle text-yellow-500"></i> Nome</th>
                    <th class="border border-gray-300 p-2"><i class="fas fa-info-circle text-yellow-500"></i> Descrição</th>
                    <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($desvantagens as $desvantagem): ?>
                    <tr>
                        <td class='border border-gray-300 p-2'><?= htmlspecialchars($desvantagem['nome']) ?></td>
                        <td class='border border-gray-300 p-2'><?= htmlspecialchars($desvantagem['descricao']) ?></td>
                        <td class='border border-gray-300 p-2'>
                            <button onclick="openEditAdvantagesDisadvantagesModal(<?= json_encode($desvantagem) ?>)" class='text-yellow-500'>
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <a href="?delete_desvantagem=<?= $desvantagem['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                <i class="fas fa-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="openModal('addAdvantagesDisadvantagesModal')" class="mt-2 w-full bg-red-800 text-white p-2 rounded">Adicionar Desvantagem</button>
    </div>
</div>

        <!-- Div Horizontal 11 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <!-- Tabela de Aliados -->
            <div class="aliados bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Aliados</h2>
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2"><i class="fas fa-user-friends text-yellow-500"></i> Nome</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-link text-yellow-500"></i> Relação</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aliados as $aliado): ?>
                            <tr>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($aliado['nome']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($aliado['relacao']) ?></td>
                                <td class='border border-gray-300 p-2'>
                                    <button onclick="openEditAlliesEnemiesModal(<?= json_encode($aliado) ?>)" class='text-yellow-500'>
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <a href="?delete_aliado=<?= $aliado['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button onclick="openModal('addAlliesEnemiesModal')" class="mt-2 w-full bg-green-800 text-white p-2 rounded">Adicionar Aliado</button>
            </div>
        
            <!-- Tabela de Inimigos -->
            <div class="inimigos bg-[#2c332b] p-4 rounded">
                <h2 class="text-2xl font-semibold text-yellow-500">Inimigos</h2>
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2"><i class="fas fa-user-slash text-yellow-500"></i> Nome</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-link text-yellow-500"></i> Relação</th>
                            <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inimigos as $inimigo): ?>
                            <tr>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($inimigo['nome']) ?></td>
                                <td class='border border-gray-300 p-2'><?= htmlspecialchars($inimigo['relacao']) ?></td>
                                <td class='border border-gray-300 p-2'>
                                    <button onclick="openEditAlliesEnemiesModal(<?= json_encode($inimigo) ?>)" class='text-yellow-500'>
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <a href="?delete_inimigo=<?= $inimigo['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                                        <i class="fas fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button onclick="openModal('addAlliesEnemiesModal')" class="mt-2 w-full bg-red-800 text-white p-2 rounded">Adicionar Inimigo</button>
            </div>
        </div>
        <!-- Anotações -->
<div class="anotacoes mt-4 bg-[#2c332b] p-4 rounded" id="ipnnd7">
    <h2 class="text-2xl font-semibold text-yellow-500" id="im38ip">Anotações</h2>

    <!-- Barra de Ferramentas Markdown -->
    <div class="navigation-bar__inner navigation-bar__inner--edit-pagedownButtons mb-2">
        <button class="navigation-bar__button button" title="Negrito – Ctrl+Shift+B" aria-label="Negrito – Ctrl+Shift+B" onclick="formatText('bold')">
            <i class="fas fa-bold text-yellow-500"></i>
        </button>
        <button class="navigation-bar__button button" title="Itálico – Ctrl+Shift+I" aria-label="Itálico – Ctrl+Shift+I" onclick="formatText('italic')">
            <i class="fas fa-italic text-yellow-500"></i>
        </button>
        <button class="navigation-bar__button button" title="Lista Não Ordenada – Ctrl+Shift+U" aria-label="Lista Não Ordenada – Ctrl+Shift+U" onclick="formatText('unordered-list')">
            <i class="fas fa-list text-yellow-500"></i>
        </button>
        <button class="navigation-bar__button button" title="Lista Ordenada – Ctrl+Shift+O" aria-label="Lista Ordenada – Ctrl+Shift+O" onclick="formatText('ordered-list')">
            <i class="fas fa-list-ol text-yellow-500"></i>
        </button>
        <button class="navigation-bar__button button" title="Citar – Ctrl+Shift+Q" aria-label="Citar – Ctrl+Shift+Q" onclick="formatText('blockquote')">
            <i class="fas fa-quote-right text-yellow-500"></i>
        </button>
    </div>

    <!-- Formulário para Adicionar Anotações -->
    <form method="POST" class="mb-2">
        <textarea name="conteudo_anotacao" id="anotacoes" placeholder="Digite sua anotação" required class="w-full p-2 border rounded"></textarea>
        <button type="submit" name="add_anotacao" class="w-full bg-green-800 text-white p-2 rounded">Adicionar Anotação</button>
    </form>

    <!-- Tabela de Anotações -->
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2"><i class="fas fa-sticky-note text-yellow-500"></i> Conteúdo</th>
                <th class="border border-gray-300 p-2"><i class="fas fa-cogs text-yellow-500"></i> Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anotacoes as $anotacao): ?>
                <tr>
                    <td class='border border-gray-300 p-2'><?= markdown_to_html($anotacao['conteudo']) ?></td>
                    <td class='border border-gray-300 p-2'>
                        <button onclick="openEditNotesModal(<?= json_encode($anotacao) ?>)" class='text-yellow-500'>
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <a href="?delete_anotacao=<?= $anotacao['id'] ?>" class='text-red-500' onclick="return confirm('Tem certeza que deseja excluir?');">
                            <i class="fas fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="actions mt-6 text-center">
        <a href="edit_ficha.php?id=<?= $ficha['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="dashboard.php" class="bg-yellow-500 text-white px-4 py-2 rounded">Voltar</a>
    </div>
</div>
</div>

    <script>
            function openModal(modalId) {
            console.log(`Tentando abrir o modal com ID: ${modalId}`); // Para depuração
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.error(`Modal com ID ${modalId} não encontrado.`);
            }
        }
        
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }
        // Fechar o modal ao clicar fora dele
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>