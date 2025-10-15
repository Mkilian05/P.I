<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  // Se não estiver logado, redireciona para o login
  header("Location: ../login.php");
  exit;
}else{
  echo"Você está logado";
}

$page = 'dashboard_admin';
include_once '../../includes/header.php';
include_once '../../includes/navbar.php';
?>

<main class="container-dashboard my-5 py-5 fade-in">
  <div class="text-end mb-3">
    <p class="fw-semibold text-success mb-0">
      👋 Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!
    </p>
    <a href="../../config/logout.php" class="btn btn-sm btn-outline-danger mt-1">
      <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
    </a>
  </div>

  <h1 class="dashboard-title">Painel Administrativo</h1>

  <div class="dashboard-cards mb-5">
    <div class="dashboard-card">
      <i class="fa-solid fa-users"></i>
      <h5>Usuários Cadastrados</h5>
      <h3 class="fw-bold">12</h3>
    </div>
  
    <div class="dashboard-card">
      <i class="fa-solid fa-house"></i>
      <h5>Casas Cadastradas</h5>
      <h3 class="fw-bold">8</h3>
    </div>
  </div>

  <div class="table-container">
    <h4 class="mb-3 text-start"><i class="fa-solid fa-user"></i> Lista de Usuários</h4>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
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
              <button class="icon-btn edit me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Ana Souza</td>
            <td>ana@email.com</td>
            <td>2025-10-09</td>
            <td>
              <button class="icon-btn edit me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Carlos Silva</td>
            <td>carlos@email.com</td>
            <td>2025-10-08</td>
            <td>
              <button class="icon-btn edit me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td>Juliana Alves</td>
            <td>juliana@email.com</td>
            <td>2025-10-07</td>
            <td>
              <button class="icon-btn edit me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>5</td>
            <td>Ricardo Mendes</td>
            <td>ricardo@email.com</td>
            <td>2025-10-06</td>
            <td>
              <button class="icon-btn edit me-2"><i class="fa-solid fa-pen"></i></button>
              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
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
