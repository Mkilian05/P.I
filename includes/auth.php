<?php
// includes/auth.php

// 1. Inicia a sessão se ela ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Verifica se o usuário NÃO está logado (não tem o ID na sessão)
if (!isset($_SESSION['id_usuario'])) {
    
    // Opcional: Destrói qualquer dado residual
    session_destroy();
    
    // 3. Manda o intruso de volta para o login (ajuste o caminho se necessário)
    // O caminho '../index.php' assume que quem chamar esse arquivo está na pasta 'views'
    header("Location: ../index.php?erro=sem_acesso");
    exit; // OBRIGATÓRIO: Impede que o restante da página carregue
}
