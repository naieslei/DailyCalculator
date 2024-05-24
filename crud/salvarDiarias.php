<?php
require_once '../DAO/conexao.php';
if (
    isset($_POST['id']) && isset($_POST['motorista']) && isset($_POST['dias']) &&
    isset($_POST['valorAlmoco']) && isset($_POST['valorJanta']) &&
    isset($_POST['valorPernoite']) && isset($_POST['total'])
) {

    $id = $_POST['id'];
    $motorista = $_POST['motorista'];
    $dias = $_POST['dias'];
    $valorAlmoco = $_POST['valorAlmoco'];
    $valorJanta = $_POST['valorJanta'];
    $valorPernoite = $_POST['valorPernoite'];
    $total = $_POST['total'];
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];
    $obs = $_POST['obs'];

    try {
        $sql = "INSERT INTO diarias (idMotorista, data_inicial, data_final, total_dias, val_almoco, val_janta, val_pernoite, val_total, obs)
         VALUES (:idMotorista, :data_inicial, :data_final, :total_dias, :val_almoco, :val_janta, :val_pernoite, :val_total, :obs)";
                $stmt = $pdo->prepare($sql);
                
                // Ligando os parâmetros da consulta aos valores fornecidos pelo formulário
                $stmt->bindParam(':idMotorista', $id);
                $stmt->bindParam(':data_inicial', $dataInicio);
                $stmt->bindParam(':data_final', $dataFim);
                $stmt->bindParam(':total_dias', $dias);
                $stmt->bindParam(':val_almoco', $valorAlmoco);
                $stmt->bindParam(':val_janta', $valorJanta);
                $stmt->bindParam(':val_pernoite', $valorPernoite);
                $stmt->bindParam(':val_total', $total);
                $stmt->bindParam(':obs', $obs);

                
                // Executando a consulta
                $stmt->execute();
        echo json_encode(array("retorno" => "success"));
    } catch (PDOException $e) {
        echo json_encode(array("retorno" => "error", "erros" => "Erro ao salvar diária: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("retorno" => "error", "erros" => "Por favor, preencha todos os campos do formulário."));
}