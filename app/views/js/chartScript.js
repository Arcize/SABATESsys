// Variable para almacenar los datos previos
let previousChartData = null;

// Función para obtener datos y actualizar la gráfica
function fetchDataAndUpdateChart() {
  fetch('index.php?view=chartData')
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor");
      }
      return response.json();
    })
    .then((chartData) => {

      // Comparar los datos nuevos con los anteriores
      if (JSON.stringify(chartData) !== JSON.stringify(previousChartData)) {
        updateChart(chartData);
        previousChartData = chartData; // Actualizar los datos previos
      } else {
        console.log("Los datos son idénticos, no se actualiza la gráfica.");
      }
    })
    .catch((error) => {
      console.error("Error en la solicitud fetch:", error);
    });
}

// Crear la gráfica inicial
const ctx = document.getElementById("pieChart").getContext("2d");
const miGrafica = new Chart(ctx, {
  type: "pie",
  data: {
    labels: [], // Inicialmente vacío
    datasets: [
      {
        data: [], // Inicialmente vacío
        backgroundColor: ["#211C84", "#7A73D1"], // Colores
      },
    ],
  },
  options: {
    animation: {
      animateScale: false,
      x: {
        duration: 0, // Desactiva animación en el eje X
      },
      y: {
        duration: 0, // Desactiva animación en el eje Y
      },
    },
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: "right",
      },
    },
  },
});

// Función para actualizar la gráfica con nuevos datos
function updateChart(chartData) {
  miGrafica.data.labels = chartData.map((row) => row.estado); // Actualizar etiquetas
  miGrafica.data.datasets[0].data = chartData.map((row) => row.total); // Actualizar datos
  miGrafica.update(); // Refrescar la gráfica
}

// Llama a la función inmediatamente para cargar los datos iniciales
fetchDataAndUpdateChart();


setInterval(fetchDataAndUpdateChart, 5000);
