<?php 
  $page = 'dispositivos'; 
  require_once __DIR__ . '/../includes/auth.php';
  require_once __DIR__ . '/../config/database.php';
  
  // SEGURANÇA: ID do usuário
  $id_usuario = $_SESSION['id_usuario'] ?? 1;

  $erros = $_SESSION['erros'] ?? [];
  $sucesso = $_SESSION['sucesso'] ?? null;
  unset($_SESSION['erros'], $_SESSION['sucesso']);

  // 1. SELECT DISPOSITIVOS (Filtrado por Usuário)
  $sql_dispositivos = "
      SELECT 
          d.id_dispositivo,
          d.nome_dispositivo, 
          c.nome_categoria,
          a.nome_ambiente,
          k.nome_casa,
          ad.quantidade
      FROM 
          ambiente_dispositivo AS ad
      JOIN 
          dispositivos AS d ON ad.fk_disp_id_disp = d.id_dispositivo
      JOIN 
          ambiente AS a ON ad.fk_ambi_id_ambi = a.id_ambiente
      JOIN 
          categoria_dispositivos AS c ON d.fk_cat_disp_id_cat = c.id_categoria
      JOIN 
          casa AS k ON a.fk_casa_id_casa = k.id_casa
      WHERE 
          k.fk_usuario_id_usuario = ? 
          AND (d.is_deleted IS NULL OR d.is_deleted = 0)
      ORDER BY 
          k.nome_casa, a.nome_ambiente, d.nome_dispositivo
  ";
  
  $stmt = $conn->prepare($sql_dispositivos);
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $lista_dispositivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  
  // 2. LISTAS PARA O MODAL (Ambientes do Usuário e Categorias)
  // Filtra ambientes onde a casa pertence ao usuário
  $sql_ambientes = "
      SELECT a.id_ambiente, a.nome_ambiente, k.nome_casa 
      FROM ambiente a 
      JOIN casa k ON a.fk_casa_id_casa = k.id_casa 
      WHERE k.fk_usuario_id_usuario = ? 
      AND (a.is_deleted IS NULL OR a.is_deleted = 0)
  ";
  $stmt_amb = $conn->prepare($sql_ambientes);
  $stmt_amb->bind_param("i", $id_usuario);
  $stmt_amb->execute();
  $ambientes = $stmt_amb->get_result()->fetch_all(MYSQLI_ASSOC);

  // Categorias são públicas, não precisa de filtro
  $categorias = $conn->query("SELECT * FROM categoria_dispositivos")->fetch_all(MYSQLI_ASSOC);

  function getIconeCategoria($nome_categoria) {
      switch (strtolower($nome_categoria)) {
          case 'lâmpada': return 'fas fa-lightbulb';
          case 'tv': return 'fas fa-tv';
          case 'ar condicionado': return 'fas fa-wind';
          default: return 'fas fa-plug';
      }
  }

  include_once __DIR__ . '/../includes/header.php'; 
  include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <h1>Meus Dispositivos</h1>
    <p>Cadastre, monitore e controle seus dispositivos.</p>
  </header>

  <?php if (!empty($erros)): ?>
      <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
  <?php endif; ?>
  
  <?php if ($sucesso): ?>
      <div class="alerta sucesso"><p><?php echo htmlspecialchars($sucesso); ?></p></div>
  <?php endif; ?>

  <section class="dispositivos-grid">
      
      <?php if(empty($lista_dispositivos)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: #666; margin-top: 2rem;">Nenhum dispositivo encontrado.</p>
      <?php endif; ?>

      <?php foreach ($lista_dispositivos as $disp): ?>
      <div class="dispositivo-card">
          <div class="dispositivo-header">
              <h2><?php echo htmlspecialchars($disp['nome_dispositivo']); ?></h2>
              <span class="status online" style="font-size: 0.8em; padding: 2px 8px;">x<?php echo $disp['quantidade']; ?></span>
              <i class="<?php echo getIconeCategoria($disp['nome_categoria']); ?> dispositivo-icon"></i>
          </div>
          <p class="dispositivo-location">
              <i class="fas fa-map-marker-alt"></i> 
              <?php echo htmlspecialchars($disp['nome_ambiente']); ?> 
              <small>(<?php echo htmlspecialchars($disp['nome_casa']); ?>)</small>
          </p>
          
          <a href="gerencia_dispositivos.php?id=<?php echo $disp['id_dispositivo']; ?>" class="btn-editar-card">
              Configurar
          </a>
      </div>
      <?php endforeach; ?>
      
      <div class="add-dispositivo-card" id="addDispositivoCard">
          <div class="add-circle">
              <i class="fas fa-plus icon-plus"></i>
          </div>
          <p>Adicionar Novo Dispositivo</p>
      </div>
  </section>

</main>

<div class="modal-backdrop hidden" id="modalDispositivo">
    <div class="modal-content">
        <header class="modal-header">
            <h2>Adicionar Novo Dispositivo</h2>
            <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        </header>

        <form class="modal-body" id="formDispositivo" method="POST" action="../config/processa_dispositivos.php">
            <input type="hidden" name="acao" value="cadastrar">
            
            <div class="form-group">
                <label for="dispositivoNome">Nome do Dispositivo</label>
                <input type="text" id="dispositivoNome" name="dispositivoNome" placeholder="Ex: Lâmpada LED" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dispositivoPotencia">Potência (W)</label>
                    <input type="number" step="0.1" id="dispositivoPotencia" name="dispositivoPotencia" placeholder="Ex: 9" required>
                </div>
                <div class="form-group">
                    <label for="dispositivoQuantidade">Qtd</label>
                    <input type="number" id="dispositivoQuantidade" name="dispositivoQuantidade" value="1" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label for="dispositivoAmbiente">Ambiente</label>
                <select id="dispositivoAmbiente" name="dispositivoAmbiente" required>
                    <option value="" disabled selected>Selecione...</option>
                    <?php foreach ($ambientes as $amb): ?>
                        <option value="<?php echo $amb['id_ambiente']; ?>">
                            <?php echo htmlspecialchars($amb['nome_ambiente']); ?> (<?php echo htmlspecialchars($amb['nome_casa']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="dispositivoTipo">Categoria</label>
                <select id="dispositivoTipo" name="dispositivoTipo" required>
                    <option value="" disabled selected>Selecione...</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id_categoria']; ?>">
                            <?php echo htmlspecialchars($cat['nome_categoria']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <footer class="modal-footer">
            <button class="btn-outline" id="modalCancelBtn">Cancelar</button>
            <button type="submit" form="formDispositivo" class="btn-primary">Salvar</button>
        </footer>
    </div>
</div>

<?php 
  include_once __DIR__ . '/../includes/footer.php'; 
  $conn->close();
?>