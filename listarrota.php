<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "motorista") {
    echo json_encode([]);
    exit;
}

$idMotorista = $_SESSION["id"];

include("conexao.php");

$sql = "SELECT idRota, Nome, HorarioPartida, HorarioSaida, Partida, Destino
        FROM Rotas
        WHERE idMotorista = ?
        ORDER BY idRota DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idMotorista);
$stmt->execute();
$resultado = $stmt->get_result();

$rotas = [];

while ($rota = $resultado->fetch_assoc()) {
    $rotas[] = $rota;
}

echo json_encode($rotas);

$stmt->close();
$conexao->close();

?>