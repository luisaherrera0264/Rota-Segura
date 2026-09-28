<?php

session_start();

header('Content-Type: text/plain; charset=utf-8');

// ============================================
// 1. VERIFICA LOGIN (mesma regra dos outros)
// ============================================
if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "responsavel") {
    echo "nao_logado";
    exit;
}

$idResponsavel = $_SESSION["id"];

// ============================================
// 2. RECEBE OS DADOS
// ============================================
$idDependente = isset($_POST['idDependente']) ? (int) $_POST['idDependente'] : 0;
$dataAusencia = isset($_POST['dataAusencia']) ? trim($_POST['dataAusencia']) : '';
$tipo         = isset($_POST['tipo'])         ? trim($_POST['tipo'])         : '';
$descricao    = isset($_POST['descricao'])    ? trim($_POST['descricao'])    : '';

// ============================================
// 3. VALIDAÇÕES
// ============================================
if ($idDependente <= 0 || $dataAusencia === '' || $tipo === '') {
    echo "dados_incompletos";
    exit;
}

// Limita descrição ao tamanho da coluna (VARCHAR 50)
if (mb_strlen($descricao) > 50) {
    $descricao = mb_substr($descricao, 0, 50);
}

// ============================================
// 4. CONEXÃO
// ============================================
include("conexao.php");

// ============================================
// 5. SEGURANÇA: dependente pertence ao responsável?
// ============================================
$sqlCheck = "SELECT idDependente
             FROM Dependentes
             WHERE idDependente = ? AND idResponsavel = ?
             LIMIT 1";

$stmtCheck = $conexao->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $idDependente, $idResponsavel);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    echo "dependente_invalido";
    $stmtCheck->close();
    $conexao->close();
    exit;
}

$stmtCheck->close();

// ============================================
// 6. INSERT
// ============================================
$sqlInsert = "INSERT INTO Ausencias (idDependente, DataAusencia, Tipo, Descricao)
              VALUES (?, ?, ?, ?)";

$stmtInsert = $conexao->prepare($sqlInsert);

// Se descrição vazia, grava NULL
$descricaoFinal = ($descricao === '') ? null : $descricao;

$stmtInsert->bind_param("isss", $idDependente, $dataAusencia, $tipo, $descricaoFinal);

if ($stmtInsert->execute()) {
    echo "sucesso";
} else {
    echo "erro_banco";
}

$stmtInsert->close();
$conexao->close();

?>