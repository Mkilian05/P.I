<?php 
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'dispositivos'; 
$id_usuario = $_SESSION['id_usuario'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dispositivos.php");
    exit;
}

$id_dispositivo = $_GET['id'];
$erros = $_SESSION['erros'] ?? [];
unset($_SESSION['erros']);

// SEGURANÇA MAXIMA: Verifica dono
$sql = "
    SELECT 
        d.*, 
        ad.quantidade, 
        ad.fk_ambi_id_ambi
    FROM dispositivos d
    JOIN ambiente_dispositivo ad ON d.id_dispositivo = ad.fk_disp_id_disp
    JOIN ambiente a ON ad.fk_ambi_id_ambi = a.id_ambiente
    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
    WHERE d.id_dispositivo = ? 
    AND c.fk_usuario_id_usuario = ? 
    AND (d.is_deleted IS NULL OR d.is_deleted = 0)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_dispositivo, $id_usuario);
$stmt->execute();
$atual = $stmt->get_result()->fetch_assoc();

if (!$atual) {
    header("Location: dispositivos.php");
    exit;
}

// DROPDOWN AMBIENTES (Só meus)
$sql_amb = "SELECT a.id_ambiente, a.nome_ambiente, k.nome_casa 
            FROM ambiente a 
            JOIN casa k ON a.fk_casa_id_casa = k.id_casa 
            WHERE k.fk_usuario_id_usuario = ? 
            AND (a.is_deleted IS NULL OR a.is_deleted = 0)";
$stmt_a = $conn->prepare($sql_amb);
$stmt_a->bind_param("i", $id_usuario);
$stmt_a->execute();
$ambientes = $stmt_a->get_result()->fetch_all(MYSQLI_ASSOC);

$categorias = $conn->query("SELECT * FROM categoria_dispositivos")->fetch_all(MYSQLI_ASSOC);

include_once __DIR__ . '/../includes/header.php'; 
include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <div style="display:flex; align-items:center; gap: 15px;">
        <a href="dispositivos.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);"><i class="fas fa-arrow-left"></i> Voltar</a>
        <div>
            <h1>Configurar Dispositivo</h1>
            <p>Editando: <?php echo htmlspecialchars($atual['nome_dispositivo']); ?></p>
        </div>
    </div>
  </header>

  <?php if (!empty($erros)): ?>
      <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
  <?php endif; ?>

  <section class="card" style="max-width: 800px; margin: 2rem auto;">
      
      <form method="POST" action="../config/processa_dispositivos.php" id="formEditar">
          <input type="hidden" name="acao" value="editar">
          <input type="hidden" name="id_dispositivo" value="<?php echo $atual['id_dispositivo']; ?>">

          <div class="form-group">
              <label for="dispositivoNome">Nome do Dispositivo</label>
              <input type="text" id="dispositivoNome" name="dispositivoNome" value="<?php echo htmlspecialchars($atual['nome_dispositivo']); ?>" required>
          </div>

          <div class="form-row">
              <div class="form-group">
                  <label for="dispositivoPotencia">Potência (W)</label>
                  <input type="number" step="0.1" id="dispositivoPotencia" name="dispositivoPotencia" value="<?php echo htmlspecialchars($atual['potencia_dispositivo']); ?>" required>
              </div>
              <div class="form-group">
                  <label for="dispositivoQuantidade">Quantidade</label>
                  <input type="number" id="dispositivoQuantidade" name="dispositivoQuantidade" value="<?php echo htmlspecialchars($atual['quantidade']); ?>" min="1" required>
              </div>
          </div>

          <div class="form-group">
              <label for="dispositivoAmbiente">Ambiente</label>
              <select id="dispositivoAmbiente" name="dispositivoAmbiente" required>
                  <?php foreach ($ambientes as $amb): ?>
                      <option value="<?php echo $amb['id_ambiente']; ?>" <?php echo ($amb['id_ambiente'] == $atual['fk_ambi_id_ambi']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($amb['nome_ambiente']); ?> (<?php echo htmlspecialchars($amb['nome_casa']); ?>)
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="form-group">
              <label for="dispositivoTipo">Categoria</label>
              <select id="dispositivoTipo" name="dispositivoTipo" required>
                  <?php foreach ($categorias as $cat): ?>
                      <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $atual['fk_cat_disp_id_cat']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($cat['nome_categoria']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
      </form>

      <div class="form-actions">
            <form method="POST" action="../config/processa_dispositivos.php" onsubmit="return confirm('Tem certeza? Isso removerá o dispositivo.');">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id_dispositivo" value="<?php echo $atual['id_dispositivo']; ?>">
                <button type="submit" class="btn-danger"><i class="fas fa-trash"></i> Excluir</button>
            </form>

            <button type="submit" form="formEditar" class="btn-primary">Salvar Alterações</button>
      </div>
  </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; $conn->close(); ?>