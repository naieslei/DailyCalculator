<?php
require_once '../DAO/conexao.php';

$pagina = isset($_GET['pagina']) ? filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) : 1;
$motorista = isset($_GET['motorista']) ? filter_input(INPUT_GET, 'motorista', FILTER_SANITIZE_SPECIAL_CHARS) : '';
$dataInicial = isset($_GET['data-inicial']) ? filter_input(INPUT_GET, 'data-inicial', FILTER_SANITIZE_SPECIAL_CHARS) : '';
$dataFinal = isset($_GET['data-final']) ? filter_input(INPUT_GET, 'data-final', FILTER_SANITIZE_SPECIAL_CHARS) : '';

if (!$pagina) {
    $pagina = 1;
}
$limite = 10;
$inicio = ($pagina * $limite) - $limite;

$where = [];
if ($motorista) {
    $where[] = "m.nome LIKE '%$motorista%'";
}
if ($dataInicial) {
    $dataInicial .= ' 00:00:00';
    $where[] = "d.data_inicial >= '$dataInicial'";
}
if ($dataFinal) {
    $dataFinal .= ' 23:59:59';
    $where[] = "d.data_final <= '$dataFinal'";
}

$whereSql = '';
if (!empty($where)) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}

$registros = $pdo->query("SELECT count(d.idDiaria) as count FROM diarias as d JOIN motorista as m ON d.idMotorista = m.id $whereSql")->fetch()['count'];
$paginas = ceil($registros / $limite);

$sql = "SELECT UPPER(m.nome) as nome, 
               DATE_FORMAT(d.data_inicial, '%d/%m/%Y %H:%i') as data_inicial, 
               DATE_FORMAT(d.data_final, '%d/%m/%Y %H:%i') as data_final, 
               ROUND(d.val_almoco / 31.5, 0) as almoco, 
               ROUND(d.val_janta / 31.5, 0) as janta, 
               ROUND(d.val_pernoite / 34, 0) as pernoite, 
               CONCAT('R$', FORMAT(d.val_total, 2, 'pt_BR')) as val_total
        FROM diarias as d 
        JOIN motorista as m ON d.idMotorista = m.id 
        $whereSql
        ORDER BY m.nome 
        LIMIT $inicio, $limite";
$stmt = $pdo->query($sql);

$table = '<table class="table table-dark table-striped text-center">
            <thead>
                <tr>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Data inicial</th>
                    <th class="text-center">Data final</th>
                    <th class="text-center">Total de Almoço</th>
                    <th class="text-center">Total de Janta</th>
                    <th class="text-center">Total de Pernoite</th>
                    <th class="text-center">Total a pagar</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $table .= "<tr>";
    $table .= "<td class='text-center'>" . $row['nome'] . "</td>";
    $table .= "<td class='text-center'>" . $row['data_inicial'] . "</td>";
    $table .= "<td class='text-center'>" . $row['data_final'] . "</td>";
    $table .= "<td class='text-center'>" . $row['almoco'] . "</td>";
    $table .= "<td class='text-center'>" . $row['janta'] . "</td>";
    $table .= "<td class='text-center'>" . $row['pernoite'] . "</td>";
    $table .= "<td class='text-center'>" . $row['val_total'] . "</td>";
    $table .= "</tr>";
}
$table .= '</tbody></table>';

$pagination = '';
if ($paginas > 1) {
    if ($pagina > 1) {
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='" . ($pagina - 1) . "'>Anterior</a></li>";
    }

    for ($i = 1; $i <= $paginas; $i++) {
        $active = $i == $pagina ? 'active' : '';
        $pagination .= "<li class='page-item {$active}'><a class='page-link' href='#' data-page='{$i}'>{$i}</a></li>";
    }

    if ($pagina < $paginas) {
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='" . ($pagina + 1) . "'>Próximo</a></li>";
    }
}

echo json_encode(['table' => $table, 'pagination' => $pagination]);
