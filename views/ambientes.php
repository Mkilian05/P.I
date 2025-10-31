<?php 
  $page = 'ambientes'; // <-- Importante: Mudar o nome da página
  include_once __DIR__ . '/../includes/header.php'; 
  include_once __DIR__ . '/../includes/sidebar-nav.php'; 
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <h1>Meus Ambientes</h1>
    <p>Organize os cômodos das suas casas.</p>
  </header>

  <section class="ambientes-grid">
      
      <div class="ambiente-card">
          <div class="ambiente-header">
              <h2>Sala de Estar</h2>
              <i class="fas fa-couch ambiente-icon"></i> 
          </div>
          <p class="ambiente-location"><i class="fas fa-home"></i> Casa Principal</p>
          
          <div class="ambiente-details">
              <p><i class="fas fa-lightbulb"></i> Dispositivos: 8</p> 
          </div>
          <a href="#" class="view-details-btn">Gerenciar Dispositivos <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="ambiente-card">
          <div class="ambiente-header">
              <h2>Quarto Principal</h2>
              <i class="fas fa-bed ambiente-icon"></i>
          </div>
          <p class="ambiente-location"><i class="fas fa-home"></i> Casa Principal</p>
          <div class="ambiente-details">
              <p><i class="fas fa-lightbulb"></i> Dispositivos: 4</p>
          </div>
          <a href="#" class="view-details-btn">Gerenciar Dispositivos <i class="fas fa-arrow-right"></i></a>
      </div>
      
      <div class="ambiente-card">
          <div class="ambiente-header">
              <h2>Cozinha</h2>
              <i class="fas fa-utensils ambiente-icon"></i>
          </div>
          <p class="ambiente-location"><i class="fas fa-home"></i> Casa de Praia</p>
          <div class="ambiente-details">
              <p><i class="fas fa-lightbulb"></i> Dispositivos: 2</p>
          </div>
          <a href="#" class="view-details-btn">Gerenciar Dispositivos <i class="fas fa-arrow-right"></i></a>
      </div>

      <a href="adicionar_ambiente.php" class="add-ambiente-card" id="addAmbienteCard">
          <div class="add-circle">
              <i class="fas fa-plus icon-plus"></i>
          </div>
          <p>Adicionar Novo Ambiente</p>
      </a>
  </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>