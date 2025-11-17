<?php 
  $page = 'dispositivos'; 
  
  session_start();
  require_once __DIR__ . '/../config/database.php'; // Seu arquivo de conexão $conn
  
  $erros = $_SESSION['erros'] ?? [];
  $sucesso = $_SESSION['sucesso'] ?? null;
  unset($_SESSION['erros'], $_SESSION['sucesso']);

  // 3. LÓGICA DE EXIBIÇÃO (SELECTS com MySQLi)
  
  // 1. Pegar lista de DISPOSITIVOS CADASTRADOS (CORRIGIDO)
  // --- CONSULTA FINAL CORRIGIDA 100% BASEADA NAS SUAS CAPTURAS DE TELA ---
  $sql_dispositivos = "
      SELECT 
          d.nome_dispositivo, 
          c.nome_categoria,
          a.nome_ambiente,
          k.nome_casa,
          ad.quantidade
      FROM 
          ambiente_dispositivo AS ad
      JOIN 
          dispositivos AS d ON ad.fk_id_dispositivo = d.id_dispositivo  --
      JOIN 
          ambiente AS a ON ad.fk_id_ambiente = a.id_ambiente          --
      JOIN 
          categoria_dispositivos AS c ON d.fk_id_categoria = c.id_categoria --
      JOIN 
          casa AS k ON a.fk_id_casa = k.id_casa                        --
  ";
  
  // Esta é a linha que estava dando o erro
  $result_dispositivos = $conn->query($sql_dispositivos);
  if(!$result_dispositivos) {
      die("Erro na consulta SQL: " . $conn->error . " <br><br>Query:<br>" . nl2br(htmlspecialchars($sql_dispositivos)));
  }
  $lista_dispositivos = $result_dispositivos->fetch_all(MYSQLI_ASSOC);
  
  // 2. Pegar lista de AMBIENTES (para o dropdown)
  // --- CORRIGIDO ---
  $sql_ambientes = "
      SELECT a.id_ambiente, a.nome_ambiente, k.nome_casa 
      FROM ambiente AS a 
      JOIN casa AS k ON a.fk_id_casa = k.id_casa --
  ";
  $result_ambientes = $conn->query($sql_ambientes);
  $ambientes = $result_ambientes->fetch_all(MYSQLI_ASSOC);

  // 3. Pegar lista de CATEGORIAS (para o dropdown)
  $sql_categorias = "SELECT * FROM categoria_dispositivos";
  $result_categorias = $conn->query($sql_categorias);
  $categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

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
      <div class="alerta erro">
          <?php foreach ($erros as $erro): ?>
              <p><?php echo htmlspecialchars($erro); ?></p>
          <?php endforeach; ?>
      </div>
  <?php endif; ?>
  
  <?php if ($sucesso): ?>
      <div class="alerta sucesso">
          <p><?php echo htmlspecialchars($sucesso); ?></p>
      </div>
  <?php endif; ?>

  <section class="dispositivos-grid">
      
      <?php foreach ($lista_dispositivos as $dispositivo): ?>
      <div class="dispositivo-card">
          <div class="dispositivo-header">
              <h2><?php echo htmlspecialchars($dispositivo['nome_dispositivo']); ?></h2>
              <span class="dispositivo-qtd">x<?php echo htmlspecialchars($dispositivo['quantidade']); ?></span>
              <i class="<?php echo getIconeCategoria($dispositivo['nome_categoria']); ?> dispositivo-icon"></i>
          </div>
          <p class="dispositivo-location">
              <i class="fas fa-map-marker-alt"></i> 
              <?php echo htmlspecialchars($dispositivo['nome_ambiente']); ?> 
              (<?php echo htmlspecialchars($dispositivo['nome_casa']); ?>)
          </p>
          <a href="#" class="view-details-btn">Configurar <i class="fas fa-cog"></i></a>
      </div>
      <?php endforeach; ?>
      
      <a href="#" class="add-dispositivo-card" id="addDispositivoCard">
          <div class="add-circle">
              <i class="fas fa-plus icon-plus"></i>
          </div>
          <p>Adicionar Novo Dispositivo</p>
      </a>
  </section>

</main>

<div class="modal-backdrop hidden" id="modalDispositivo">
    <div class="modal-content">
        
        <header class="modal-header">
            <h2>Adicionar Novo Dispositivo</h2>
            <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        </header>

        <form class="modal-body" id="formDispositivo" method="POST" action="../config/processa_dispositivos.php">
            
            <div class="form-group">
                <label for="dispositivoNome">Nome do Dispositivo</label>
                <input type="text" id="dispositivoNome" name="dispositivoNome" placeholder="Ex: Lâmpada LED 9W" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dispositivoPotencia">Potência (em Watts)</label>
                    <input type="number" step="0.1" id="dispositivoPotencia" name="dispositivoPotencia" placeholder="Ex: 9" required>
                </div>
                
                <div class="form-group">
                    <label for="dispositivoQuantidade">Quantidade</label>
                    <input type="number" id="dispositivoQuantidade" name="dispositivoQuantidade" value="1" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label for="dispositivoAmbiente">Ambiente</label>
                <select id="dispositivoAmbiente" name="dispositivoAmbiente" required>
                    <option value="" disabled selected>Selecione um ambiente...</option>
                    <?php foreach ($ambientes as $ambiente): ?>
                        <option value="<?php echo $ambiente['id_ambiente']; ?>">
                            <?php echo htmlspecialchars($ambiente['nome_ambiente']); ?> 
                            (<?php echo htmlspecialchars($ambiente['nome_casa']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="dispositivoTipo">Tipo de Dispositivo</label>
                <select id="dispositivoTipo" name="dispositivoTipo" required>
                    <option value="" disabled selected>Selecione o tipo...</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>">
                            <?php echo htmlspecialchars($categoria['nome_categoria']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </form>

        <footer class="modal-footer">
            <button class="btn-outline" id="modalCancelBtn">Cancelar</button>
            <button type="submit" form="formDispositivo" class="btn-primary">Salvar Dispositivo</button>
        </footer>

    </div>
</div>

<?php 
  include_once __DIR__ . '/../includes/footer.php'; 
  $conn->close();
?>
<script src="../assets/js/dispositivos.js"></script>