<?php 
  // 1. SEGURANÇA
  require_once __DIR__ . '/../includes/auth.php';

  $page = 'dashboard';
  include_once __DIR__ . '/../includes/header.php';
  include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<main class="dashboard-content fade-in">
  
  <section class="welcome-section">
    <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</h2>
    <p>Aqui está o resumo da sua residência inteligente.</p>
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

  </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>