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
                <h1>Consumo por Ambiente</h1>
                <p>Veja a proporção de gastos em cada cômodo das suas casas.</p>
            </div>
        </div>
    </header>

    <section class="card" style="max-width: 800px; margin: 2rem auto; padding: 2rem;">
        
        <div style="position: relative; height: 450px; width: 100%;">
            <canvas id="graficoAmbientes"></canvas>
        </div>

    </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoAmbientes');

    if (ctx) {
        // Busca os dados no PHP
        fetch('../config/dados_ambientes.php')
            .then(response => response.json())
            .then(dados => {
                
                if(dados.error) {
                    console.error("Erro:", dados.error);
                    alert("Erro ao carregar dados: " + dados.error);
                    return;
                }

                // Se não tiver dados, mostra aviso
                if(!dados.data || dados.data.length === 0) {
                    ctx.parentElement.innerHTML = "<div style='text-align:center; padding: 50px; color:#666;'><h3>Sem dados!</h3><p>Registre o consumo para ver a distribuição por ambientes.</p></div>";
                    return;
                }

                // Cria o Gráfico de Rosca (Doughnut)
                new Chart(ctx, {
                    type: 'doughnut', 
                    data: {
                        labels: dados.labels,
                        datasets: [{
                            label: 'Consumo (kWh)',
                            data: dados.data,
                            backgroundColor: [
                                '#7a3ef2', // Roxo Principal
                                '#ff9800', // Laranja
                                '#00bcd4', // Ciano
                                '#e91e63', // Rosa
                                '#4caf50', // Verde
                                '#ffc107', // Amarelo
                                '#9c27b0', // Roxo Escuro
                                '#607d8b'  // Cinza
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right', // Legenda na direita
                                labels: { font: { size: 12 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        // Calcula porcentagem automática
                                        let total = context.chart._metasets[context.datasetIndex].total;
                                        let percentage = ((value / total) * 100).toFixed(1) + "%";
                                        return label + ": " + value + " kWh (" + percentage + ")";
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Erro:', error));
    }
});
</script>