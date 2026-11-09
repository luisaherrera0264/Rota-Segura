<?php
session_start();
$jsonFile = 'usuarios.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? ''; // 'responsavel', 'monitor' ou 'motorista'

    if (!file_exists($jsonFile)) {
        echo "<script>alert('Base de dados JSON não encontrada!'); window.location='login.html';</script>";
        exit;
    }

    $jsonData = json_decode(file_get_contents($jsonFile), true);
    
    // Mapeia a chave conforme o tipo selecionado
    $chaveArr = '';
    if ($tipo == 'responsavel') $chaveArr = 'responsaveis';
    elseif ($tipo == 'monitor') $chaveArr = 'monitores';
    elseif ($tipo == 'motorista') $chaveArr = 'motoristas';

    $autenticado = false;

    if (!empty($chaveArr) && isset($jsonData[$chaveArr])) {
        foreach ($jsonData[$chaveArr] as $usuario) {
            if (trim($usuario['email']) === $email && $usuario['senha'] === $senha) {
                $autenticado = true;
                break;
            }
        }
    }

    if ($autenticado) {
        $_SESSION['email'] = $email;
        $_SESSION['tipo'] = $tipo;

        // Redireciona para a página correspondente ao perfil
        if ($tipo == 'responsavel') {
            header("Location: responsavel.html");
        } elseif ($tipo == 'monitor') {
            header("Location: monitor.html");
        } elseif ($tipo == 'motorista') {
            header("Location: motorista.html");
        }
        exit;
    } else {
        echo "<script>alert('E-mail, senha ou perfil incorretos!'); window.history.back();</script>";
    }
}
?>