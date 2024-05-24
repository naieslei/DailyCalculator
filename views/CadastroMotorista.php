<?php
require_once '../DAO/sessionLogin.php';
require_once '../DAO/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Motorista</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-6 mx-auto ">
        <div class="card bg-dark text-white">
          <div class="card-header">
            <h5 class="card-title">Cadastro de Motorista</h5>
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

            <form action="../crud/CadMotorista.php" method="POST">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do motorista" autocomplete="off" required>
              </div>
              <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" autocomplete="off" placeholder="Digite o CPF do motorista">
              </div>
              <div class="mb-3">
                <label for="funcao" class="form-label">Função</label>
                <select class="form-select" id="funcao" name="funcao">
                  <option value="" disabled selected>Selecione a função</option>
                  <option value="Motorista de Truck">Motorista de Truck</option>
                  <option value="Motorista de Carreta">Motorista de Carreta</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Cadastrar</button>
              <a href="index.php" class="btn btn-success">Voltar</a>

            </form>
          </div>
        </div>
        <div class="card bg-dark text-white mt-3">
          <div class="card-header">
            <h5 class="card-title">Lista de Motoristas</h5>
          </div>
          <div class="card-body">
            <form method="GET" action="">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="filtro_nome" autocomplete="off" placeholder="Filtrar por nome" value="<?php echo isset($_GET['filtro_nome']) ? $_GET['filtro_nome'] : ''; ?>">
                <button class="btn btn-outline-light" type="submit">Filtrar</button>
              </div>
            </form>
            <table class="table table-dark table-striped">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Função</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $filtro_nome = isset($_GET['filtro_nome']) ? $_GET['filtro_nome'] : '';
                $sql = "SELECT * FROM motorista";
                if ($filtro_nome) {
                  $sql .= " WHERE nome LIKE :filtro_nome";
                }
                $sql .= " LIMIT 100";
                $stmt = $pdo->prepare($sql);
                if ($filtro_nome) {
                  $stmt->execute(['filtro_nome' => '%' . $filtro_nome . '%']);
                } else {
                  $stmt->execute();
                }
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . $row['nome'] . "</td>";
                  echo "<td>" . $row['funcao'] . "</td>";
                  echo "<td><button class='btn btn-primary' data-id='" . $row['id'] . " 'data-nome='" . $row['nome'] . "' data-cpf='" . $row['cpf'] . "' data-funcao='" . $row['funcao'] . "' data-bs-toggle='modal' data-bs-target='#editarModal'>Editar</button></td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title" id="editarModalLabel">Editar Motorista</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="../crud/editarMotorista.php" method="POST">
              <input type="hidden" class="form-control" id="idModal" name="id" required>
            <div class="mb-3">
              <label for="nome" class="form-label">Nome</label>
              <input type="text" class="form-control" id="nomeModal" name="nome" required>
            </div>
            <div class="mb-3">
              <label for="cpf" class="form-label">CPF</label>
              <input type="text" class="form-control" id="cpfModal" name="cpf" required>
            </div>
            <div class="mb-3">
              <label for="funcao" class="form-label">Função</label>
              <select class="form-select" id="funcaoModal" name="funcao" required>
                <option value="" disabled selected>Selecione a função</option>
                <option value="Motorista de Truck">Motorista de Truck</option>
                <option value="Motorista de Carreta">Motorista de Carreta</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var editarModal = document.getElementById('editarModal');
      editarModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; 
        var id = button.getAttribute('data-id');
        var nome = button.getAttribute('data-nome');
        var cpf = button.getAttribute('data-cpf');
        var funcao = button.getAttribute('data-funcao');
        var modalBody = editarModal.querySelector('.modal-body');
        modalBody.querySelector('#idModal').value = id;
        modalBody.querySelector('#nomeModal').value = nome;
        modalBody.querySelector('#cpfModal').value = cpf;
        modalBody.querySelector('#funcaoModal').value = funcao;
      });
    });
  </script>
</body>

</html>