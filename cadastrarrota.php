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
$nome           = isset($_POST['nome'])           ? trim($_POST['nome'])           : '';
$horarioPartida = isset($_POST['horarioPartida']) ? trim($_POST['horarioPartida']) : '';
$horarioSaida   = isset($_POST['horarioSaida'])   ? trim($_POST['horarioSaida'])   : '';
$partida        = isset($_POST['partida'])        ? trim($_POST['partida'])        : '';
$destino        = isset($_POST['destino'])        ? trim($_POST['destino'])        : '';

// ============================================
// 3. VALIDAÇÕES
// ============================================
if ($nome === '' || $horarioPartida === '' || $horarioSaida === '' || $partida === '' || $destino === '') {
    echo "dados_incompletos";
    exit;
}

// Formato do horário: HH:MM ou HH:MM:SS
if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $horarioPartida) ||
    !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $horarioSaida)) {
    echo "horario_invalido";
    exit;
}

// Se vier HH:MM, completa com :00
if (strlen($horarioPartida) === 5) { $horarioPartida .= ':00'; }
if (strlen($horarioSaida)   === 5) { $horarioSaida   .= ':00'; }

// Limita tamanhos (novos limites após ALTER TABLE)
if (mb_strlen($nome)    > 80)  { $nome    = mb_substr($nome,    0, 80); }
if (mb_strlen($partida) > 150) { $partida = mb_substr($partida, 0, 150); }
if (mb_strlen($destino) > 255) { $destino = mb_substr($destino, 0, 255); }

// ============================================
// 4. CONEXÃO
// ============================================
include("conexao.php");

// ============================================
// 5. INSERT
// ============================================
$sqlInsert = "INSERT INTO Rotas
              (idMotorista, Nome, HorarioPartida, HorarioSaida, Partida, Destino)
              VALUES (?, ?, ?, ?, ?, ?)";

$stmtInsert = $conexao->prepare($sqlInsert);

$stmtInsert->bind_param(
    "isssss",
    $idMotorista,
    $nome,
    $horarioPartida,
    $horarioSaida,
    $partida,
    $destino
);

if ($stmtInsert->execute()) {
    echo "sucesso";
} else {
    echo "erro_banco";
}

$stmtInsert->close();
$conexao->close();

?>