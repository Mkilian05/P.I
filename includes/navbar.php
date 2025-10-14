<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-roxo" href="#">
      <img src="/P.I/assets/img/LOGO_PI.png" alt="Logo" width="40" class="me-2">
      Watt’s Up!
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav gap-3">

        <?php
        if ($page === 'index') {
          echo '<li class="nav-item"><a class="nav-link text-dark" href="#como-funciona">Como Funciona</a></li>';
          echo '<li class="nav-item"><a class="nav-link text-dark" href="#planos-recursos">Recursos</a></li>';
          echo '<li class="nav-item"><a class="nav-link text-dark" href="#beneficios">Benefícios</a></li>';
          echo '<li class="nav-item"><a class="nav-link text-dark" href="#depoimentos">Depoimentos</a></li>';
          echo '<li class="nav-item"><a class="nav-link btn btn-roxo px-3 py-1 rounded-3" href="#cadastro">Cadastre-se</a></li>';
        } elseif ($page === 'login') {
          echo '<li class="nav-item"><a class="nav-link btn btn-roxo px-3 py-1 rounded-3" href="../views/register.php">Cadastre-se</a></li>';
        } elseif ($page === 'register') {
          echo '<li class="nav-item"><a class="nav-link btn btn-roxo px-3 py-1 rounded-3" href="../views/login.php">Login</a></li>';
        } elseif ($page === 'dashboard_admin') { // <-- CONDIÇÃO ADICIONADA
          echo '<li class="nav-item"><a class="nav-link text-dark fw-bold" href="#">Usuários</a></li>';
          echo '<li class="nav-item"><a class="nav-link text-dark fw-bold" href="#">Casas</a></li>';
          echo '<li class="nav-item"><a class="nav-link btn btn-danger px-3 py-1 rounded-3 text-white" href="#">Sair</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>