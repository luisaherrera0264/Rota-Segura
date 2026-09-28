<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "monitor") {
    echo json_encode([]);
    exit;
}

include("conexao.php");

$sql = "SELECT r.idRota, r.Nome, r.HorarioPartida, r.HorarioSaida,
               r.Partida, r.Destino,
               m.Nome AS NomeMotorista
        FROM Rotas r
        LEFT JOIN Motoristas m ON m.idMotorista = r.idMotorista
        ORDER BY r.Nome";

$resultado = $conexao->query($sql);

$rotas = [];

while ($rota = $resultado->fetch_assoc()) {
    $rotas[] = $rota;
}

echo json_encode($rotas);

$conexao->close();

?>