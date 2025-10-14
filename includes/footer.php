  <!-- Rodapé -->
  <footer class="text-center py-4 fade-in">
    <p class="mb-2">&copy; <?php echo date('Y'); ?> Watt’s Up! - Todos os direitos reservados.</p>
    <div class="d-flex justify-content-center gap-3 fs-5">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
  </footer>

  <!-- Scripts -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
 if ($page === 'index') {
  echo '<script src="./assets/scripts/index.js"></script>';
 } elseif ($page === 'register') {
  echo '<script src="/P.I/assets/scripts/register.js"></script>';
 } elseif ($page === 'login') {
  echo '<script src="/P.I/assets/scripts/login.js"></script>';
 } elseif ($page === 'dashboard_admin') {
  // Recomendo usar o caminho relativo que funcionou para o CSS
  echo '<script src="../../assets/scripts/dashboard_admin.js"></script>';
 } else {
  echo ''; // É melhor comentar o erro do que exibi-lo na tela
 }
?>

</body>

</html>