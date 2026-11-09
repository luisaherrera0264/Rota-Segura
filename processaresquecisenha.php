<?php
$jsonFile = 'usuarios.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo']; // 'responsavel', 'monitor' ou 'motorista'
    $email = trim($_POST['email']);
    $novaSenha = $_POST['nova_senha'];

    if (!file_exists($jsonFile)) {
        echo "<script>alert('Arquivo de base de dados JSON não encontrado!'); window.location='esquecisenha.html';</script>";
        exit;
    }

    $jsonData = json_decode(file_get_contents($jsonFile), true);
    
    // Mapeia a chave do JSON conforme o tipo selecionado
    $chaveArr = '';
    if ($tipo == 'responsavel') $chaveArr = 'responsaveis';
    elseif ($tipo == 'monitor') $chaveArr = 'monitores';
    elseif ($tipo == 'motorista') $chaveArr = 'motoristas';

    $encontrado = false;

    if (isset($jsonData[$chaveArr])) {
        foreach ($jsonData[$chaveArr] as &$usuario) {
            if (trim($usuario['email']) === $email) {
                $usuario['senha'] = $novaSenha; // Atualiza a senha
                $encontrado = true;
                break;
            }
        }
        unset($usuario);
    }

    if ($encontrado) {
        // Salva de volta no arquivo JSON
        file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));
        echo "<script>alert('Senha atualizada com sucesso!'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('E-mail não encontrado para este perfil.'); window.history.back();</script>";
    }
}
?>