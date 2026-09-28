<?php

include("conexao.php");

$tipo = $_POST["tipo"];
$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];


/*
 * Protege os dados antes de colocar
 * dentro do comando SQL.
 */

$nome = mysqli_real_escape_string($conexao, $nome);
$email = mysqli_real_escape_string($conexao, $email);


/*
 * Criptografa a senha antes de salvar
 * no banco de dados.
 */

$senha = password_hash($senha, PASSWORD_DEFAULT);


/*
 * CADASTRO DE RESPONSÁVEL
 */

if ($tipo == "responsavel") {

    $cpf = $_POST["cpf"];
    $dataNascimento = $_POST["dataNascimento"];

    $cpf = mysqli_real_escape_string($conexao, $cpf);
    $dataNascimento = mysqli_real_escape_string($conexao, $dataNascimento);


    $sql = "INSERT INTO Responsaveis
            (Nome, CPF, DataNascimento, Email, Senha)
            VALUES
            ('$nome', '$cpf', '$dataNascimento', '$email', '$senha')";


    if (mysqli_query($conexao, $sql)) {

        header("Location: criarconta.html?cadastro=sucesso&tipo=responsavel");
        exit;

    } else {

        header("Location: criarconta.html?cadastro=erro");
        exit;

    }

}


/*
 * CADASTRO DE MOTORISTA
 */

else if ($tipo == "motorista") {

    $cnh = $_POST["cnh"];
    $dataNascimento = $_POST["dataNascimento"];

    $cnh = mysqli_real_escape_string($conexao, $cnh);
    $dataNascimento = mysqli_real_escape_string($conexao, $dataNascimento);


    $sql = "INSERT INTO Motoristas
            (Nome, CNH, DataNascimento, Email, Senha)
            VALUES
            ('$nome', '$cnh', '$dataNascimento', '$email', '$senha')";


    if (mysqli_query($conexao, $sql)) {

        header("Location: criarconta.html?cadastro=sucesso&tipo=motorista");
        exit;

    } else {

        header("Location: criarconta.html?cadastro=erro");
        exit;

    }

}


/*
 * CADASTRO DE MONITOR
 */

else if ($tipo == "monitor") {

    $cpf = $_POST["cpf"];
    $dataNascimento = $_POST["dataNascimento"];

    $cpf = mysqli_real_escape_string($conexao, $cpf);
    $dataNascimento = mysqli_real_escape_string($conexao, $dataNascimento);


    $sql = "INSERT INTO Monitores
            (Nome, CPF, DataNascimento, Email, Senha)
            VALUES
            ('$nome', '$cpf', '$dataNascimento', '$email', '$senha')";


    if (mysqli_query($conexao, $sql)) {

        header("Location: criarconta.html?cadastro=sucesso&tipo=monitor");
        exit;

    } else {

        header("Location: criarconta.html?cadastro=erro");
        exit;

    }

}


/*
 * Caso o tipo não seja válido.
 */

else {

    header("Location: criarconta.html?cadastro=erro");
    exit;

}


$conexao->close();

?>