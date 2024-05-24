<?php
require_once '../DAO/sessionLogin.php';
require_once '../DAO/conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Diárias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        #tableContainer {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto centralizar">
                <div class="card bg-dark text-white">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Listagem de diárias</h5>
                        <a href="index.php" class="btn btn-success">Voltar</a>
                    </div>

                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="motorista" class="form-label">Motorista</label>
                                        <input type="text" class="form-control" id="motorista" name="motorista" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="data-inicial" class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" id="data-inicial" name="data-inicial">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="data-final" class="form-label">Data Final</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="data-final" name="data-final">
                                            <button class="btn btn-primary ms-2" type="submit">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="tableContainer"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="paginationContainer"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/JavaScript.js"></script>
</body>

</html>