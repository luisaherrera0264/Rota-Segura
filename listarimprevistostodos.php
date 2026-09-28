<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "monitor") {
    echo json_encode([]);
    exit;
}

include("conexao.php");

/*
 * JOIN com Rotas pra pegar o nome da rota.
 * JOIN com Monitores pra pegar quem registrou.
 *
 * Filtra somente os imprevistos de HOJE.
 * Ordena do mais recente pro mais antigo.
 */

$sql = "SELECT i.idImprevisto,
               i.DataImprevisto,
               i.Tipo,
               i.Descricao,
               r.Nome AS NomeRota,
               r.Partida,
               r.Destino,
               m.Nome AS NomeMonitor
        FROM Imprevistos i
        INNER JOIN Rotas r     ON r.idRota      = i.Rota
        INNER JOIN Monitores m ON m.idMonitor   = i.idMonitor
        WHERE DATE(i.DataImprevisto) = CURDATE()
        ORDER BY i.DataImprevisto DESC";

$resultado = $conexao->query($sql);

$imprevistos = [];

while ($linha = $resultado->fetch_assoc()) {
    $imprevistos[] = $linha;
}

echo json_encode($imprevistos);

$conexao->close();

?>