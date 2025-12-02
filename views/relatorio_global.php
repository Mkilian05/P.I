<?php
require_once __DIR__ . '/../includes/auth.php';
$page = 'dashboard'; 
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<main class="dashboard-content fade-in">
    
    <header class="top-header">
        <div style="display:flex; align-items:center; gap: 15px;">
            <a href="dashboard.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div>
                <h1>Panorama Global de Consumo</h1>
                <p>Categorias que mais consomem energia (Todos os Usuários).</p>
            </div>
        </div>
    </header>

    <section class="card" style="max-width: 900px; margin: 2rem auto; padding: 2rem;">
        <div style="position: relative; height: 450px; width: 100%;">
            <canvas id="graficoGlobal"></canvas>
        </div>
    </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoGlobal');

    if (ctx) {
        fetch('../config/dados_categorias_global.php')
            .then(response => response.json())
            .then(dados => {
                
                if(dados.error) {
                    alert("Erro: " + dados.error);
                    return;
                }

                if(!dados.data || dados.data.length === 0) {
                    ctx.parentElement.innerHTML = "<div style='text-align:center; padding: 50px; color:#666;'><h3>Sem dados!</h3><p>O sistema ainda não possui registros de consumo.</p></div>";
                    return;
                }

                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: dados.labels,
                        datasets: [{
                            label: 'Consumo Global (kWh)',
                            data: dados.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 5,
                            minBarLength: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' kWh';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Consumo (kWh)' }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Erro:', error));
    }
});
</script>