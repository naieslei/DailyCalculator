<?php 
session_start(); // Iniciando a sessão

// Incluindo o arquivo de conexão
require_once '../DAO/conexao.php';

// Verifique se todos os campos foram enviados
if (empty($_POST['id']) || empty($_POST['nome']) || empty($_POST['funcao'])) {
    $_SESSION['msgErro'] = "Por favor, preencha todos os campos.";
    header("Location: ../views/CadastroMotorista.php");
    exit();
} else {
    // Dados do formulário de cadastro
    $id = $_POST['id']; // Valor do campo ID
    $nome = $_POST['nome']; // Valor do campo Nome
    $cpf = $_POST['cpf']; // Valor do campo CPF
    $funcao = $_POST['funcao']; // Valor do campo Função
    
    try {
        // Preparando a consulta SQL para atualizar os dados na tabela de motoristas
        $sql = "UPDATE motorista SET nome = :nome, cpf = :cpf, funcao = :funcao WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Ligando os parâmetros da consulta aos valores fornecidos pelo formulário
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':funcao', $funcao);
        
        // Executando a consulta
        $stmt->execute();
        
        // Armazenando mensagem de sucesso
        $_SESSION['msgSucesso'] = "Dados do motorista atualizados com sucesso!";
    } catch (PDOException $e) {
        // Caso ocorra um erro na conexão ou na execução da consulta, armazenar a mensagem de erro
        $_SESSION['msgErro'] = "Erro ao atualizar os dados do motorista: " . $e->getMessage();
    }
    // Redirecionando de volta para a página de cadastro de motorista
    header("Location: ../views/CadastroMotorista.php");
    exit();
}
?>
