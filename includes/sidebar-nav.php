<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="../assets/img/LOGO_PI.png" alt="Logo Watt’s Up">
  </div>
  <nav class="sidebar-menu">
    <ul>
      <li><a href="../views/dashboard.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i class="fa-solid fa-chart-simple me-2"></i>Dashboard</a></li>
      <li><a href="../views/casas.php" class="<?= $page === 'casas' ? 'active' : '' ?>"><i class="fa-solid fa-house me-2"></i>Casas</a></li>
      <li><a href="../views/ambientes.php" class="<?= $page === 'ambientes' ? 'active' : '' ?>"><i class="fa-solid fa-couch me-2"></i>Ambientes</a></li>
      <li><a href="../views/dispositivos.php" class="<?= $page === 'dispositivos' ? 'active' : '' ?>"><i class="fa-solid fa-plug me-2"></i>Dispositivos</a></li>
      <li><a href="../config/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Sair</a></li>
    </ul>
  </nav>
</aside>