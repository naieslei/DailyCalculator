<?php 

session_start(); // Iniciando a sessão

// Incluindo o arquivo de conexão
require_once '../DAO/conexao.php';

if (empty($_POST['nome']) || empty($_POST['funcao'])) {
    $_SESSION['msgErro'] = "Por favor, preencha todos os campos.";
    header("Location: ../views/CadastroMotorista.php");
    exit();
} else {
    // Dados do formulário de cadastro
    $nome = $_POST['nome']; // Valor do campo Nome
    $cpf = $_POST['cpf']; // Valor do campo Email
    $funcao = $_POST['funcao']; // Valor do campo Senha
    try {
        // Preparando a consulta SQL para inserir os dados na tabela de usuários
        $sql = "INSERT INTO motorista (nome, cpf, funcao) VALUES (:nome, :cpf, :funcao)";
        $stmt = $pdo->prepare($sql);
        
        // Ligando os parâmetros da consulta aos valores fornecidos pelo formulário
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':funcao', $funcao);
        $stmt->execute();
        
        // Armazenando mensagem de sucesso
        $_SESSION['msgSucesso'] = "Cadastro realizado com sucesso!";
    } catch (PDOException $e) {
        // Caso ocorra um erro na conexão ou na execução da consulta, armazenar a mensagem de erro
        $_SESSION['msgErro'] = "Erro ao cadastrar usuário: " . $e->getMessage();
    }
    header("Location: ../views/CadastroMotorista.php");
    exit();
}