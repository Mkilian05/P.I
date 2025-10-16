<?php
session_start();
require_once '../../config/database.php'; // Adicionado para conectar ao banco

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../views/login.php");
  exit;
}

// ---- LÓGICA PARA BUSCAR DADOS DO BANCO ----

// 1. Contar o total de usuários cadastrados
$query_total_usuarios = "SELECT COUNT(id_usuario) as total FROM usuarios";
$resultado_total = $conn->query($query_total_usuarios);
$total_usuarios = $resultado_total->fetch_assoc()['total'];

// 2. Buscar todos os usuários para listar na tabela
$query_usuarios = "SELECT id_usuario, nome_usuario, email FROM usuarios ORDER BY nome_usuario DESC";
$resultado_usuarios = $conn->query($query_usuarios);

// ---------------------------------------------

$page = 'dashboard_admin';
include_once '../../includes/header.php';
include_once '../../includes/navbar.php';
?>

<main class="container-dashboard my-5 py-5 fade-in">
  <div class="text-end mb-3">
    <?php if (isset($_GET['sucesso'])): ?>
      <div class="alert alert-success alert-dismissible fade show text-center rounded-3 py-2 mb-4" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i>
        <?php echo htmlspecialchars($_GET['sucesso']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <a href="../../config/logout.php" class="btn btn-sm btn-outline-danger mt-1">
      <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
    </a>
  </div>

  <h1 class="dashboard-title">Painel Administrativo</h1>

  <h5 class="fw-semibold text-success text-center mb-5">
    👋 Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!
  </h5>

  <div class="dashboard-cards mb-5">
    <div class="dashboard-card">
      <i class="fa-solid fa-users"></i>
      <h5>Usuários Cadastrados</h5>
      <h3 class="fw-bold"><?= $total_usuarios; ?></h3>
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
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Verifica se há usuários para exibir
          if ($resultado_usuarios->num_rows > 0) {
            // Loop para criar uma linha da tabela para cada usuário
            while ($usuario = $resultado_usuarios->fetch_assoc()) {
              ?>
              <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']); ?></td>
                <td><?= htmlspecialchars($usuario['nome_usuario']); ?></td>
                <td><?= htmlspecialchars($usuario['email']); ?></td>
                <td>
                <td>
                  <a href="../../views/editar_usuarios.php?id=<?= $usuario['id_usuario']; ?>" class="icon-btn edit me-2" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <a href="../../config/processa_exclusao.php?id=<?= $usuario['id_usuario']; ?>" class="icon-btn delete"
                    title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </td>
                </td>
              </tr>
              <?php
            }
          } else {
            // Mensagem exibida se não houver nenhum usuário cadastrado
            echo '<tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
include_once('../../includes/footer.php');
?>