<?php 
// 1. BLINDAGEM DE LOGIN
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'casas'; 
$id_usuario = $_SESSION['id_usuario'];

// 2. Validação do ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: casas.php");
    exit;
}

$id_casa = $_GET['id'];
$erros = $_SESSION['erros'] ?? [];
unset($_SESSION['erros']);

// 3. BUSCAR DADOS (COM SEGURANÇA)
$sql = "SELECT * FROM casa 
        WHERE id_casa = ? 
        AND fk_usuario_id_usuario = ? 
        AND (is_deleted IS NULL OR is_deleted = 0)";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_casa, $id_usuario);
$stmt->execute();
$casa_atual = $stmt->get_result()->fetch_assoc();

// 4. SE NÃO FOR SUA, TCHAU
if (!$casa_atual) {
    header("Location: casas.php");
    exit;
}

$estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

include_once __DIR__ . '/../includes/header.php'; 
include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <div style="display:flex; align-items:center; gap: 15px;">
        <a href="casas.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);"><i class="fas fa-arrow-left"></i> Voltar</a>
        <div>
            <h1>Editar Casa</h1>
            <p>Alterar dados de <?php echo htmlspecialchars($casa_atual['nome_casa']); ?></p>
        </div>
    </div>
  </header>

  <?php if (!empty($erros)): ?>
      <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
  <?php endif; ?>

  <section class="card" style="max-width: 800px; margin: 2rem auto;">
      
      <form method="POST" action="../config/processa_casas.php" id="formEditar">
          <input type="hidden" name="acao" value="editar">
          <input type="hidden" name="id_casa" value="<?php echo $casa_atual['id_casa']; ?>">

          <div class="form-group">
              <label for="casaNome">Nome da Casa</label>
              <input type="text" id="casaNome" name="casaNome" value="<?php echo htmlspecialchars($casa_atual['nome_casa']); ?>" required>
          </div>

          <div class="form-row">
              <div class="form-group">
                  <label for="casaCidade">Cidade</label>
                  <input type="text" id="casaCidade" name="casaCidade" value="<?php echo htmlspecialchars($casa_atual['cidade']); ?>" required>
              </div>
              <div class="form-group">
                  <label for="casaEstado">Estado</label>
                  <select id="casaEstado" name="casaEstado" required>
                      <?php foreach ($estados as $uf): ?>
                          <option value="<?php echo $uf; ?>" <?php echo ($uf == $casa_atual['sigla_estado']) ? 'selected' : ''; ?>>
                              <?php echo $uf; ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>
          </div>

          <div class="form-row">
              <div class="form-group">
                  <label for="casaBairro">Bairro</label>
                  <input type="text" id="casaBairro" name="casaBairro" value="<?php echo htmlspecialchars($casa_atual['bairro']); ?>" required>
              </div>
              <div class="form-group">
                  <label for="casaCep">CEP</label>
                  <input type="text" id="casaCep" name="casaCep" value="<?php echo htmlspecialchars($casa_atual['cep']); ?>" required>
              </div>
          </div>

          <div class="form-group">
              <label for="casaNumero">Número</label>
              <input type="text" id="casaNumero" name="casaNumero" value="<?php echo htmlspecialchars($casa_atual['numero_casa']); ?>" required>
          </div>
      </form>

      <div class="form-actions">
            <form method="POST" action="../config/processa_casas.php" onsubmit="return confirm('Tem certeza? Isso removerá a casa e seus ambientes.');">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id_casa" value="<?php echo $casa_atual['id_casa']; ?>">
                <button type="submit" class="btn-danger"><i class="fas fa-trash"></i> Excluir Casa</button>
            </form>

            <button type="submit" form="formEditar" class="btn-primary">Salvar Alterações</button>
      </div>
  </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; $conn->close(); ?>