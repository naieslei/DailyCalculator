<?php require_once '../DAO/sessionLogin.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de usuario</title>
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
            <h5 class="card-title">Cadastro de Usuário</h5>
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

            <form action="../crud/CadUsuario.php" method="POST">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" autocomplete="off" placeholder="Digite seu nome">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" autocomplete="off" placeholder="Digite seu email">
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="senha" class="form-label">Senha</label>
                  <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha">
                </div>
                <div class="col">
                  <label for="confirmarSenha" class="form-label">Confirmação de Senha</label>
                  <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" placeholder="Confirme sua senha">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Cadastrar</button>
              <a href="index.php" class="btn btn-success">Voltar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>