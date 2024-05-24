<?php

if (isset($_POST['nomeMotorista']) && isset($_POST['funcao']) && isset($_POST['dtInicio']) && isset($_POST['dtFim'])) {

    if ($_POST['dtFim'] < $_POST['dtInicio'])
        echo json_encode(array("retorno" => "error", "erros" => "Data de Inicio maior do que data final"));
    else {

        $motorista = $_POST['nomeMotorista'];
        $funcao = $_POST['funcao'];
        $dataHoraInicio = new DateTime($_POST["dtInicio"]);
        $dataHoraFinal = new DateTime($_POST["dtFim"]);
        $id = $_POST['idInput'];
        // Valores
        $valorAlmocoJanta = 31.50;
        $valorPernoite = 34;

        // Horas de referência
        $horaInicioAlmoco = new DateTime("11:40");
        $horaFinalAlmoco = new DateTime("11:20");

        $horaInicioJanta = new DateTime("19:40");
        $horaFinalJanta = new DateTime("19:20");

        $horaInicioPernoite = new DateTime("23:59");
        $horaFinalPernoite = new DateTime("20:50");

        // Convertido em Data
        $dataInicioViagem = new DateTime($dataHoraInicio->format('Y-m-d'));
        $dataFimViagem = new DateTime($dataHoraFinal->format('Y-m-d'));

        // Horas de Início e Chegada da Viagem
        $horaInicioViagem = new DateTime($dataHoraInicio->format('H:i:s'));
        $horaChegadaViagem = new DateTime($dataHoraFinal->format('H:i:s'));


        // Diferença de dias
        $diferenca = $dataFimViagem->diff($dataInicioViagem);
        $diferencaDias = $diferenca->days;

        // Valores a receber
        $valorAlmocoRecebe = 0;
        $valorJantaRecebe = 0;
        $valorPernoiteRecebe = 0;

        $resultado = "";
        $resultadoAlmoco = "";
        $resultadoJanta = "";
        $resultadoPernoite = "";

        $resultado .= "Inicio viagem: " . $dataHoraInicio->format('Y-m-d H:i:s') . "<br>";
        $resultado .= "Fim viagem: " . $dataHoraFinal->format('Y-m-d H:i:s') . "<br>";
        $resultado .= "Diferença dias: " . $diferencaDias . "<br>";

        if ($diferencaDias > 0) {
            if ($horaInicioViagem < $horaInicioAlmoco || $horaChegadaViagem >= $horaFinalAlmoco && $horaChegadaViagem <= $horaFinalAlmoco)
                $valorAlmocoRecebe += $valorAlmocoJanta;

            for ($i = 1; $i <= $diferencaDias; $i++) {
                $dataRecebeAlmoco = new DateTime($dataInicioViagem->format('Y-m-d') . " " . $horaFinalAlmoco->format('H:i:s'));
                $dataRecebeAlmoco->modify("+$i day");
                if ($dataHoraFinal > $dataRecebeAlmoco)
                    $valorAlmocoRecebe += $valorAlmocoJanta;
            }

            if ($horaInicioViagem <= $horaInicioJanta)
                $valorJantaRecebe += $valorAlmocoJanta;

            for ($i = 1; $i <= $diferencaDias; $i++) {
                $dataRecebeJanta = new DateTime($dataInicioViagem->format('Y-m-d') . " " . $horaFinalJanta->format('H:i:s'));
                $dataRecebeJanta->modify("+$i day");
                if ($dataHoraFinal >= $dataRecebeJanta)
                    $valorJantaRecebe += $valorAlmocoJanta;
            }

            if ($horaInicioViagem < $horaInicioPernoite)
                $valorPernoiteRecebe += $valorPernoite;

            for ($i = 1; $i <= $diferencaDias; $i++) {
                $dataRecebePernoite = new DateTime($dataInicioViagem->format('Y-m-d') . " " . $horaFinalPernoite->format('H:i:s'));
                $dataRecebePernoite->modify("+$i day");
                if ($dataHoraFinal >= $dataRecebePernoite)
                    $valorPernoiteRecebe += $valorPernoite;
            }
        } else {
            if ($horaInicioViagem < $horaInicioAlmoco) {
                $valorAlmocoRecebe += $valorAlmocoJanta;
                if ($horaInicioViagem < $horaInicioAlmoco && $horaChegadaViagem < $horaInicioAlmoco)
                    $valorAlmocoRecebe -= $valorAlmocoJanta;
            }

            if ($horaInicioViagem <= $horaInicioJanta && $horaChegadaViagem >= $horaFinalJanta)
                $valorJantaRecebe += $valorAlmocoJanta;

            if ($horaInicioViagem < $horaInicioPernoite && $horaChegadaViagem >= $horaFinalPernoite)
                $valorPernoiteRecebe += $valorPernoite;
        }

        $total = $valorAlmocoRecebe + $valorJantaRecebe + $valorPernoiteRecebe;
        if ($valorAlmocoRecebe > 31.5)
            $resultadoAlmoco .= "Qtd Almoço: " . $valorAlmocoRecebe / 31.50;
        else
            $resultadoAlmoco .= "Qtd Almoço: " . $valorAlmocoRecebe / 31.50;
        if ($valorJantaRecebe > 31.5)
            $resultadoJanta .= "Qtd Janta: " . $valorJantaRecebe / 31.50;
        else
            $resultadoJanta .= "Qtd Janta: " . $valorJantaRecebe / 31.50;
        if ($valorPernoiteRecebe > 34)
            $resultadoPernoite .= "Qtd Pernoite: " . $valorPernoiteRecebe / 34;
        else
            $resultadoPernoite .= "Qtd Pernoite: " . $valorPernoiteRecebe / 34;

        $resultado .= "Total: " . $total;

        echo json_encode(array(
            "retorno" => "success",
            "id" => $id,
            "motorista" => $motorista,
            "dias" => $diferencaDias,
            "valorAlmoco" => $valorAlmocoRecebe,
            "valorJanta" => $valorJantaRecebe,
            "valorPernoite" => $valorPernoiteRecebe,
            "total" => $total,
            "dataInicio" =>$dataHoraInicio->format('Y-m-d H:i:s'),
            "dataFinal" => $dataHoraFinal->format('Y-m-d H:i:s'),
            "resultadoAlmoco" => $resultadoAlmoco,
            "resultadoJanta" => $resultadoJanta,
            "resultadoPernoite" => $resultadoPernoite,
            "resultado" => $resultado
        ));
    }
} else {
    echo json_encode(array("retorno" => "error", "erros" => "Por favor, preencha todos os campos do formulário."));
}
?>
