<?php
 $page = 'dashboard_admin';
 include_once '../../includes/header.php';
 include_once '../../includes/navbar.php';
?>

<main class="container my-5 fade-in">
  <h1 class="text-center mb-4">Painel Administrativo</h1>

  <!-- Cards de estatísticas -->
  <div class="row g-4 mb-5 justify-content-center">
    <div class="col-md-4">
      <div class="card info-card">
        <div class="card-body text-center">
          <i class="fa-solid fa-users fa-2x mb-2"></i>
          <h5>Usuários Cadastrados</h5>
          <h3>12</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card info-card">
        <div class="card-body text-center">
          <i class="fa-solid fa-house fa-2x mb-2"></i>
          <h5>Casas Cadastradas</h5>
          <h3>8</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela de usuários -->
  <div class="card shadow-sm p-3">
    <h4 class="mb-3"><i class="fa-solid fa-user"></i> Lista de Usuários</h4>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Cadastro</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Matheus Kilian</td>
            <td>matheus@email.com</td>
            <td>2025-10-10</td>
            <td>
              <button class="btn btn-sm btn-warning me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Ana Souza</td>
            <td>ana@email.com</td>
            <td>2025-10-09</td>
            <td>
              <button class="btn btn-sm btn-warning me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Carlos Silva</td>
            <td>carlos@email.com</td>
            <td>2025-10-08</td>
            <td>
              <button class="btn btn-sm btn-warning me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>Juliana Alves</td>
            <td>juliana@email.com</td>
            <td>2025-10-07</td>
            <td>
              <button class="btn btn-sm btn-warning me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>Ricardo Mendes</td>
            <td>ricardo@email.com</td>
            <td>2025-10-06</td>
            <td>
              <button class="btn btn-sm btn-warning me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</main>


<?php
include_once('../../includes/footer.php'); 
?>
