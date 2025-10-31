<?php 
  $page = 'dashboard';
  include_once __DIR__ . '/../includes/header.php';
  include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<!-- Conteúdo principal -->
<main class="dashboard-content fade-in">
  <section class="welcome-section">
    <h2>Bem-vindo(a) ao seu painel</h2>
    <p>Gerencie suas casas, ambientes e dispositivos de forma prática e rápida.</p>
  </section>

  <section class="cards-container">
    <div class="card fade-in">
      <h3>Casas</h3>
      <p>Gerencie suas residências cadastradas.</p>
      <button class="btn-primary" id="btnCasas">Gerenciar</button>
    </div>

    <div class="card fade-in">
      <h3>Ambientes</h3>
      <p>Organize os cômodos das suas casas.</p>
      <button class="btn-primary" id="btnAmbientes">Gerenciar</button>
    </div>

    <div class="card fade-in">
      <h3>Dispositivos</h3>
      <p>Cadastre e monitore seus dispositivos.</p>
      <button class="btn-primary" id="btnDispositivos">Gerenciar</button>
    </div>
  </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

