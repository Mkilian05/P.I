<?php 
  $page = 'casas';
  include_once __DIR__ . '/../includes/header.php'; // Seu header
  include_once __DIR__ . '/../includes/sidebar-nav.php'; // Seu menu lateral
?>

<main class="dashboard-content fade-in">
  
  <header class="top-header">
    <h1>Minhas Casas</h1>
    <p>Gerencie suas residências cadastradas.</p>
  </header>

  <section class="houses-grid">
      
      <div class="house-card">
          <div class="house-header">
              <h2>Casa Principal</h2>
              <span class="status online"><i class="fas fa-circle"></i> Online</span>
          </div>
          <p class="house-address"><i class="fas fa-map-marker-alt"></i> Rua Fictícia, 123 - Centro</p>
          <div class="house-details">
              <p><i class="fas fa-couch"></i> Ambientes: 5</p>
              <p><i class="fas fa-lightbulb"></i> Dispositivos: 12</p>
          </div>
          <a href="#" class="view-details-btn">Ver Detalhes <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="house-card">
          <div class="house-header">
              <h2>Casa de Praia</h2>
              <span class="status offline"><i class="fas fa-circle"></i> Offline</span>
          </div>
          <p class="house-address"><i class="fas fa-map-marker-alt"></i> Av. Beira Mar, 456 - Litoral</p>
          <div class="house-details">
              <p><i class="fas fa-couch"></i> Ambientes: 3</p>
              <p><i class="fas fa-lightbulb"></i> Dispositivos: 7</p>
          </div>
          <a href="#" class="view-details-btn">Ver Detalhes <i class="fas fa-arrow-right"></i></a>
      </div>

      <a href="adicionar_casa.php" class="add-house-card" id="addHouseCard">
          <div class="add-circle">
              <i class="fas fa-plus icon-plus"></i>
          </div>
          <p>Adicionar Nova Casa</p>
      </a>
  </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; // Seu rodapé ?>