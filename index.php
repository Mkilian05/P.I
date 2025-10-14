<?php

$page = 'index';
include_once "./includes/header.php";
include_once "./includes/navbar.php";
?>

<!-- Seção Hero -->
<header class="pt-5 mt-5 hero-section">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <h1 class="display-5 fw-bold">Monitore e reduza o consumo de energia da sua casa</h1>
                <p class="lead mt-3">Com o <strong>Watt’s Up!</strong>, você entende como cada aparelho consome energia,
                    recebe dicas personalizadas e economiza na conta de luz sem perder conforto.</p>
                <a href="#cadastro" class="btn btn-light btn-lg mt-3">Conheça o Watt’s Up!</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="/P.I/assets/img/Hero.png" alt="Casa inteligente" class="img-fluid hero-img">
            </div>
        </div>
    </div>
</header>

<!-- Como Funciona -->
<section id="como-funciona" class="py-5 bg-light fade-in">
    <div class="container text-center">
        <h2 class="fw-bold text-roxo mb-5">Como o Watt’s Up Funciona</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-plug fa-2x text-roxo"></i></div>
                <h5 class="fw-semibold">Conecte seus aparelhos</h5>
                <p class="text-secondary">Ligue seus dispositivos à plataforma e comece a monitorar o consumo de cada
                    um.</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-eye fa-2x text-roxo"></i></div>
                <h5 class="fw-semibold">Monitore em tempo real</h5>
                <p class="text-secondary">Acompanhe o consumo instantâneo de cada eletrodoméstico, onde quer que você
                    esteja.
                </p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-lightbulb fa-2x text-roxo"></i></div>
                <h5 class="fw-semibold">Receba dicas inteligentes</h5>
                <p class="text-secondary">Sugestões personalizadas ajudam você a economizar energia sem perder conforto.
                </p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-chart-line fa-2x text-roxo"></i></div>
                <h5 class="fw-semibold">Reduza sua conta</h5>
                <p class="text-secondary">Visualize relatórios detalhados e veja sua economia crescer mês a mês.</p>
            </div>
        </div>
    </div>
</section>

<!-- Planos e Recursos -->
<section id="planos-recursos" class="py-5 fade-in">
    <div class="container">
        <h2 class="text-center fw-bold text-roxo mb-5">Planos e Recursos do Watt’s Up</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-house fa-2x text-roxo"></i></div>
                    <h5 class="fw-semibold">Monitoramento por cômodo</h5>
                    <p class="text-secondary">Veja o consumo detalhado de cada ambiente da sua casa.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-lightbulb fa-2x text-roxo"></i></div>
                    <h5 class="fw-semibold">Dicas personalizadas</h5>
                    <p class="text-secondary">Receba recomendações de economia de acordo com seus hábitos de consumo.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-chart-pie fa-2x text-roxo"></i></div>
                    <h5 class="fw-semibold">Relatórios detalhados</h5>
                    <p class="text-secondary">Gráficos interativos mostram sua evolução na economia de energia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefícios -->
<section id="beneficios" class="py-5 fade-in">
    <div class="container">
        <h2 class="text-center fw-bold text-roxo mb-5">Por que usar o Watt’s Up?</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-bolt"></i></div>
                <h5 class="fw-semibold">Economize energia</h5>
                <p class="text-secondary">Reduza até 30% da sua conta com insights baseados no seu consumo real.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-chart-line"></i></div>
                <h5 class="fw-semibold">Acompanhe em tempo real</h5>
                <p class="text-secondary">Monitore o consumo dos seus eletrodomésticos a qualquer hora e lugar.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-icon mx-auto mb-3"><i class="fas fa-leaf"></i></div>
                <h5 class="fw-semibold">Mais sustentabilidade</h5>
                <p class="text-secondary">Contribua para um planeta mais verde reduzindo desperdícios energéticos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Depoimentos -->
<section id="depoimentos" class="py-5 bg-light fade-in">
    <div class="container">
        <h2 class="text-center fw-bold text-roxo mb-5">O que nossos usuários dizem</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <p>"O Watt’s Up mudou a forma como eu entendo o consumo da minha casa. Consegui reduzir 25% da minha
                        conta!"
                    </p>
                    <h6 class="mt-3 mb-0">Ana Silva</h6>
                    <small class="text-muted">São Paulo/SP</small>
                    <div class="text-warning mt-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <p>"É incrível ver quanto cada aparelho consome! Agora sei exatamente onde economizar."</p>
                    <h6 class="mt-3 mb-0">Carlos Mendes</h6>
                    <small class="text-muted">Campinas/SP</small>
                    <div class="text-warning mt-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="far fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 feature-card">
                    <p>"Interface simples e intuitiva. Em poucos dias, já notei diferença no consumo da casa."</p>
                    <h6 class="mt-3 mb-0">Juliana Rocha</h6>
                    <small class="text-muted">Leme/SP</small>
                    <div class="text-warning mt-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cadastro / CTA -->
<section id="cadastro" class="py-5 text-center text-white fade-in"
    style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
    <div class="container">
        <h2 class="fw-bold mb-3">Pronto para transformar sua casa em uma Smart Home?</h2>
        <p class="lead mb-4">Cadastre-se agora e conheça o futuro da eficiência energética!</p>
        <a href="/P.I/views/register.php" class="btn btn-light btn-lg fw-semibold px-4 hero-btn">Cadastre-se
            gratuitamente</a>
    </div>
</section>

<?php
include_once "./includes/footer.php";
?>