<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Watt’s Up! - Controle seu Consumo de Energia</title>

  <!-- Favicon -->
  <link rel="icon" href="/P.I/assets/img/LOGO_PI.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS dinâmico -->
  <?php
    if ($page === 'index') {
      echo '<link rel="stylesheet" href="/P.I/assets/css/index.css">';
    }elseif ($page === 'register' or $page === 'login') {
      echo '<link rel="stylesheet" href="../assets/css/register.css">';
    }elseif ($page === 'dashboard_admin') {
    echo '<link rel="stylesheet" href="../../assets/css/dashboard_admin.css">';
}
    else{
      echo 'Erro carregar CSS';
    }
?>
</head>

<body>
