// Começo da criação do gráfico
const ctx = document.getElementById("line-chart").getContext("2d");

new Chart(ctx, {
  type: "line",
  data: {
    labels: ["Jan", "Fev", "Mar", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
    datasets: [
      {
        label: "Consumo em W(h)",
        data: [5, 10, 15, 2, 18, 25, 5, 0, 3, 8, 25],
        borderWidth: 2,
        borderColor: 'rgba(255, 25, 253, 0.85)',
        backgroundColor: 'transparent',
        tension: 0.3
      },
      {
        label: "Consumo em kW(h)",
        data: [0.5, 1, 1.5, 0.2, 1.8, 2.5, 0.5, 0, 0.3, 0.8, 2.5],
        borderWidth: 2,
        borderColor: 'rgba(25, 255, 166, 0.85)',
        backgroundColor: 'transparent',
        tension: 0.3
      }
    ]
  },
  options: {
    plugins: {
      title: {
        display: true,
        text: "Relatório",
        font: {
          size: 20
        }
      }
    }
  }
});
// Fim criação de gráfico