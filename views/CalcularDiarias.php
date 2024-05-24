<?php
require_once '../DAO/sessionLogin.php';
require_once '../DAO/conexao.php';
$sql = "SELECT id, UPPER(nome) as nome, UPPER(funcao) as funcao FROM motorista";
$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular Diárias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto centralizar">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="card-title">Calculadora de Diárias</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['msgErro'])) : ?>
                            <p class="msg"><?php echo $_SESSION['msgErro']; ?></p>
                            <?php unset($_SESSION['msgErro']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['msgSucesso'])) : ?>
                            <p class="msg"><?php echo $_SESSION['msgSucesso']; ?></p>
                            <?php unset($_SESSION['msgSucesso']); ?>
                        <?php endif; ?>
                        <form id="formDiaria" name="formDiaria" action="../crud/CadDiaria.php" method="post">
                            <div class="mb-3">
                                <label for="nomeMotorista" class="form-label">Nome do Motorista</label>
                                <?php if ($stmt->rowCount() > 0) { ?>
                                    <select class="form-select" id="nomeMotorista" name="nomeMotorista" onchange="updateFuncao()">
                                        <option value="" disabled selected>Selecione o motorista</option>
                                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?= $row["nome"] ?>" data-id="<?= $row["id"] ?>" data-funcao="<?= $row["funcao"] ?>"><?= $row["nome"] ?></option>
                                    <?php }
                                    } ?>
                                    </select>
                                    <input type="hidden" id="idInput" name="idInput" />
                            </div>
                            <div class="mb-3">
                                <label for="funcao" class="form-label">Função</label>
                                <input type="text" class="form-control" id="funcao" name="funcao" autocomplete="off" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="horafimJornada" class="form-label">Data / Hora inicio da Jornada</label>
                                <input type="datetime-local" class="form-control" id="dtInicio" name="dtInicio" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="horafimJornada" class="form-label">Data / Hora fim da Jornada</label>
                                <input type="datetime-local" class="form-control" id="dtFim" name="dtFim" autocomplete="off" required>
                            </div>
                            <button id="submitForm" class="btn btn-primary" type="button">Registrar</button>
                            <a href="index.php" class="btn btn-success">Voltar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultadoModalLabel">Resultado do Cálculo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="divResultado"></div>

                    <form action="../crud/salvarDiarias.php" method="post" id="formDadosSalvos">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="id" class="form-label">ID</label>
                                    <input type="number" class="form-control" id="id" name="id" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="motorista" class="form-label">Motorista</label>
                                    <input type="text" class="form-control" id="motorista" name="motorista" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="dias" class="form-label">Dias</label>
                                    <input type="text" class="form-control" id="dias" name="dias" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="valorAlmoco" class="form-label">Valor Almoço</label>
                                    <input type="text" class="form-control" id="valorAlmoco" name="valorAlmoco" autocomplete="off" onchange="calcularTotal()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="valorJanta" class="form-label">Valor Janta</label>
                                    <input type="text" class="form-control" id="valorJanta" name="valorJanta" autocomplete="off" onchange="calcularTotal()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="valorPernoite" class="form-label">Valor Pernoite</label>
                                    <input type="text" class="form-control" id="valorPernoite" name="valorPernoite" autocomplete="off" onchange="calcularTotal()">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="total" name="total" readonly>
                                <input type="hidden" class="form-control" id="dataInicio" name="dataInicio" readonly>
                                <input type="hidden" class="form-control" id="dataFim" name="dataFim" readonly>
                                <button class="btn btn-primary ms-2" id="submitBanco" type="submit">Submit</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observacao" class="form-label">Observação</label>
                                <textarea class="form-control" id="obs" name="obs"></textarea>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="form-label" id="resultadoAlmoco" name="resultadoAlmoco" style=" margin-right: 10px;"></div>
                            <div class="form-label" id="resultadoJanta" name="resultadoJanta" style=" margin-right: 10px;"></div>
                            <div class="form-label" id="resultadoPernoite" name="resultadoPernoite" style=" margin-right: 10px;"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/javascript.js"></script>
</body>

</html>