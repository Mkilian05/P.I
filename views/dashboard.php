<?php 
  // 1. LÓGICA E SEGURANÇA
  require_once __DIR__ . '/../includes/auth.php';
  require_once __DIR__ . '/../config/database.php';

  $page = 'dashboard';
  $id_usuario = $_SESSION['id_usuario'];

  // --- CÁLCULOS DAS METAS E CONSUMO (Nova Lógica) ---
  
  $tarifa_kwh = 0.85; // Preço base

  // 1. Busca Meta
  $sql_meta = "SELECT valor_meta FROM metas_usuario WHERE fk_usuario_id_usuario = ?";
  $stmt = $conn->prepare($sql_meta);
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $meta_usuario = $stmt->get_result()->fetch_assoc()['valor_meta'] ?? 0;

  // 2. Consumo Mês Atual
  $mes_atual = date('m'); $ano_atual = date('Y');
  // Usando JOIN completo para segurança
  $sql_mes = "
      SELECT SUM(h.consumo) as total_wh
      FROM historico_consumo h
      JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
      JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
      JOIN casa c ON a.fk_casa_id_casa = c.id_casa
      WHERE c.fk_usuario_id_usuario = ? AND MONTH(h.data) = ? AND YEAR(h.data) = ?
  ";
  $stmt2 = $conn->prepare($sql_mes);
  $stmt2->bind_param("iii", $id_usuario, $mes_atual, $ano_atual);
  $stmt2->execute();
  $consumo_atual_wh = $stmt2->get_result()->fetch_assoc()['total_wh'] ?? 0;
  $gasto_atual_reais = ($consumo_atual_wh / 1000) * $tarifa_kwh;

  // 3. Consumo Mês Anterior
  $mes_ant = date('m', strtotime("-1 month")); $ano_ant = date('Y', strtotime("-1 month"));
  $stmt3 = $conn->prepare($sql_mes);
  $stmt3->bind_param("iii", $id_usuario, $mes_ant, $ano_ant);
  $stmt3->execute();
  $consumo_ant_wh = $stmt3->get_result()->fetch_assoc()['total_wh'] ?? 0;
  $gasto_ant_reais = ($consumo_ant_wh / 1000) * $tarifa_kwh;

  // 4. Lógica Visual da Barra
  $porc_meta = 0; $cor_barra = '#4CAF50';
  if ($meta_usuario > 0) {
      $porc_meta = ($gasto_atual_reais / $meta_usuario) * 100;
      if ($porc_meta > 100) $porc_meta = 100;
      if ($porc_meta > 50) $cor_barra = '#FFC107';
      if ($porc_meta > 85) $cor_barra = '#F44336';
  }

  // 5. Comparativo
  $diff_perc = 0; $icone_comp = 'fa-minus'; $cor_comp = '#666';
  if ($gasto_ant_reais > 0) {
      $diff = $gasto_atual_reais - $gasto_ant_reais;
      $diff_perc = ($diff / $gasto_ant_reais) * 100;
      if ($diff > 0) { $icone_comp = 'fa-arrow-up'; $cor_comp = '#F44336'; }
      elseif ($diff < 0) { $icone_comp = 'fa-arrow-down'; $cor_comp = '#4CAF50'; }
  }

  include_once __DIR__ . '/../includes/header.php';
  include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<style>
    .modal-backdrop { position:fixed; top:0; left:0; width:100%; height:100vh; background:rgba(0,0,0,0.6); display:flex; justify-content:center; align-items:center; z-index:9999; opacity:1; visibility:visible; transition:all 0.3s; }
    .modal-backdrop.hidden { opacity:0; visibility:hidden; pointer-events:none; }
    .modal-content { background:#fff !important; width:90%; max-width:400px; padding:2rem; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.3); text-align:left; }
    .form-group label { display:block; font-weight:600; margin-bottom:0.5rem; color:#444; }
    .form-group input { width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; box-sizing:border-box; }
</style>

<main class="dashboard-content fade-in">
  
  <section class="welcome-section">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
            <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h2>
            <p>Aqui está o resumo da sua residência inteligente.</p>
        </div>
        <button id="btnAbrirMeta" class="btn-outline" style="font-size:0.9rem;">
            <i class="fas fa-bullseye"></i> Definir Meta
        </button>
    </div>
  </section>

  <section class="cards-container" style="margin-top: 1.5rem;">
      
      <div class="card fade-in" style="flex:2; border-left: 5px solid <?php echo $cor_barra; ?>;">
          <h3 style="display:flex; justify-content:space-between; font-size:1.1rem;">
              Meta de Gastos
              <span style="font-size:0.9em; color:#666;">
                  R$ <?php echo number_format($gasto_atual_reais, 2, ',', '.'); ?> / 
                  R$ <?php echo number_format($meta_usuario, 2, ',', '.'); ?>
              </span>
          </h3>
          <?php if($meta_usuario > 0): ?>
              <div style="background:#eee; height:15px; width:100%; border-radius:10px; margin-top:10px; overflow:hidden;">
                  <div style="background:<?php echo $cor_barra; ?>; height:100%; width:<?php echo $porc_meta; ?>%;"></div>
              </div>
          <?php else: ?>
              <p style="color:#999; margin-top:5px; font-size:0.9rem;">Defina uma meta para acompanhar.</p>
          <?php endif; ?>
      </div>

      <div class="card fade-in" style="flex:1; text-align:center;">
          <h3 style="font-size:1rem; color:#666;">Mês Anterior</h3>
          <?php if($gasto_ant_reais > 0): ?>
              <div style="display:flex; align-items:center; justify-content:center; gap:10px; margin-top:5px;">
                  <i class="fas <?php echo $icone_comp; ?>" style="color:<?php echo $cor_comp; ?>; font-size:1.5rem;"></i>
                  <span style="font-weight:bold; font-size:1.2rem; color:<?php echo $cor_comp; ?>;">
                      <?php echo number_format(abs($diff_perc), 1); ?>%
                  </span>
              </div>
          <?php else: ?>
              <small style="color:#999;">Sem dados anteriores</small>
          <?php endif; ?>
      </div>

  </section>

  <h3 style="margin-top: 2rem; color: var(--roxo); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid #eee; padding-bottom: 10px;">
      <i class="fas fa-cogs"></i> Gerenciamento de Estrutura
  </h3>
  
  <section class="cards-container">
    <div class="card fade-in">
      <h3>Casas</h3>
      <p>Gerencie suas residências.</p>
      <a href="casas.php" class="btn-primary" style="text-decoration: none; display: inline-block;">Gerenciar</a>
    </div>

    <div class="card fade-in">
      <h3>Ambientes</h3>
      <p>Organize seus cômodos.</p>
      <a href="ambientes.php" class="btn-primary" style="text-decoration: none; display: inline-block;">Gerenciar</a>
    </div>

    <div class="card fade-in">
      <h3>Dispositivos</h3>
      <p>Controle seus aparelhos.</p>
      <a href="dispositivos.php" class="btn-primary" style="text-decoration: none; display: inline-block;">Gerenciar</a>
    </div>
  </section>

  <h3 style="margin-top: 3rem; color: var(--roxo); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid #eee; padding-bottom: 10px;">
      <i class="fas fa-bolt"></i> Controle de Energia
  </h3>

  <section class="cards-container">
    
    <div class="card fade-in" style="border-left: 5px solid #ff9800;"> 
      <div style="margin-bottom: 10px;">
        <i class="fas fa-stopwatch" style="font-size: 2.5rem; color: #ff9800;"></i>
      </div>
      <h3>Registrar Uso</h3>
      <p>Informe o tempo de uso diário dos seus aparelhos.</p>
      <a href="lancar_consumo.php" class="btn-primary" style="text-decoration: none; display: inline-block; background-color: #ff9800;">
        Lançar Horas
      </a>
    </div>

    <div class="card fade-in" style="border-left: 5px solid var(--roxo);">
      <div style="margin-bottom: 10px;">
        <i class="fas fa-chart-bar" style="font-size: 2.5rem; color: var(--roxo);"></i>
      </div>
      <h3>Ranking de Consumo</h3>
      <p>Veja gráficos dos dispositivos que mais gastam.</p>
      <a href="relatorio_consumo.php" class="btn-primary" style="text-decoration: none; display: inline-block;">
        Ver Gráfico
      </a>
    </div>

    <div class="card fade-in" style="border-left: 5px solid #4CAF50;">
        <div style="margin-bottom: 10px;">
            <i class="fas fa-file-invoice-dollar" style="font-size: 2.5rem; color: #4CAF50;"></i>
        </div>
        <h3>Simulador de Conta</h3>
        <p>Calcule o valor estimado da sua fatura mensal.</p>
        <a href="calculo_mensal.php" class="btn-primary" style="text-decoration: none; display: inline-block; background-color: #4CAF50;">
            Simular
        </a>
    </div>

    <div class="card fade-in" style="border-left: 5px solid #00BCD4;">
        <div style="margin-bottom: 10px;">
            <i class="fas fa-file-pdf" style="font-size: 2.5rem; color: #00BCD4;"></i>
        </div>
        <h3>Histórico & PDF</h3>
        <p>Relatório detalhado por período para impressão.</p>
        <a href="relatorio_historico.php" class="btn-primary" style="text-decoration: none; display: inline-block; background-color: #00BCD4;">
            Gerar Relatório
        </a>
    </div>

    <div class="card fade-in" style="border-left: 5px solid #e91e63;"> <div style="margin-bottom: 10px;">
            <i class="fas fa-chart-pie" style="font-size: 2.5rem; color: #e91e63;"></i>
        </div>
        <h3>Consumo por Ambiente</h3>
        <p>Descubra qual cômodo gasta mais energia.</p>
        <a href="relatorio_ambientes.php" class="btn-primary" style="text-decoration: none; display: inline-block; background-color: #e91e63;">
            Ver Gráfico
        </a>
    </div>

    <div class="card fade-in" style="border-left: 5px solid #673AB7;"> <div style="margin-bottom: 10px;">
            <i class="fas fa-globe-americas" style="font-size: 2.5rem; color: #673AB7;"></i>
        </div>
        <h3>Estatísticas Globais</h3>
        <p>Veja o consumo geral de todas as categorias.</p>
        <a href="relatorio_global.php" class="btn-primary" style="text-decoration: none; display: inline-block; background-color: #673AB7;">
            Ver Panorama
        </a>
    </div>

  </section>

</main>

<div class="modal-backdrop hidden" id="modalMeta">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; margin-bottom:1rem; border-bottom:1px solid #eee; padding-bottom:0.5rem;">
            <h2 style="margin:0; color:#7a3ef2;">Definir Meta</h2>
            <button id="btnFecharMeta" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#999;">&times;</button>
        </div>
        <form method="POST" action="../config/processa_meta.php">
            <div class="form-group">
                <label>Valor Máximo Mensal (R$)</label>
                <input type="number" step="0.01" name="valor_meta" placeholder="Ex: 200.00" value="<?php echo $meta_usuario > 0 ? $meta_usuario : ''; ?>" required>
            </div>
            <div style="text-align:right; margin-top:1.5rem;">
                <button type="submit" class="btn-primary">Salvar Meta</button>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalMeta');
    const btnAbrir = document.getElementById('btnAbrirMeta');
    const btnFechar = document.getElementById('btnFecharMeta');

    const toggleModal = () => modal.classList.toggle('hidden');

    if(btnAbrir) btnAbrir.addEventListener('click', toggleModal);
    if(btnFechar) btnFechar.addEventListener('click', toggleModal);
    if(modal) modal.addEventListener('click', (e) => { if(e.target===modal) toggleModal(); });
});
</script>