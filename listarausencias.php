<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "monitor") {
    echo json_encode([]);
    exit;
}

include("conexao.php");

/*
 * JOIN entre Ausencias e Dependentes
 * pra pegar o nome do dependente junto com a ausência.
 *
 * Ordena da mais recente pra mais antiga.
 */

$sql = "SELECT a.idAusencia,
               a.DataAusencia,
               a.Tipo,
               a.Descricao,
               d.Nome AS NomeDependente,
               d.Escola,
               d.Turno
        FROM Ausencias a
        INNER JOIN Dependentes d ON d.idDependente = a.idDependente
        ORDER BY a.DataAusencia DESC, a.idAusencia DESC";

$resultado = $conexao->query($sql);

$ausencias = [];

while ($linha = $resultado->fetch_assoc()) {
    $ausencias[] = $linha;
}

echo json_encode($ausencias);

$conexao->close();

?>