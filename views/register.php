<?php

$page = 'register';
include_once '../includes/header.php';
include_once '../includes/navbar.php';
?>

<!-- Cadastro -->
<section class="py-5 mt-5 cadastro-section">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card cadastro-card p-4 shadow-lg rounded-4">
            <div class="text-center mb-4">
                <img src="/P.I/assets/img/LOGO_PI.png" alt="Logo" width="70" class="mb-3">
                <h2 class="fw-bold text-roxo"><i class="fa-solid fa-user-plus me-2"></i>Crie sua conta</h2>
                <p class="text-secondary">Cadastre-se e comece a monitorar seu consumo de energia com o Watt’s Up!</p>
            </div>

            <form id="formCadastro">
                <div class="mb-3">
                    <label for="nome" class="form-label"><i class="fa-solid fa-user me-1"></i> Nome completo</label>
                    <input type="text" class="form-control" id="nome" placeholder="Ex: Ana Silva" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa-solid fa-envelope me-1"></i> E-mail</label>
                    <input type="email" class="form-control" id="email" placeholder="seuemail@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label"><i class="fa-solid fa-lock me-1"></i> Senha</label>
                    <input type="password" class="form-control" id="senha" placeholder="Crie uma senha forte" required>
                </div>

                <div class="mb-4">
                    <label for="confirmarSenha" class="form-label"><i class="fa-solid fa-lock me-1"></i> Confirmar
                        senha</label>
                    <input type="password" class="form-control" id="confirmarSenha" placeholder="Repita sua senha"
                        required>
                </div>

                <button type="submit" class="btn btn-roxo w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-paper-plane me-2"></i> Cadastrar
                </button>

                <p class="text-center text-secondary mt-3 mb-0">
                    Já tem uma conta? <a href="../views/login.php"
                        class="text-roxo fw-semibold text-decoration-none">Entrar</a>
                </p>
            </form>
        </div>
    </div>
</section>

<?php
include_once '../includes/footer.php';
?>