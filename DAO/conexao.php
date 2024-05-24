<?php
require_once 'config.php';
try {
    // Criando uma nova conexão PDO
    $pdo = new PDO("mysql:host=".HOST.";dbname=".DB."","".USER."","".PASS."");
    
    // Configurando o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurando o charset para UTF-8 (opcional)
    $pdo->exec("SET NAMES utf8");
    
    // Exibindo mensagem de sucesso
} catch (PDOException $e) {
    // Caso ocorra um erro na conexão, exibir a mensagem de erro
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}