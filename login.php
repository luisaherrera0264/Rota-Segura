<?php

session_start();

include("conexao.php");

$email = $_POST["email"];
$senha = $_POST["senha"];

$encontrou = false;


/* =========================
   VERIFICAR RESPONSÁVEL
   ========================= */

$sql = "SELECT idResponsavel, Nome, Email, Senha
        FROM Responsaveis
        WHERE Email = '$email'";

$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario["Senha"])) {

        $_SESSION["id"] = $usuario["idResponsavel"];
        $_SESSION["nome"] = $usuario["Nome"];
        $_SESSION["email"] = $usuario["Email"];
        $_SESSION["tipo"] = "responsavel";

        header("Location: responsavel.html");
        exit;
    }

    $encontrou = true;
}


/* =========================
   VERIFICAR MOTORISTA
   ========================= */

$sql = "SELECT idMotorista, Nome, Email, Senha
        FROM Motoristas
        WHERE Email = '$email'";

$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario["Senha"])) {

        $_SESSION["id"] = $usuario["idMotorista"];
        $_SESSION["nome"] = $usuario["Nome"];
        $_SESSION["email"] = $usuario["Email"];
        $_SESSION["tipo"] = "motorista";

        header("Location: motorista.html");
        exit;
    }

    $encontrou = true;
}


/* =========================
   VERIFICAR MONITOR
   ========================= */

$sql = "SELECT idMonitor, Nome, Email, Senha
        FROM Monitores
        WHERE Email = '$email'";

$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario["Senha"])) {

        $_SESSION["id"] = $usuario["idMonitor"];
        $_SESSION["nome"] = $usuario["Nome"];
        $_SESSION["email"] = $usuario["Email"];
        $_SESSION["tipo"] = "monitor";

        header("Location: monitor.html");
        exit;
    }

    $encontrou = true;
}


/* =========================
   LOGIN INCORRETO
   ========================= */

echo "<script>";

echo "alert('Usuário ou senha incorretos!');";

echo "window.location.href = 'login.html';";

echo "</script>";

$conexao->close();

?>
