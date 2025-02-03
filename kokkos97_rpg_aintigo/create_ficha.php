<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o usuário é mestre ou jogador
if ($_SESSION['funcao'] != 1 && $_SESSION['funcao'] != 2) {
    echo "Você não tem permissão para criar fichas.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $altura = $_POST['altura'];
    $peso = $_POST['peso'];
    $cabelos = $_POST['cabelos'];
    $olhos = $_POST['olhos'];
    $aparencia_personalidade = $_POST['aparencia_personalidade']; // Link da imagem
    $jogador = $_POST['jogador'];
    $forca = $_POST['for'];
    $destreza = $_POST['des'];
    $constituicao = $_POST['con'];
    $inteligencia = $_POST['int'];
    $sabedoria = $_POST['sab'];
    $carisma = $_POST['car'];
    $poder = $_POST['poder'];
    $pvs = $_POST['pvs'];
    $pes = $_POST['pes'];
    $pss = $_POST['pss'];
    $historia = $_POST['historia']; // Captura a história do personagem
    $usuario_id = $_SESSION['usuario_id'];
    $tipo = $_POST['tipo']; // Captura o tipo da ficha (jogador ou npc)

    // Validação do tipo
    if ($tipo !== 'jogador' && $tipo !== 'npc') {
        echo "Tipo de ficha inválido.";
        exit;
    }

    try {
        // Insere a ficha
        $stmt = $pdo->prepare("INSERT INTO fichas (nome, idade, altura, peso, cabelos, olhos, aparencia_personalidade, jogador, `for`, `des`, `con`, `int`, `sab`, `car`, poder, pvs, pes, pss, historia, tipo, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Parâmetros para a consulta
        $params = [
            $nome, $idade, $altura, $peso, $cabelos, $olhos, $aparencia_personalidade, $jogador, 
            $forca, $destreza, $constituicao, $inteligencia, 
            $sabedoria, $carisma, $poder, $pvs, $pes, $pss, 
            $historia, // Adiciona a história aqui
            $tipo, // Adiciona o tipo da ficha
            $usuario_id
        ];

        // Executa a consulta
        if ($stmt->execute($params)) {
            $ficha_id = $pdo->lastInsertId();

            // Insere os sonhos
            foreach ($sonhos as $sonho) {
                $stmt = $pdo->prepare("INSERT INTO sonhos (ficha_id, sonho) VALUES (?, ?)");
                $stmt->execute([$ficha_id, $sonho]);
            }

            // Insere os medos
            foreach ($medos as $medo) {
                $stmt = $pdo->prepare("INSERT INTO medos (ficha_id, medo) VALUES (?, ?)");
                $stmt->execute([$ficha_id, $medo]);
            }

            // Redireciona para o dashboard após a inserção bem-sucedida
            header("Location: dashboard.php");
            exit;
        } 
     else {
            echo "Erro ao criar ficha.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Ficha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1a1a1a;
            color: #ecf0f1;
        }
        .container {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            max-width: 1200px;
            margin: auto;
        }
        h1, h2, h3 {
            color: #D4AF37;
        }
        .form-group input, .form-group textarea {
            background-color: #444;
            color: #ecf0f1;
            border: 1px solid #555;
        }
        .form-group button {
            background-color: #4A6E4D;
        }
        .form-group button:hover {
            background-color: #3b5540;
        }
        .actions a {
            background-color: #D4AF37;
        }
        .actions a:hover {
            background-color: #b08e2e;
        }
        .section-title {
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .attribute-container, .status-container {
            margin-bottom: 20px;
        }
        .attribute-container input, .status-container input {
            width: 50px;
            text-align: center;
        }
        .attribute-container button, .status-container button {
            margin-left: 5px;
        }
        .attribute-container-g, .status-container, .historia {
            margin-top: 20px;
        }
        .attribute-container-g, .status-container {
            width: 20%;
        }
        .historia {
            width: 40%;
        }
        .img-preview {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .img-preview img {
            max-width: 200px;
            border: 2px solid #D4AF37;
        }
    </style>
</head>
<body>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Ficha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1a1a1a;
            color: #ecf0f1;
        }
        .container {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            max-width: 1200px;
            margin: auto;
        }
        h1, h2, h3 {
            color: #D4AF37;
        }
        .form-group input, .form-group textarea {
            background-color: #444;
            color: #ecf0f1;
            border: 1px solid #555;
        }
        .form-group input::placeholder, .form-group textarea::placeholder {
            color: #888;
        }
        .form-group button {
            background-color: #4A6E4D;
        }
        .form-group button:hover {
            background-color: #3b5540;
        }
        .actions a {
            background-color: #D4AF37;
        }
        .actions a:hover {
            background-color: #b08e2e;
        }
        .section-title {
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .attribute-container, .status-container {
            margin-bottom: 20px;
        }
        .attribute-container input, .status-container input {
            width: 50px;
            text-align: center;
        }
        .attribute-container button, .status-container button {
            margin-left: 5px;
        }
        .attribute-container-g, .status-container, .historia {
            margin-top: 20px;
        }
        .attribute-container-g, .status-container {
            width: 20%;
        }
        .historia {
            width: 40%;
        }
        .img-preview {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .img-preview img {
            max-width: 200px;
            border: 2px solid #D4AF37;
        }
        button, input, optgroup, select, textarea {
            font-family: inherit;
            font-feature-settings: inherit;
            font-variation-settings: inherit;
            font-size: 100%;
            font-weight: inherit;
            line-height: inherit;
            letter-spacing: inherit;
            color: #4a6e4d;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-3xl font-bold text-center">Criar Ficha</h1>
        <form method="POST">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-4">
                    <div class="Aparencia">
                        <h3 class="text-xl font-semibold section-title">Aparência</h3>
                        <div class="Nome flex justify-between">
                            <input type="text" name="nome" placeholder="Nome do Personagem" required class="w-full p-2 mb-2 border rounded">
                            <input type="text" name="jogador" placeholder="Nome do Jogador" required class="w-full p-2 mb-2 border rounded">
                        </div>
                        <div class="img">
                            <input type="text" name="aparencia_personalidade" id="aparencia_personalidade" placeholder="Link da Imagem" oninput="previewImage()" class="w-full p-2 mb-2 border rounded">
                        </div>
                        <div class="Aparencia-sub flex justify-between">
                            <input type="number" name="idade" placeholder="Idade" required class="w-full p-2 mb-2 border rounded">
                            <input type="text" name="altura" placeholder="Altura" required class="w-full p-2 mb-2 border rounded">
                            <input type="text" name="peso" placeholder="Peso" required class="w-full p-2 mb-2 border rounded">
                            <input type="text" name="cabelos" placeholder="Cabelos" required class="w-full p-2 mb-2 border rounded">
                            <input type="text" name="olhos" placeholder="Olhos" required class="w-full p-2 mb-2 border rounded">
                        </div>
                        <div class="SM flex">
                            <div class="sonhos w-1/2">
                                <h3 class="text-xl font-semibold section-title">Sonhos</h3>
                                <div id="dreamsContainer">
                                    <div class="dream-input flex items-center">
                                        <input type="text" name="sonhos[]" placeholder="Digite um sonho" required class="w-full p-2 mb-2 border rounded">
                                        <button type="button" onclick="addDream()" class="bg-green-800 text-white p-2 rounded ml-2">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="medos w-1/2">
                                <h3 class="text-xl font-semibold section-title">Medos</h3>
                                <div id="fearsContainer">
                                    <div class="fear-input flex items-center">
                                        <input type="text" name="medos[]" placeholder="Digite um medo" required class="w-full p-2 mb-2 border rounded">
                                        <button type="button" onclick="addFear()" class="bg-green-800 text-white p-2 rounded ml-2">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1 img-preview">
                    <img id="imgPreview" src="" alt="Preview da Aparência" style="display:none;"/>
                </div>
            </div>
            <div class="mt-4">
                <label for="tipo" class="block text-xl font-semibold section-title">Tipo de Ficha:</label>
                <select name="tipo" required class="w-full p-2 mb-2 border rounded">
                    <option value="jogador">Jogador</option>
                    <option value="npc">NPC</option>
                </select>
            </div>
            <div class="geral flex justify-between mt-4">
                <div class="attribute-container-g">
                    <h3 class="text-xl font-semibold section-title">Atributos</h3>
                    <div id="pointsDisplay">Pontos disponíveis: 30</div>
                    <div class="attribute-container">
                        Força: 
                        <input type="number" id="for" value="0" readonly>
                        <input type="hidden" name="for" id="forHidden" value="0">
                        <button type="button" onclick="adjustAttribute('for', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('for', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Destreza: 
                        <input type="number" id="des" value="0" readonly>
                        <input type="hidden" name="des" id="desHidden" value="0">
                        <button type="button" onclick="adjustAttribute('des', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('des', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Constituição: 
                        <input type="number" id="con" value="0" readonly>
                        <input type="hidden" name="con" id="conHidden" value="0">
                        <button type="button" onclick="adjustAttribute('con', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('con', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Inteligência: 
                        <input type="number" id="int" value="0" readonly>
                        <input type="hidden" name="int" id="intHidden" value="0">
                        <button type="button" onclick="adjustAttribute('int', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('int', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Sabedoria: 
                        <input type="number" id="sab" value="0" readonly>
                        <input type="hidden" name="sab" id="sabHidden" value="0">
                        <button type="button" onclick="adjustAttribute('sab', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('sab', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Carisma: 
                        <input type="number" id="car" value="0" readonly>
                        <input type="hidden" name="car" id="carHidden" value="0">
                        <button type="button" onclick="adjustAttribute('car', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('car', -1)">-</button>
                    </div>
                    <div class="attribute-container">
                        Poder: 
                        <input type="number" id="poder" value="0" readonly>
                        <input type="hidden" name="poder" id="poderHidden" value="0">
                        <button type="button" onclick="adjustAttribute('poder', 1)">+</button>
                        <button type="button" onclick="adjustAttribute('poder', -1)">-</button>
                    </div>
                </div>
                <div class="status-container">
                    <h3 class="text-xl font-semibold section-title">Status</h3>
                    <div id="pvTotal">PVs Total: 0</div>
                    PVs Adicional: <input type="number" id="pvs" value="0" oninput="updatePVs()" class="w-full p-2 mb-2 border rounded">
                    <input type="hidden" name="pvs" id="pvsHidden" value="0">
                    
                    <div id="peTotal">PEs Total: 0</div>
                    PEs Adicional: <input type="number" id="pes" value="0" oninput="updatePEs()" class="w-full p-2 mb-2 border rounded">
                    <input type="hidden" name="pes" id="pesHidden" value="0">
                    
                    <div id="psTotal">PSs Total: 0</div>
                    PSs Adicional: <input type="number" id="pss" value="0" oninput="updatePSs()" class="w-full p-2 mb-2 border rounded">
                    <input type="hidden" name="pss" id="pssHidden" value="0">
                    
                    <button type="button" onclick="calculateTotals()" class="w-full bg-green-800 text-white p-2 rounded mt-2">Calcular Totais</button>
                </div>
                <div class="historia">
                    <h3 class="text-xl font-semibold section-title">História do Personagem</h3>
                    <textarea name="historia" placeholder="Conte a história do seu personagem..." required class="w-full p-2 mb-2 border rounded"></textarea>
                </div>
            </div>
            <button type="submit" class="w-full bg-green-800 text-white p-2 rounded mt-4">Salvar</button>
        </form>
    </div>
   <script src="script.js"></script>
</body>
</html>