document.addEventListener("DOMContentLoaded", function() {
  // Gráfico de Linha
  const lineChart = new Chart(document.getElementById("line-chart"), {
    type: 'line',
    data: {
      labels: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio"],
      datasets: [{
        label: "Consumo (kWh)",
        data: [300, 280, 350, 320, 360],
        borderColor: "#d08dfd",
        backgroundColor: "rgba(208,141,253,0.2)",
        fill: true,
        tension: 0.4
      }]
    }
  });

  // Gráfico de Pizza
  const pieChart = new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
      labels: ["Cozinha", "Sala", "Quarto", "Banheiro"],
      datasets: [{
        label: "Consumo por Cômodo",
        data: [120, 50, 80, 30],
        backgroundColor: ["#d08dfd", "#6c757d", "#0d6efd", "#198754"]
      }]
    }
  });

  // Gráfico de Barras
  const barChart = new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["Geladeira", "TV", "Ar-condicionado", "Luz"],
      datasets: [{
        label: "Consumo por Dispositivo (kWh)",
        data: [120, 50, 80, 40],
        backgroundColor: "#d08dfd"
      }]
    }
  });
});