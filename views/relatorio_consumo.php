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
                <h1>Ranking de Potência</h1>
                <p>Top 5 dispositivos com maior carga instalada (Watts).</p>
            </div>
        </div>
    </header>

    <section class="card" style="max-width: 900px; margin: 2rem auto; padding: 2rem;">
        
        <div style="position: relative; height: 400px; width: 100%;">
            <canvas id="graficoConsumo"></canvas>
        </div>

    </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("Iniciando gráfico...");

    const ctx = document.getElementById('graficoConsumo');

    if (ctx) {
        // Busca os dados no arquivo PHP
        // O caminho '../config/dados_grafico.php' sobe um nível (sai de views) e entra em config
        fetch('../config/dados_grafico.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro de rede: ' + response.status);
                }
                return response.json();
            })
            .then(dados => {
                console.log("Dados recebidos:", dados); // Veja isso no F12 se der erro

                if(dados.error) {
                    alert("Erro no sistema: " + dados.error);
                    return;
                }

                // Se não houver dados (tudo zero ou vazio)
                if(!dados.data || dados.data.length === 0) {
                    alert("Não há dispositivos cadastrados para gerar o gráfico!");
                    return;
                }

                // Cria o Gráfico
                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: dados.labels, 
                        datasets: [{
                            label: 'Potência Total (Watts)',
                            data: dados.data, 
                            backgroundColor: [
                                'rgba(122, 62, 242, 0.7)', // Roxo
                                'rgba(54, 162, 235, 0.7)', // Azul
                                'rgba(255, 206, 86, 0.7)', // Amarelo
                                'rgba(75, 192, 192, 0.7)', // Verde
                                'rgba(255, 99, 132, 0.7)', // Vermelho
                            ],
                            borderColor: [
                                'rgba(122, 62, 242, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                            ],
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Potência (Watts)'
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Erro:', error);
                alert("Erro ao carregar o gráfico. Verifique se o arquivo '../config/dados_grafico.php' existe.");
            });
    }
});
</script>