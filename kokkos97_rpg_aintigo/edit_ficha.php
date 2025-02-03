<?php
session_start();
include 'db.php';

// Verifica se o usuário está autenticado e se é um mestre
if (!isset($_SESSION['usuario_id']) || $_SESSION['funcao'] != 1) {
    header("Location: login.php");
    exit;
}

// Captura o ID da ficha a ser visualizada
$id = $_GET['id'];

// Prepara e executa a consulta para obter os dados da ficha
$stmt = $pdo->prepare("SELECT * FROM fichas WHERE id = ?");
$stmt->execute([$id]);
$ficha = $stmt->fetch();

// Verifica se a ficha foi encontrada
if (!$ficha) {
    echo "Ficha não encontrada.";
    exit;
}

// Processa a atualização dos dados da ficha
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_ficha'])) {
        $nome = $_POST['nome'];
        $forca = $_POST['for'];
        $destreza = $_POST['des'];
        $constituicao = $_POST['con'];
        $inteligencia = $_POST['int'];
        $sabedoria = $_POST['sab'];
        $carisma = $_POST['car'];
        $poder = $_POST['poder'];
        $idade = $_POST['idade'];
        $altura = $_POST['altura'];
        $peso = $_POST['peso'];
        $cabelos = $_POST['cabelos'];
        $olhos = $_POST['olhos'];
        $seed = $_POST['seed'];
        $pontos_narrativos = $_POST['pontos_narrativos'];
        $pontos_acao = $_POST['pontos_acao'];
        $pontos_xp = $_POST['pontos_xp'];
        $historia = $_POST['historia'];
        $aparencia_personalidade = $_POST['aparencia_personalidade'];
        $patente = $_POST['patente'];

        try {
            // Atualiza os dados da ficha no banco de dados
            $stmt = $pdo->prepare("UPDATE fichas SET nome = ?, for = ?, des = ?, con = ?, int = ?, sab = ?, car = ?, poder = ?, idade = ?, altura = ?, peso = ?, cabelos = ?, olhos = ?, seed = ?, pontos_narrativos = ?, pontos_acao = ?, pontos_xp = ?, historia = ?, aparencia_personalidade = ?, patente = ? WHERE id = ?");
            $stmt->execute([$nome, $forca, $destreza, $constituicao, $inteligencia, $sabedoria, $carisma, $poder, $idade, $altura, $peso, $cabelos, $olhos, $seed, $pontos_narrativos, $pontos_acao, $pontos_xp, $historia, $aparencia_personalidade, $patente, $ficha['id']]);
            header("Location: edit_ficha.php?id=" . $ficha['id']);
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar ficha: " . $e->getMessage();
        }
    }
}
   
    // CRUD em Perícias
if (isset($_POST['add_pericia'])) {
    $nome_pericia = $_POST['nome_pericia'];
    $pts_pericia = $_POST['pts_pericia'];
    $valor_pericia = $_POST['valor_pericia'];
    $stmt = $pdo->prepare("INSERT INTO pericias (ficha_id, nome, pts, valor) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $nome_pericia, $pts_pericia, $valor_pericia]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_pericia'])) {
    $id_pericia = $_POST['id_pericia'];
    $nome_pericia = $_POST['nome_pericia'];
    $pts_pericia = $_POST['pts_pericia'];
    $valor_pericia = $_POST['valor_pericia'];
    $stmt = $pdo->prepare("UPDATE pericias SET nome = ?, pts = ?, valor = ? WHERE id = ?");
    $stmt->execute([$nome_pericia, $pts_pericia, $valor_pericia, $id_pericia]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_pericia'])) {
    $id_pericia = $_POST['id_pericia'];
    $stmt = $pdo->prepare("DELETE FROM pericias WHERE id = ?");
    $stmt->execute([$id_pericia]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Equipamentos
if (isset($_POST['add_equipamento'])) {
    $nome_equipamento = $_POST['nome_equipamento'];
    $quantidade_equipamento = $_POST['quantidade_equipamento'];
    $peso_por_unidade = $_POST['peso_por_unidade'];
    $stmt = $pdo->prepare("INSERT INTO equipamento (ficha_id, nome, quantidade, peso_por_unidade) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $nome_equipamento, $quantidade_equipamento, $peso_por_unidade]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_equipamento'])) {
    $id_equipamento = $_POST['id_equipamento'];
    $nome_equipamento = $_POST['nome_equipamento'];
    $quantidade_equipamento = $_POST['quantidade_equipamento'];
    $peso_por_unidade = $_POST['peso_por_unidade'];
    $stmt = $pdo->prepare("UPDATE equipamento SET nome = ?, quantidade = ?, peso_por_unidade = ? WHERE id = ?");
    $stmt->execute([$nome_equipamento, $quantidade_equipamento, $peso_por_unidade, $id_equipamento]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_equipamento'])) {
    $id_equipamento = $_POST['id_equipamento'];
    $stmt = $pdo->prepare("DELETE FROM equipamento WHERE id = ?");
    $stmt->execute([$id_equipamento]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Combate
if (isset($_POST['add_combate'])) {
    $pericia = $_POST['pericia'];
    $arma_ou_poder = $_POST['arma_ou_poder'];
    $ataque = $_POST['ataque'];
    $pts = $_POST['pts'];
    $cargas = $_POST['cargas'];
    $ataque_por_turno = $_POST['ataque_por_turno'];
    $distancia = $_POST['distancia'];
    $detalhes = $_POST['detalhes'];
    $stmt = $pdo->prepare("INSERT INTO combate (ficha_id, pericia, arma_ou_poder, ataque, pts, cargas, ataque_por_turno, distancia, detalhes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $pericia, $arma_ou_poder, $ataque, $pts, $cargas, $ataque_por_turno, $distancia, $detalhes]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_combate'])) {
    $id_combate = $_POST['id_combate'];
    $pericia = $_POST['pericia'];
    $arma_ou_poder = $_POST['arma_ou_poder'];
    $ataque = $_POST['ataque'];
    $pts = $_POST['pts'];
    $cargas = $_POST['cargas'];
    $ataque_por_turno = $_POST['ataque_por_turno'];
    $distancia = $_POST['distancia'];
    $detalhes = $_POST['detalhes'];
    $stmt = $pdo->prepare("UPDATE combate SET pericia = ?, arma_ou_poder = ?, ataque = ?, pts = ?, cargas = ?, ataque_por_turno = ?, distancia = ?, detalhes = ? WHERE id = ?");
    $stmt->execute([$pericia, $arma_ou_poder, $ataque, $pts, $cargas, $ataque_por_turno, $distancia, $detalhes, $id_combate]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_combate'])) {
    $id_combate = $_POST['id_combate'];
    $stmt = $pdo->prepare("DELETE FROM combate WHERE id = ?");
    $stmt->execute([$id_combate]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Vantagens
if (isset($_POST['add_vantagem'])) {
    $nome_vantagem = $_POST['nome_vantagem'];
    $descricao_vantagem = $_POST['descricao_vantagem'];
    $stmt = $pdo->prepare("INSERT INTO vantagens (ficha_id, nome, descricao) VALUES (?, ?, ?)");
    $stmt->execute([$id, $nome_vantagem, $descricao_vantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_vantagem'])) {
    $id_vantagem = $_POST['id_vantagem'];
    $nome_vantagem = $_POST['nome_vantagem'];
    $descricao_vantagem = $_POST['descricao_vantagem'];
    $stmt = $pdo->prepare("UPDATE vantagens SET nome = ?, descricao = ? WHERE id = ?");
    $stmt->execute([$nome_vantagem, $descricao_vantagem, $id_vantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_vantagem'])) {
    $id_vantagem = $_POST['id_vantagem'];
    $stmt = $pdo->prepare("DELETE FROM vantagens WHERE id = ?");
    $stmt->execute([$id_vantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Desvantagens
if (isset($_POST['add_desvantagem'])) {
    $nome_desvantagem = $_POST['nome_desvantagem'];
    $descricao_desvantagem = $_POST['descricao_desvantagem'];
    $stmt = $pdo->prepare("INSERT INTO desvantagens (ficha_id, nome, descricao) VALUES (?, ?, ?)");
    $stmt->execute([$id, $nome_desvantagem, $descricao_desvantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_desvantagem'])) {
    $id_desvantagem = $_POST['id_desvantagem'];
    $nome_desvantagem = $_POST['nome_desvantagem'];
    $descricao_desvantagem = $_POST['descricao_desvantagem'];
    $stmt = $pdo->prepare("UPDATE desvantagens SET nome = ?, descricao = ? WHERE id = ?");
    $stmt->execute([$nome_desvantagem, $descricao_desvantagem, $id_desvantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_desvantagem'])) {
    $id_desvantagem = $_POST['id_desvantagem'];
    $stmt = $pdo->prepare("DELETE FROM desvantagens WHERE id = ?");
    $stmt->execute([$id_desvantagem]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Aliados
if (isset($_POST['add_aliado'])) {
    $nome_aliado = $_POST['nome_aliado'];
    $relacao_aliado = $_POST['relacao_aliado'];
    $stmt = $pdo->prepare("INSERT INTO aliados (ficha_id, nome, relacao) VALUES (?, ?, ?)");
    $stmt->execute([$id, $nome_aliado, $relacao_aliado]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_aliado'])) {
    $id_aliado = $_POST['id_aliado'];
    $nome_aliado = $_POST['nome_aliado'];
    $relacao_aliado = $_POST['relacao_aliado'];
    $stmt = $pdo->prepare("UPDATE aliados SET nome = ?, relacao = ? WHERE id = ?");
    $stmt->execute([$nome_aliado, $relacao_aliado, $id_aliado]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_aliado'])) {
    $id_aliado = $_POST['id_aliado'];
    $stmt = $pdo->prepare("DELETE FROM aliados WHERE id = ?");
    $stmt->execute([$id_aliado]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Inimigos
if (isset($_POST['add_inimigo'])) {
    $nome_inimigo = $_POST['nome_inimigo'];
    $relacao_inimigo = $_POST['relacao_inimigo'];
    $stmt = $pdo->prepare("INSERT INTO inimigos (ficha_id, nome, relacao) VALUES (?, ?, ?)");
    $stmt->execute([$id, $nome_inimigo, $relacao_inimigo]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_inimigo'])) {
    $id_inimigo = $_POST['id_inimigo'];
    $nome_inimigo = $_POST['nome_inimigo'];
    $relacao_inimigo = $_POST['relacao_inimigo'];
    $stmt = $pdo->prepare("UPDATE inimigos SET nome = ?, relacao = ? WHERE id = ?");
    $stmt->execute([$nome_inimigo, $relacao_inimigo, $id_inimigo]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_inimigo'])) {
    $id_inimigo = $_POST['id_inimigo'];
    $stmt = $pdo->prepare("DELETE FROM inimigos WHERE id = ?");
    $stmt->execute([$id_inimigo]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Anotações
if (isset($_POST['add_anotacao'])) {
    $conteudo_anotacao = $_POST['conteudo_anotacao'];
    $stmt = $pdo->prepare("INSERT INTO anotacoes (ficha_id, conteudo) VALUES (?, ?)");
    $stmt->execute([$id, $conteudo_anotacao]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['update_anotacao'])) {
    $id_anotacao = $_POST['id_anotacao'];
    $conteudo_anotacao = $_POST['conteudo_anotacao'];
    $stmt = $pdo->prepare("UPDATE anotacoes SET conteudo = ? WHERE id = ?");
    $stmt->execute([$conteudo_anotacao, $id_anotacao]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}
if (isset($_POST['delete_anotacao'])) {
    $id_anotacao = $_POST['id_anotacao'];
    $stmt = $pdo->prepare("DELETE FROM anotacoes WHERE id = ?");
    $stmt->execute([$id_anotacao]);
    header("Location: edit_ficha.php?id=$id");
    exit;
}

// CRUD em Sonhos
if (isset($_POST['add_sonho'])) {
    $descricao_sonho = $_POST['descricao_sonho'];
    $stmt = $pdo->prepare("INSERT INTO sonhos (ficha_id, descricao) VALUES (?, ?)");
    $stmt->execute([$id, $descricao_sonho]);
    header("Location: edit_ficha.php?id=$id");
    return;
}
if (isset($_POST['update_sonho'])) {
    $id_sonho = $_POST['id_sonho'];
    $descricao_sonho = $_POST['descricao_sonho'];
    $stmt = $pdo->prepare("UPDATE sonhos SET descricao = ? WHERE id = ?");
    $stmt->execute([$descricao_sonho, $id_sonho]);
    header("Location: edit_ficha.php?id=$id");
    return;
}
if (isset($_POST['delete_sonho'])) {
    $id_sonho = $_POST['id_sonho'];
    $stmt = $pdo->prepare("DELETE FROM sonhos WHERE id = ?");
    $stmt->execute([$id_sonho]);
    header("Location: edit_ficha.php?id=$id");
    return;
}

// CRUD em Medos
if (isset($_POST['add_medo'])) {
    $descricao_medo = $_POST['descricao_medo'];
    $stmt = $pdo->prepare("INSERT INTO medos (ficha_id, descricao) VALUES (?, ?)");
    $stmt->execute([$id, $descricao_medo]);
    header("Location: edit_ficha.php?id=$id");
    return;
}
if (isset($_POST['update_medo'])) {
    $id_medo = $_POST['id_medo'];
    $descricao_medo = $_POST['descricao_medo'];
    $stmt = $pdo->prepare("UPDATE medos SET descricao = ? WHERE id = ?");
    $stmt->execute([$descricao_medo, $id_medo]);
    header("Location: edit_ficha.php?id=$id");
    return;
}
if (isset($_POST['delete_medo'])) {
    $id_medo = $_POST['id_medo'];
    $stmt = $pdo->prepare("DELETE FROM medos WHERE id = ?");
    $stmt->execute([$id_medo]);
    header("Location: edit_ficha.php?id=$id");
    return;
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
    $text = preg_replace('/-(.*)/', '<li>$1</li>', $text);

    // Lista ordenada
    $text = preg_replace('/(\d+)\. (.*)/', '<li>$2</li>', $text);

    // Citação
    $text = preg_replace('/>(.*)/', '<blockquote>$1</blockquote>', $text);

    // Links
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $text);

    return $text;
}

?>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Ficha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
* {
	box-sizing: border-box;
}
           * {
	box-sizing: border-box;
}

body {
	margin: 0;
}

body {
	font-family: 'Roboto', sans-serif;
	background-color: #1a1a1a;
	color: #ecf0f1;
	margin: 0;
}

.container {
	background-color: #2c2c2c;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
}

h1,
h2,
h3 {
	color: #D4AF37;
}

.header img {
	border: 2px solid #D4AF37;
}

.form-group input,
.form-group textarea {
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

* {
	box-sizing: border-box;
}

#pss_atual {
	color: #166534;
	width: 30%;
	min-width: 30%;
	max-width: 30%;
}

#pvs_atual {
	color: #166534;
	display: inline;
	justify-content: center;
	width: 30%;
	min-width: 30%;
	max-width: 30%;
}

#pes_atual {
	color: #166534;
	max-width: 30%;
	min-width: 30%;
	width: 30%;
}

#ivdif2 {
    max-width: 30vh;
    max-height: 30vh;
    min-height: 30vh;
    min-width: 30vh;
    width: 30vh;
    height: 30vh;
    object-fit: cover;
    object-position: top;

}

#i9oqhp {
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	display: flex;
	justify-content: space-between;
	align-items: stretch;
	flex-direction: row;
	align-content: flex-start;
}

#ig864d {
	flex: 0 0 auto;
	align-self: flex-start;
	display: block;
	width: 20%;
	min-width: 20%;
	max-width: 20%;
}

#i6il4d {
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
	flex: 0 0 auto;
	width: 30%;
	height: 30%;
	min-width: 30%;
	min-height: 30%;
	max-width: 30%;
	max-height: 30%;
}

#i35ntw {
	flex-direction: column;
}

#ixuhqr {
	display: flex;
	justify-content: space-evenly;
	align-items: flex-start;
}

#i5t87i {
	width: 40%;
	min-width: 40%;
	max-width: 40%;
}

.Aparencia {
	display: block;
	flex: 1 1 0%;
	align-self: center;
}

.Aparencia-sub {
	display: flex;
	justify-content: center;
	gap: 10%;
}

.text-xl.font-semibold.section-title {
	display: flex;
	justify-content: flex-start;
}

.seed.mt-4 {
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
}

#iiu5q1 {
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 3%;
	padding-left: 0px;
}

#idrnqj {
	padding-bottom: 3%;
}

#i5ftla {
	padding-bottom: 3%;
}

#iuoo3a {
	color: rgba(255, 255, 255, 1);
}

#iopask {
	padding-bottom: 3%;
	padding-right: 3%;
}

#ih5m4x {
	width: 20%;
	min-width: 20%;
	max-width: 20%;
}

#irjgrg {
	padding-right: 3%;
}

#iojva2 {
	position: static;
	display: block;
}

#iloeca {
	padding-right: 3%;
}

#ixv1zc {
	width: 20%;
	min-width: 20%;
	max-width: 20%;
}

#i1njpp {
	justify-content: center;
	display: block;
}

#i8sqak {
	display: block;
}

#ivuf97 {
	display: block;
}

#ij4mgg {
	display: flex;
	width: 60%;
	min-width: 60%;
	max-width: 60%;
}

#ipnnd7 {
	margin-top: 16px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
}

/* Estilo da Barra de Ferramentas */
.navigation-bar__inner {
    display: flex;
    gap: 8px;
    padding: 8px;
    background-color: #2c2c2c;
    border-radius: 4px;
    margin-bottom: 8px;
}

.navigation-bar__button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ecf0f1;
    transition: background-color 0.3s;
}

.navigation-bar__button:hover {
    background-color: #4A6E4D;
    border-radius: 4px;
}

.navigation-bar__button svg {
    width: 20px;
    height: 20px;
    fill: currentColor;
}
    </style>
</head>
<body id="imaotr">
  <meta charset="UTF-8" />

<!-- Seção de Informações Básicas -->
<div class="informacoes-basicas mt-4">
<div id="i9oqhp">
  <div class="Aparencia" id="i6il4d">
    <h1 class="text-3xl font-bold" id="id4hap">
      <?= htmlspecialchars($ficha['nome']) ?>
    </h1>
    <img src="<?= htmlspecialchars($ficha['aparencia_personalidade']) ?>" alt="Aparência do Personagem" id="ivdif2" class="mx-auto rounded-full mt-4"/>
  </div>
  <div class="Aparencia" id="ig864d">
    <h3 class="text-xl font-semibold section-title" id="ieo7nk">Aparência</h3>
    <div class="Aparencia-sub" id="i35ntw">
      <form method="POST">
        <p id="i3tlvy"><strong>Idade:</strong>
          <input type="text" name="idade" value="<?= htmlspecialchars($ficha['idade']) ?>">
        </p>
        <p><strong>Altura:</strong>
          <input type="text" name="altura" value="<?= htmlspecialchars($ficha['altura']) ?>">
        </p>
        <p><strong>Peso:</strong>
          <input type="text" name="peso" value="<?= htmlspecialchars($ficha['peso']) ?>">
        </p>
        <p><strong>Cabelos:</strong>
          <input type="text" name="cabelos" value="<?= htmlspecialchars($ficha['cabelos']) ?>">
        </p>
        <p><strong id="ih3j1g">Olhos:</strong>
          <input type="text" name="olhos" value="<?= htmlspecialchars($ficha['olhos']) ?>">
        </p>
        <p>
          <h3 class="text-xl font-semibold section-title" id="i31tyo">Seed</h3>
        </p>
        <div class="seed mt-4" id="ihfs5k">
          <p>
            <input type="text" name="seed" value="<?= htmlspecialchars($ficha['seed']) ?>">
          </p>
        </div>
        <button type="submit" name="update_aparencia" class="btn btn-primary">Atualizar</button>
      </form>
    </div>
  </div>
  <div class="atributos" id="ixv1zc">
    <h3 class="text-xl font-semibold section-title">Atributos</h3>
    <ul class="list-disc list-inside">
      <li><strong>Força:</strong>
        <input type="text" name="for" value="<?= htmlspecialchars($ficha['for']) ?>">
      </li>
      <li><strong>Destreza:</strong>
        <input type="text" name="des" value="<?= htmlspecialchars($ficha['des']) ?>">
      </li>
      <li><strong>Constituição:</strong>
        <input type="text" name="con" value="<?= htmlspecialchars($ficha['con']) ?>">
      </li>
      <li><strong>Inteligência:</strong>
        <input type="text" name="int" value="<?= htmlspecialchars($ficha['int']) ?>">
      </li>
      <li><strong>Sabedoria:</strong>
        <input type="text" name="sab" value="<?= htmlspecialchars($ficha['sab']) ?>">
      </li>
      <li><strong>Carisma:</strong>
        <input type="text" name="car" value="<?= htmlspecialchars($ficha['car']) ?>">
      </li>
      <li><strong>Poder:</strong>
        <input type="text" name="poder" value="<?= htmlspecialchars($ficha['poder']) ?>">
      </li>
    </ul>
    <button type="submit" name="update_atributos" class="btn btn-primary">Atualizar</button>
  </div>
  <div class="status-container" id="ih5m4x">
    <h3 class="text-xl font-semibold section-title">Status</h3>
    <form method="POST" id="iuoo3a">
      <ul class="list-disc list-inside" id="iojva2">
        <li id="iiu5q1"><strong id="iloeca">PVs Atuais:</strong><input type="number" name="pvs_atual" id="pvs_atual" value="<?= htmlspecialchars($ficha['pvs_atual']) ?>" required/>
                                (Máx:
          <?= htmlspecialchars($ficha['for'] + ($ficha['con'] * 2) + $ficha[' pvs']) ?>)
        </li>
        <li id="idrnqj"><strong id="iopask">PSs Atuais:</strong><input type="number" name="pss_atual" id="pss_atual" value="<?= htmlspecialchars($ficha['pss_atual']) ?>" required/>
                                (Máx:
          <?= htmlspecialchars($ficha['sab'] + ($ficha['con'] * 2) + $ficha['pss']) ?>)
        </li>
        <li id="i5ftla"><strong id="irjgrg">PES Atuais:</strong><input type="number" name="pes_atual" id="pes_atual" value="<?= htmlspecialchars($ficha['pes_atual']) ?>" required/>
                                (Máx:
          <?= htmlspecialchars($ficha['int'] + floor($ficha['poder'] * M_PI) + $ficha['pes']) ?>)
        </li>
      </ul>
      <button type="submit" name="update_status" class="w-full bg-green-800 text-white p-2 rounded mt-2">Atualizar Status</button>
    </form>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4" id="ixuhqr">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4" id="ij4mgg">
    <div class="patente">
      <h3 class="text-xl font-semibold section-title" id="ijvc9w">Patente</h3>
      <form method="POST">
        <p>
          <input type="text" name="patente" value="<?= htmlspecialchars($ficha['patente']) ?>">
        </p>
        <button type="submit" name="update_patente" class="btn btn-primary">Atualizar Patente</button>
      </form>
    </div>
    <div class="pontos-narrativos" id="ioe7ef">
      <h3 class="text-xl font-semibold section-title" id="i1njpp">Pontos Narrativos</h3>
      <form method="POST">
        <p>
          <input type="number" name="pontos_narrativos" value="<?= htmlspecialchars($ficha['pontos_narrativos']) ?>">
        </p>
        <button type="submit" name="update_pontos_narrativos" class="btn btn-primary">Atualizar</button>
      </form>
    </div>
    <div class="pontos-acao">
      <h3 class="text-xl font-semibold section-title" id="i8sqak">Pontos de Ação</h3>
      <form method="POST">
        <p>
          <input type="number" name="pontos_acao" value="<?= htmlspecialchars($ficha['pontos_acao']) ?>">
        </p>
        <button type="submit" name="update_pontos_acao" class="btn btn-primary">Atualizar</button>
      </form>
    </div>
    <div class="pontos-xp">
      <h3 class="text-xl font-semibold section-title" id="ivuf97">Pontos de XP</h3>
      <form method="POST">
        <p>
          <input type="number" name="pontos_xp" value="<?= htmlspecialchars($ficha['pontos_xp']) ?>">
        </p>
        <button type="submit" name="update_pontos_xp" class="btn btn-primary">Atualizar</button>
      </form>
    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4" id="i5t87i">
  <!-- Seção de Sonhos -->
<div class="sonhos mt-4">
    <h3 class="text-xl font-semibold section-title">Sonhos</h3>
    <form method="POST">
        <div class="form-group">
            <label for="descricao_sonho">Descrição do Sonho:</label>
            <textarea name="descricao_sonho" id="descricao_sonho" required></textarea>
        </div>
        <button type="submit" name="add_sonho" class="btn btn-primary">Adicionar Sonho</button>
    </form>
    <ul>
        <?php foreach ($sonhos as $sonho): ?>
            <li>
                <?= htmlspecialchars($sonho['descricao']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_sonho" value="<?= $sonho['id'] ?>">
                    <input type="hidden" name="descricao_sonho" value="<?= htmlspecialchars($sonho['descricao']) ?>">
                    <button type="submit" name="update_sonho" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_sonho" value="<?= $sonho['id'] ?>">
                    <button type="submit" name="delete_sonho" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

        <!-- Seção de Medos -->
        <div class="medos mt-4">
            <h3 class="text-xl font-semibold section-title">Medos</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="descricao_medo">Descrição do Medo:</label>
                    <textarea name="descricao_medo" id="descricao_medo" required></textarea>
                </div>
                <button type="submit" name="add_medo" class="btn btn-primary">Adicionar Medo</button>
            </form>
            <ul>
                <?php foreach ($medos as $medo): ?>
                    <li>
                        <?= htmlspecialchars($medo['descricao']) ?> - 
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_medo" value="<?= $medo['id'] ?>">
                            <input type="hidden" name="descricao_medo" value="<?= htmlspecialchars($medo['descricao']) ?>">
                            <button type="submit" name="update_medo" class="btn btn-secondary">Atualizar</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_medo" value="<?= $medo['id'] ?>">
                            <button type="submit" name="delete_medo" class="btn btn-danger">Excluir</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
  </div>
</div>

<div class="historia mt-4">
  <h3 class=" text-xl font-semibold section-title" id="inka42">História</h3>
  <form method="POST">
    <textarea name="historia" rows="4" class="w-full"><?= nl2br(htmlspecialchars($ficha['historia'])) ?></textarea>
    <button type="submit" name="update_historia" class="btn btn-primary">Atualizar História</button>
  </form>
</div>
<!-- Seção de Perícias -->
<div class="pericias mt-4">
    <h3 class="text-xl font-semibold section-title">Perícias</h3>
    <form method="POST">
        <div class="form-group">
            <label for="nome_pericia">Nome da Perícia:</label>
            <input type="text" name="nome_pericia" id="nome_pericia" required>
        </div>
        <div class="form-group">
            <label for="pts_pericia">Pontos da Perícia:</label>
            <input type="number" name="pts_pericia" id="pts_pericia" required>
        </div>
        <div class="form-group">
            <label for="valor_pericia">Valor da Perícia:</label>
            <input type="number" name="valor_pericia" id="valor_pericia" required>
        </div>
        <button type="submit" name="add_pericia" class="btn btn-primary">Adicionar Perícia</button>
    </form>
    <ul>
        <?php foreach ($pericias as $pericia): ?>
            <li>
                <?= htmlspecialchars($pericia['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_pericia" value="<?= $pericia['id'] ?>">
                    <input type="hidden" name="nome_pericia" value="<?= htmlspecialchars($pericia['nome']) ?>">
                    <input type="hidden" name="pts_pericia" value="<?= $pericia['pts'] ?>">
                    <input type="hidden" name="valor_pericia" value="<?= $pericia['valor'] ?>">
                    <button type="submit" name="update_pericia" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_pericia" value="<?= $pericia['id'] ?>">
                    <button type="submit" name="delete_pericia" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Equipamentos -->
<div class="equipamentos mt-4">
    <h3 class="text-xl font-semibold section-title">Equipamentos</h3>
    <form method="POST">
        <div class="form-group">
            <label for="nome_equipamento">Nome do Equipamento:</label>
            <input type="text" name="nome_equipamento" id="nome_equipamento" required>
        </div>
        <div class="form-group">
            <label for="quantidade_equipamento">Quantidade:</label>
            <input type="number" name="quantidade_equipamento" id="quantidade_equipamento" required>
        </div>
        <div class="form-group">
            <label for="peso_por_unidade">Peso por Unidade:</label>
            <input type="number" name="peso_por_unidade" id="peso_por_unidade" required>
        </div>
        <button type="submit" name="add_equipamento" class="btn btn-primary">Adicionar Equipamento</button>
    </form>
    <ul>
        <?php foreach ($equipamentos as $equipamento): ?>
            <li>
                <?= htmlspecialchars($equipamento['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_equipamento" value="<?= $equipamento['id'] ?>">
                    <input type="hidden" name="nome_equipamento" value="<?= htmlspecialchars($equipamento['nome']) ?>">
                    <input type="hidden" name="quantidade_equipamento" value="<?= $equipamento['quantidade'] ?>">
                    <input type="hidden" name="peso_por_unidade" value="<?= $equipamento['peso_por_unidade'] ?>">
                    <button type="submit" name="update_equipamento" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_equipamento" value="<?= $equipamento['id'] ?>">
                    <button type="submit" name="delete_equipamento" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Combate -->
<div class="combate mt-4">
    <h3 class="text-xl font-semibold section-title">Combate</h3>
    <form method="POST">
        <div class="form-group">
            <label for="pericia ">Perícia:</label>
            <input type="text" name="pericia" id="pericia" required>
        </div>
        <div class="form-group">
            <label for="arma_ou_poder">Arma ou Poder:</label>
            <input type="text" name="arma_ou_poder" id="arma_ou_poder" required>
        </div>
        <div class="form-group">
            <label for="ataque">Ataque:</label>
            <input type="number" name="ataque" id="ataque" required>
        </div>
        <div class="form-group">
            <label for="pts">Pontos:</label>
            <input type="number" name="pts" id="pts" required>
        </div>
        <div class="form-group">
            <label for="cargas">Cargas:</label>
            <input type="number" name="cargas" id="cargas" required>
        </div>
        <div class="form-group">
            <label for="ataque_por_turno">Ataque por Turno:</label>
            <input type="number" name="ataque_por_turno" id="ataque_por_turno" required>
        </div>
        <div class="form-group">
            <label for="distancia">Distância:</label>
            <input type="text" name="distancia" id="distancia" required>
        </div>
        <div class="form-group">
            <label for="detalhes">Detalhes:</label>
            <textarea name="detalhes" id="detalhes" required></textarea>
        </div>
        <button type="submit" name="add_combate" class="btn btn-primary">Adicionar Combate</button>
    </form>
    <ul>
        <?php foreach ($combate as $item): ?>
            <li>
                <?= htmlspecialchars($item['pericia']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_combate" value="<?= $item['id'] ?>">
                    <input type="hidden" name="pericia" value="<?= htmlspecialchars($item['pericia']) ?>">
                    <input type="hidden" name="arma_ou_poder" value="<?= htmlspecialchars($item['arma_ou_poder']) ?>">
                    <input type="hidden" name="ataque" value="<?= $item['ataque'] ?>">
                    <input type="hidden" name="pts" value="<?= $item['pts'] ?>">
                    <input type="hidden" name="cargas" value="<?= $item['cargas'] ?>">
                    <input type="hidden" name="ataque_por_turno" value="<?= $item['ataque_por_turno'] ?>">
                    <input type="hidden" name="distancia" value="<?= htmlspecialchars($item['distancia']) ?>">
                    <input type="hidden" name="detalhes" value="<?= htmlspecialchars($item['detalhes']) ?>">
                    <button type="submit" name="update_combate" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_combate" value="<?= $item['id'] ?>">
                    <button type="submit" name="delete_combate" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Vantagens -->
<div class="vantagens mt-4">
    <h3 class="text-xl font-semibold section-title">Vantagens</h3>
    <form method="POST">
        <div class="form-group">
            <label for="nome_vantagem">Nome da Vantagem:</label>
            <input type="text" name="nome_vantagem" id="nome_vantagem" required>
        </div>
        <div class="form-group">
            <label for="descricao_vantagem">Descrição:</label>
            <textarea name="descricao_vantagem" id="descricao_vantagem" required></textarea>
        </div>
        <button type="submit" name="add_vantagem" class="btn btn-primary">Adicionar Vantagem</button>
    </form>
    <ul>
        <?php foreach ($vantagens as $vantagem): ?>
            <li>
                <?= htmlspecialchars($vantagem['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_vantagem" value="<?= $vantagem['id'] ?>">
                    <input type="hidden" name="nome_vantagem" value="<?= htmlspecialchars($vantagem['nome']) ?>">
                    <input type="hidden" name="descricao_vantagem" value="<?= htmlspecialchars($vantagem['descricao']) ?>">
                    <button type="submit" name="update_vantagem" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_vantagem" value="<?= $vantagem['id'] ?>">
                    <button type="submit" name="delete_vantagem" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Desvantagens -->
<div class="desvantagens mt-4">
    <h3 class="text-xl font-semibold section-title">Desvantagens</h3>
    <form method="POST">
        <div class="form-group">
            <label for="nome_desvantagem">Nome da Desvantagem:</label>
            <input type="text" name="nome_desvantagem" id="nome_desvantagem" required>
        </div>
        <div class="form-group">
            <label for="descricao_desvantagem">Descrição:</label>
            <textarea name="descricao_desvantagem" id="descricao_desvantagem" required></textarea>
        </div>
        <button type="submit" name="add_desvantagem" class="btn btn-primary">Adicionar Desvantagem</button>
    </form>
    <ul>
        <?php foreach ($desvantagens as $desvantagem): ?>
            <li>
                <?= htmlspecialchars($desvantagem['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_desvantagem" value="<?= $desvantagem['id'] ?>">
                    <input type="hidden" name="nome_desvantagem" value="<?= htmlspecialchars($desvantagem['nome']) ?>">
                    <input type="hidden" name="descricao_desvantagem" value="<?= htmlspecialchars($desvantagem['descricao']) ?>">
                    <button type="submit" name="update_desvantagem" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_desvantagem" value="<?= $desvantagem['id'] ?>">
                    <button type="submit" name="delete_desvantagem" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Aliados -->
<div class="aliados mt-4">
    <h3 class="text-xl font-semibold section-title">Aliados</h3>
    <form method="POST">
        <div class="form-group">
            <label for="nome_aliado">Nome do Aliado:</label>
            <input type="text" name="nome_aliado" id="nome_aliado" required>
        </div>
        <div class="form-group">
            <label for="relacao_aliado">Relação:</label>
            <input type="text" name="relacao_aliado" id="relacao_aliado" required>
        </div>
        <button type="submit" name="add_aliado" class="btn btn-primary">Adicionar Aliado</button>
    </form>
    <ul>
        <?php foreach ($aliados as $aliado): ?>
            <li>
                <?= htmlspecialchars($aliado['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_aliado" value="<?= $aliado['id'] ?>">
                    <input type="hidden" name="nome_aliado" value="<?= htmlspecialchars($aliado['nome']) ?>">
                    <input type="hidden" name="relacao_aliado" value="<?= htmlspecialchars($aliado['relacao']) ?>">
                    <button type="submit" name="update_aliado" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_aliado" value="<?= $aliado['id'] ?>">
                    <button type="submit" name="delete_aliado" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Inimigos -->
<div class="inimigos mt-4">
    <h3 class="text-xl font-semibold section-title">Inimigos</h3>
    <form method="POST">
 <div class="form-group">
            <label for="nome_inimigo">Nome do Inimigo:</label>
            <input type="text" name="nome_inimigo" id="nome_inimigo" required>
        </div>
        <div class="form-group">
            <label for="relacao_inimigo">Relação:</label>
            <input type="text" name="relacao_inimigo" id="relacao_inimigo" required>
        </div>
        <button type="submit" name="add_inimigo" class="btn btn-primary">Adicionar Inimigo</button>
    </form>
    <ul>
        <?php foreach ($inimigos as $inimigo): ?>
            <li>
                <?= htmlspecialchars($inimigo['nome']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_inimigo" value="<?= $inimigo['id'] ?>">
                    <input type="hidden" name="nome_inimigo" value="<?= htmlspecialchars($inimigo['nome']) ?>">
                    <input type="hidden" name="relacao_inimigo" value="<?= htmlspecialchars($inimigo['relacao']) ?>">
                    <button type="submit" name="update_inimigo" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_inimigo" value="<?= $inimigo['id'] ?>">
                    <button type="submit" name="delete_inimigo" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Seção de Anotações -->
<div class="anotacoes mt-4">
    <h3 class="text-xl font-semibold section-title">Anotações</h3>
    <form method="POST">
        <div class="form-group">
            <label for="conteudo_anotacao">Conteúdo da Anotação:</label>
            <textarea name="conteudo_anotacao" id="conteudo_anotacao" required></textarea>
        </div>
        <button type="submit" name="add_anotacao" class="btn btn-primary">Adicionar Anotação</button>
    </form>
    <ul>
        <?php foreach ($anotacoes as $anotacao): ?>
            <li>
                <?= htmlspecialchars($anotacao['conteudo']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_anotacao" value="<?= $anotacao['id'] ?>">
                    <input type="hidden" name="conteudo_anotacao" value="<?= htmlspecialchars($anotacao['conteudo']) ?>">
                    <button type="submit" name="update_anotacao" class="btn btn-secondary">Atualizar</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id_anotacao" value="<?= $anotacao['id'] ?>">
                    <button type="submit" name="delete_anotacao" class="btn btn-danger">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</div>
    <div class="actions mt-6 text-center"><a href="edit_ficha.php?id=<?= $ficha['id'] ?>"
        class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">Editar</a><a href="dashboard.php"
        class="bg-yellow-500 text-white px-4 py-2 rounded">Voltar</a></div>
  </div>
  <script>
    function formatText(command) {
        const textarea = document.getElementById('anotacoes');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        
        let formattedText = '';

        switch (command) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `*${selectedText}*`;
                break;
            case 'unordered-list':
                formattedText = `- ${selectedText}`;
                break;
            case 'ordered-list':
                formattedText = `1. ${selectedText}`;
                break;
            case 'blockquote':
                formattedText = `> ${selectedText}`;
                break;
            default:
                return;
        }

        textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
    }

  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>