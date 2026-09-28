<?php

session_start();

include("conexao.php");


if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != "responsavel") {

    header("Location: login.html");

    exit;

}


$idResponsavel = $_SESSION["id"];

$nome = $_POST["nome"];
$dataNascimento = $_POST["dataNascimento"];
$escola = $_POST["escola"];
$enderecoEmbarque = $_POST["enderecoEmbarque"];
$enderecoDesembarque = $_POST["enderecoDesembarque"];
$turno = $_POST["turno"];


$sql = "INSERT INTO Dependentes
        (idResponsavel, Nome, DataNascimento, Escola, EnderecoEmbarque, EnderecoDesembarque, Turno)
        VALUES
        ('$idResponsavel',
         '$nome',
         '$dataNascimento',
         '$escola',
         '$enderecoEmbarque',
         '$enderecoDesembarque',
         '$turno')";


$resultado = $conexao->query($sql);


if ($resultado) {

    header("Location: responsavel.html?cadastro=sucesso");

    exit;

}


header("Location: responsavel.html?cadastro=erro");

exit;


$conexao->close();

?>