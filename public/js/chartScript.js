// Variable para almacenar los datos previos
let previousChartData = null;

// Función para obtener datos y actualizar la gráfica
function fetchDataAndUpdateChart() {
  fetch("index.php?view=chartData")
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

// Configuración de colores y opciones comunes
const chartColors = ["#211C84", "#7A73D1"];
const chartTitle = {
  display: true,
  text: "Empleados",
  font: {
    size: 18,
  },
  color: "#0f2c64",
  padding: {
    bottom: 20,
  },
};
const chartLegend = {
  position: "bottom",
};
const chartAnimation = {
  animateScale: false,
  x: {
    duration: 0,
  },
  y: {
    duration: 0,
  },
};

const options = {
  animation: chartAnimation,
  responsive: true,
  maintainAspectRatio: false,
  plugins:
  { 
    title: chartTitle,
    legend: chartLegend,
    tooltip: {
      enabled: true,
    }
  }
};

const data = {
  labels: [], // Inicialmente vacío
  datasets: [
    {
      data: [], // Inicialmente vacío
      backgroundColor: chartColors,
      cutout: "60%",
    },
  ],
};
const doughnutText = {
  id: "doughnutText",
  beforeDraw: (chart) => {
    const { ctx, chartArea } = chart;
    const total = chart.data.datasets[0].data.reduce((sum, value) => sum + value, 0);
    const percentage = total > 0 ? Math.round((chart.data.datasets[0].data[0] / total) * 100) : 0;
    const text = `${percentage}%`;
    const textX = Math.round((chartArea.left + chartArea.right) / 2);
    const textY = Math.round((chartArea.top + chartArea.bottom) / 2);
    ctx.font = "bold 24px segoe ui";
    ctx.fillStyle = "#0f2c64";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(text, textX, textY);
  },
};
const doughnutCanvas = document.getElementById("pieChart").getContext("2d");
const empleadosChart = new Chart(doughnutCanvas, {
  type: "doughnut",
  data: data,
  options: options,
  plugins: [doughnutText]
});

// Función para actualizar la gráfica con nuevos datos
function updateChart(chartData) {
  empleadosChart.data.labels = chartData.map((row) => row.estado); // Actualizar etiquetas
  empleadosChart.data.datasets[0].data = chartData.map((row) => row.total); // Actualizar datos
  empleadosChart.update(); // Refrescar la gráfica
}

// Llama a la función inmediatamente para cargar los datos iniciales
fetchDataAndUpdateChart();

setInterval(fetchDataAndUpdateChart, 5000);
