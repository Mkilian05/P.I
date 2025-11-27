<?php 
  $page = 'casas'; 
  require_once __DIR__ . '/../includes/auth.php';
  require_once __DIR__ . '/../config/database.php';
  
  // Define um ID de usuário padrão (caso login não esteja pronto)
  // Se você já tiver login, use: $id_usuario = $_SESSION['id_usuario'];
  $id_usuario = $_SESSION['id_usuario'] ?? 1; 

  $erros = $_SESSION['erros'] ?? [];
  $sucesso = $_SESSION['sucesso'] ?? null;
  unset($_SESSION['erros'], $_SESSION['sucesso']);

  // 1. BUSCAR CASAS DO USUÁRIO
  // Filtra por usuário e remove as deletadas
  $sql_casas = "
      SELECT * FROM casa 
      WHERE fk_usuario_id_usuario = ? 
      AND (is_deleted IS NULL OR is_deleted = 0)
      ORDER BY nome_casa ASC
  ";
  
  $stmt = $conn->prepare($sql_casas);
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $result = $stmt->get_result();
  $lista_casas = $result->fetch_all(MYSQLI_ASSOC);

  // Lista de Estados para o Select
  $estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

  include_once __DIR__ . '/../includes/header.php'; 
  include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <h1>Minhas Casas</h1>
    <p>Gerencie suas residências e locais de instalação.</p>
  </header>

  <?php if (!empty($erros)): ?>
      <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
  <?php endif; ?>
  
  <?php if ($sucesso): ?>
      <div class="alerta sucesso"><p><?php echo htmlspecialchars($sucesso); ?></p></div>
  <?php endif; ?>

  <section class="casas-grid">
      
      <?php foreach ($lista_casas as $casa): ?>
      <div class="casa-card">
          <div class="casa-header">
              <h2><?php echo htmlspecialchars($casa['nome_casa']); ?></h2>
              <i class="fas fa-home casa-icon"></i>
          </div>
          
          <div class="casa-tag">
              <i class="fas fa-map-marker-alt"></i>
              <?php echo htmlspecialchars($casa['cidade']) . ' - ' . htmlspecialchars($casa['sigla_estado']); ?>
          </div>
          <p style="font-size: 0.85rem; color: #888; margin-bottom: 15px;">
              <?php echo htmlspecialchars($casa['bairro']); ?>
          </p>
          
          <a href="gerencia_casas.php?id=<?php echo $casa['id_casa']; ?>" class="btn-editar-card">
              Configurar
          </a>
      </div>
      <?php endforeach; ?>
      
      <div class="add-casa-card" id="addCasaCard">
          <div class="add-circle">
              <i class="fas fa-plus icon-plus"></i>
          </div>
          <p>Nova Casa</p>
      </div>

  </section>

</main>

<div class="modal-backdrop hidden" id="modalCasa">
    <div class="modal-content">
        <header class="modal-header">
            <h2>Cadastrar Nova Casa</h2>
            <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        </header>

        <form class="modal-body" id="formCasa" method="POST" action="../config/processa_casa.php">
            <input type="hidden" name="acao" value="cadastrar">
            
            <div class="form-group">
                <label for="casaNome">Nome da Casa (Apelido)</label>
                <input type="text" id="casaNome" name="casaNome" placeholder="Ex: Casa de Praia" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="casaCidade">Cidade</label>
                    <input type="text" id="casaCidade" name="casaCidade" required>
                </div>
                <div class="form-group">
                    <label for="casaEstado">Estado</label>
                    <select id="casaEstado" name="casaEstado" required>
                        <option value="" disabled selected>UF</option>
                        <?php foreach ($estados as $uf): ?>
                            <option value="<?php echo $uf; ?>"><?php echo $uf; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="casaBairro">Bairro</label>
                    <input type="text" id="casaBairro" name="casaBairro" required>
                </div>
                <div class="form-group">
                    <label for="casaCep">CEP</label>
                    <input type="text" id="casaCep" name="casaCep" placeholder="00000-000" required>
                </div>
            </div>

            <div class="form-group">
                <label for="casaNumero">Número</label>
                <input type="text" id="casaNumero" name="casaNumero" required>
            </div>

        </form>

        <footer class="modal-footer">
            <button class="btn-outline" id="modalCancelBtn">Cancelar</button>
            <button type="submit" form="formCasa" class="btn-primary">Salvar Casa</button>
        </footer>
    </div>
</div>

<?php 
  include_once __DIR__ . '/../includes/footer.php'; 
  $conn->close();
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addCard = document.getElementById('addCasaCard');
    const modal = document.getElementById('modalCasa');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    const fechar = () => modal.classList.add('hidden');
    const abrir = (e) => { if(e) e.preventDefault(); modal.classList.remove('hidden'); };

    if (addCard) addCard.addEventListener('click', abrir);
    if (closeBtn) closeBtn.addEventListener('click', fechar);
    if (cancelBtn) cancelBtn.addEventListener('click', (e) => { e.preventDefault(); fechar(); });
    if (modal) modal.addEventListener('click', (e) => { if (e.target === modal) fechar(); });
});
</script>