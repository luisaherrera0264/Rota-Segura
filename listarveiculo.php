<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "motorista") {
    echo json_encode(null);
    exit;
}

$idMotorista = $_SESSION["id"];

include("conexao.php");

$sql = "SELECT idVeiculo, Marca, Modelo, Ano, Placa, Capacidade, Cor
        FROM Veiculos
        WHERE idMotorista = ?
        ORDER BY idVeiculo DESC
        LIMIT 1";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idMotorista);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(null);
} else {
    echo json_encode($resultado->fetch_assoc());
}

$stmt->close();
$conexao->close();

?>