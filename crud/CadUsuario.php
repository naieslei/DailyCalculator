<?php
session_start(); // Iniciando a sessão

// Incluindo o arquivo de conexão
require_once '../DAO/conexao.php';

if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['confirmarSenha'])) {
    $_SESSION['msgErro'] = "Por favor, preencha todos os campos.";
    header("Location: ../views/CadastroUsuario.php");
    exit();
} else {
    // Dados do formulário de cadastro
    $nome = $_POST['nome']; // Valor do campo Nome
    $email = $_POST['email']; // Valor do campo Email
    $senha = $_POST['senha']; // Valor do campo Senha
    $confSenha = $_POST['confirmarSenha'];
    $dataCadastro =  date('Y-m-d H:i:s');
    $dataUpdate = null;

    try {
        if(strlen($senha) < 8){
            $_SESSION['msgErro'] = "A senha deve conter pelo menos 8 caracteres";
        } elseif ($senha !== $confSenha) {
            $_SESSION['msgErro'] = "As senhas não coincidem.";
        } else {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $_SESSION['msgErro'] = "Email já cadastrado";
            } else {
                $hashSenha = md5($senha);
                // Preparando a consulta SQL para inserir os dados na tabela de usuários
                $sql = "INSERT INTO usuario (user, email, password, data_cadastro, data_update) VALUES (:user, :email, :password, :data_cadastro, :data_update)";
                $stmt = $pdo->prepare($sql);
                
                // Ligando os parâmetros da consulta aos valores fornecidos pelo formulário
                $stmt->bindParam(':user', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashSenha);
                $stmt->bindParam(':data_cadastro', $dataCadastro);
                $stmt->bindParam(':data_update', $dataUpdate);

                
                // Executando a consulta
                $stmt->execute();
                
                // Armazenando mensagem de sucesso
                $_SESSION['msgSucesso'] = "Cadastro realizado com sucesso!";
            }
        }
    } catch (PDOException $e) {
        // Caso ocorra um erro na conexão ou na execução da consulta, armazenar a mensagem de erro
        $_SESSION['msgErro'] = "Erro ao cadastrar usuário: " . $e->getMessage();
    }
    header("Location: ../views/CadastroUsuario.php");
    exit();
}
?>
