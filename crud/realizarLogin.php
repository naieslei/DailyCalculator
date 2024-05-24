<?php
require_once '../DAO/conexao.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $usuario = htmlspecialchars($_POST['usuario']);
    $senha = htmlspecialchars($_POST['password']);
    $cripSenha = md5($senha);

    // Prepara e executa a consulta SQL
    $sql = "SELECT * FROM usuario WHERE user = :user AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user' => $usuario, 'password' => $cripSenha]);

    // Verifica se encontrou um usuário com as credenciais fornecidas
    if ($stmt->rowCount() > 0) {
        // Inicia a sessão e redireciona para a página secreta
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['ativo'] = true;
        header("Location: ../views/");
        exit(); // Termina o script após o redirecionamento
    } else {
        // Exibe uma mensagem de erro caso as credenciais sejam inválidas
        echo '<div class="alert alert-danger">Usuário ou senha inválidos.</div>';
    }
}
?>
