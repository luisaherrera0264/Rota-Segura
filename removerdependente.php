<?php

session_start();

include("conexao.php");

if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "responsavel") {
    echo "erro";
    exit;
}

$idResponsavel = $_SESSION["id"];
$idDependente = $_POST["idDependente"];

$sql = "DELETE FROM Dependentes
        WHERE idDependente = $idDependente
        AND idResponsavel = $idResponsavel";

if ($conexao->query($sql)) {

    echo "sucesso";

} else {

    echo "erro";

}

$conexao->close();

?>