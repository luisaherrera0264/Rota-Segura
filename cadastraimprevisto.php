<?php

session_start();

header('Content-Type: text/plain; charset=utf-8');

// ============================================
// 1. VERIFICA LOGIN
// ============================================
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "monitor") {
    echo "nao_logado";
    exit;
}

$idMonitor = $_SESSION["id"];

// ============================================
// 2. RECEBE OS DADOS (sem data — banco preenche)
// ============================================
$idRota    = isset($_POST['idRota'])    ? (int) $_POST['idRota'] : 0;
$tipo      = isset($_POST['tipo'])      ? trim($_POST['tipo'])      : '';
$descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';

// ============================================
// 3. VALIDAÇÕES
// ============================================
if ($idRota <= 0 || $tipo === '' || $descricao === '') {
    echo "dados_incompletos";
    exit;
}

if (mb_strlen($tipo) > 30) {
    $tipo = mb_substr($tipo, 0, 30);
}

if (mb_strlen($descricao) > 255) {
    $descricao = mb_substr($descricao, 0, 255);
}

// ============================================
// 4. CONEXÃO
// ============================================
include("conexao.php");

// ============================================
// 5. VERIFICA SE A ROTA EXISTE
// ============================================
$sqlCheck = "SELECT idRota FROM Rotas WHERE idRota = ? LIMIT 1";
$stmtCheck = $conexao->prepare($sqlCheck);
$stmtCheck->bind_param("i", $idRota);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    echo "rota_invalida";
    $stmtCheck->close();
    $conexao->close();
    exit;
}

$stmtCheck->close();

// ============================================
// 6. INSERT — DataImprevisto preenchida pelo banco
// ============================================
$sqlInsert = "INSERT INTO Imprevistos
              (idMonitor, Rota, Tipo, Descricao)
              VALUES (?, ?, ?, ?)";

$stmtInsert = $conexao->prepare($sqlInsert);

$stmtInsert->bind_param(
    "iiss",
    $idMonitor,
    $idRota,
    $tipo,
    $descricao
);

if ($stmtInsert->execute()) {
    echo "sucesso";
} else {
    echo "erro_banco";
}

$stmtInsert->close();
$conexao->close();

?>