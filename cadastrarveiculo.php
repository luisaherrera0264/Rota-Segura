<?php

session_start();

header('Content-Type: text/plain; charset=utf-8');

// ============================================
// 1. VERIFICA LOGIN
// ============================================
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "motorista") {
    echo "nao_logado";
    exit;
}

$idMotorista = $_SESSION["id"];

// ============================================
// 2. RECEBE OS DADOS
// ============================================
$marca      = isset($_POST['marca'])      ? trim($_POST['marca'])      : '';
$modelo     = isset($_POST['modelo'])     ? trim($_POST['modelo'])     : '';
$ano        = isset($_POST['ano'])        ? (int) $_POST['ano']        : 0;
$placa      = isset($_POST['placa'])      ? trim($_POST['placa'])      : '';
$capacidade = isset($_POST['capacidade']) ? (int) $_POST['capacidade'] : 0;
$cor        = isset($_POST['cor'])        ? trim($_POST['cor'])        : '';

// ============================================
// 3. LIMPA A PLACA (remove hífen, espaço, deixa maiúsculo)
// ============================================
$placa = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $placa));

// ============================================
// 4. VALIDAÇÕES
// ============================================
if ($marca === '' || $modelo === '' || $ano <= 0 || $placa === '' || $capacidade <= 0) {
    echo "dados_incompletos";
    exit;
}

if (strlen($placa) !== 7) {
    echo "placa_invalida";
    exit;
}

if ($ano < 1900 || $ano > 2100) {
    echo "ano_invalido";
    exit;
}

// Limita tamanhos (novos limites após ALTER TABLE)
if (mb_strlen($marca) > 50)  { $marca  = mb_substr($marca, 0, 50); }
if (mb_strlen($modelo) > 50) { $modelo = mb_substr($modelo, 0, 50); }
if (mb_strlen($cor) > 30)    { $cor    = mb_substr($cor, 0, 30); }

$corFinal = ($cor === '') ? null : $cor;

// ============================================
// 5. CONEXÃO
// ============================================
include("conexao.php");

// ============================================
// 6. VERIFICA SE A PLACA JÁ EXISTE
// ============================================
$sqlCheck = "SELECT idVeiculo FROM Veiculos WHERE Placa = ? LIMIT 1";
$stmtCheck = $conexao->prepare($sqlCheck);
$stmtCheck->bind_param("s", $placa);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo "placa_duplicada";
    $stmtCheck->close();
    $conexao->close();
    exit;
}

$stmtCheck->close();

// ============================================
// 7. INSERT
// ============================================
$sqlInsert = "INSERT INTO Veiculos
              (idMotorista, Marca, Modelo, Ano, Placa, Capacidade, Cor)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmtInsert = $conexao->prepare($sqlInsert);

$stmtInsert->bind_param(
    "ississi",
    $idMotorista,
    $marca,
    $modelo,
    $ano,
    $placa,
    $capacidade,
    $corFinal
);

if ($stmtInsert->execute()) {
    echo "sucesso";
} else {
    echo "erro_banco";
}

$stmtInsert->close();
$conexao->close();

?>