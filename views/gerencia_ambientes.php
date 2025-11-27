<?php 
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'ambientes'; 
$id_usuario = $_SESSION['id_usuario'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ambientes.php");
    exit;
}

$id_ambiente = $_GET['id'];
$erros = $_SESSION['erros'] ?? [];
unset($_SESSION['erros']);

// SEGURANÇA: Verifica se a CASA do ambiente é do usuário
$sql = "SELECT a.*, c.nome_casa 
        FROM ambiente a
        JOIN casa c ON a.fk_casa_id_casa = c.id_casa
        WHERE a.id_ambiente = ? 
        AND c.fk_usuario_id_usuario = ? 
        AND (a.is_deleted IS NULL OR a.is_deleted = 0)";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_ambiente, $id_usuario);
$stmt->execute();
$ambiente_atual = $stmt->get_result()->fetch_assoc();

if (!$ambiente_atual) {
    header("Location: ambientes.php");
    exit;
}

// Lista apenas CASAS DO USUÁRIO
$sql_casas = "SELECT id_casa, nome_casa FROM casa WHERE fk_usuario_id_usuario = ? AND (is_deleted IS NULL OR is_deleted = 0)";
$stmt_c = $conn->prepare($sql_casas);
$stmt_c->bind_param("i", $id_usuario);
$stmt_c->execute();
$casas = $stmt_c->get_result()->fetch_all(MYSQLI_ASSOC);

include_once __DIR__ . '/../includes/header.php'; 
include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <div style="display:flex; align-items:center; gap: 15px;">
        <a href="ambientes.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);"><i class="fas fa-arrow-left"></i> Voltar</a>
        <div>
            <h1>Editar Ambiente</h1>
            <p>Alterar dados de <?php echo htmlspecialchars($ambiente_atual['nome_ambiente']); ?></p>
        </div>
    </div>
  </header>

  <?php if (!empty($erros)): ?>
      <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
  <?php endif; ?>

  <section class="card" style="max-width: 800px; margin: 2rem auto;">
      
      <form method="POST" action="../config/processa_ambiente.php" id="formEditar">
          <input type="hidden" name="acao" value="editar">
          <input type="hidden" name="id_ambiente" value="<?php echo $ambiente_atual['id_ambiente']; ?>">

          <div class="form-group">
              <label for="ambienteNome">Nome do Ambiente</label>
              <input type="text" id="ambienteNome" name="ambienteNome" value="<?php echo htmlspecialchars($ambiente_atual['nome_ambiente']); ?>" required>
          </div>

          <div class="form-group">
              <label for="ambienteCasa">Casa Pertencente</label>
              <select id="ambienteCasa" name="ambienteCasa" required>
                  <?php foreach ($casas as $casa): ?>
                      <option value="<?php echo $casa['id_casa']; ?>" <?php echo ($casa['id_casa'] == $ambiente_atual['fk_casa_id_casa']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($casa['nome_casa']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
      </form>

      <div class="form-actions">
            <form method="POST" action="../config/processa_ambiente.php" onsubmit="return confirm('Tem certeza? Isso removerá o ambiente.');">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id_ambiente" value="<?php echo $ambiente_atual['id_ambiente']; ?>">
                <button type="submit" class="btn-danger"><i class="fas fa-trash"></i> Excluir</button>
            </form>

            <button type="submit" form="formEditar" class="btn-primary">Salvar Alterações</button>
      </div>
  </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; $conn->close(); ?>