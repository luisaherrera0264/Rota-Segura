<?php

session_start();

include("conexao.php");

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "responsavel") {
    echo json_encode([]);
    exit;
}

$idResponsavel = $_SESSION["id"];

$sql = "SELECT idDependente, Nome, DataNascimento, Escola,
               EnderecoEmbarque, EnderecoDesembarque, Turno
        FROM Dependentes
        WHERE idResponsavel = $idResponsavel
        ORDER BY Nome";

$resultado = $conexao->query($sql);

$dependentes = [];

while ($dependente = $resultado->fetch_assoc()) {

    $dependentes[] = $dependente;

}

echo json_encode($dependentes);

$conexao->close();

?>