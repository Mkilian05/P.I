<?php

$page = 'login';
include_once '../includes/header.php';
include_once '../includes/navbar.php';
?>

<!-- Login -->
<section class="py-5 mt-5 cadastro-section">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card cadastro-card p-4 shadow-lg rounded-4">
            <div class="text-center mb-4">
                <img src="/P.I/assets/img/LOGO_PI.png" alt="Logo" width="70" class="mb-3">
                <h2 class="fw-bold text-roxo"><i class="fa-solid fa-right-to-bracket me-2"></i>Login</h2>
                <p class="text-secondary">Entre na sua conta Watt’s Up e comece a monitorar seu consumo de energia!</p>
            </div>

            <form id="formLogin">
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa-solid fa-envelope me-1"></i> E-mail</label>
                    <input type="email" class="form-control" id="email" placeholder="seuemail@email.com" required>
                </div>

                <div class="mb-4">
                    <label for="senha" class="form-label"><i class="fa-solid fa-lock me-1"></i> Senha</label>
                    <input type="password" class="form-control" id="senha" placeholder="Digite sua senha" required>
                </div>

                <button type="submit" class="btn btn-roxo w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
                </button>

                <p class="text-center text-secondary mt-3 mb-0">
                    Não tem uma conta? <a href="../views/register.php"
                        class="text-roxo fw-semibold text-decoration-none">Cadastre-se</a>
                </p>
                <p class="text-center text-secondary mt-1 mb-0">
                    <a href="#" class="text-roxo fw-semibold text-decoration-none">Esqueci minha
                        senha</a>
                </p>
            </form>
        </div>
    </div>
</section>

<?php
include_once '../includes/footer.php';
?>