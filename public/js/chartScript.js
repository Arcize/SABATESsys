// Variable para almacenar los datos previos de todas las gráficas
let previousChartsData = {};

// Función para obtener datos y actualizar las gráficas
function fetchDataAndUpdateCharts() {
  fetch("index.php?view=chartsData")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor");
      }
      return response.json();
    })
    .then((chartsData) => {
      chartsData.forEach((chartData) => {
        const chartId = chartData.id; // Identificador único para cada gráfica
        if (
          !previousChartsData[chartId] ||
          JSON.stringify(chartData.data) !==
            JSON.stringify(previousChartsData[chartId])
        ) {
          updateOrCreateChart(chartId, chartData);
          previousChartsData[chartId] = chartData.data; // Actualizar los datos previos
        } else {
          console.log(
            `Los datos de la gráfica ${chartId} son idénticos, no se actualiza.`
          );
        }
      });
    })
    .catch((error) => {
      console.error("Error en la solicitud fetch:", error);
    });
}

// Configuración de colores y opciones comunes
const chartColors = ["#211C84", "#7A73D1"];
const defaultOptions = {
  animation: {
    animateScale: false,
    x: { duration: 0 },
    y: { duration: 0 },
  },
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    title: {
      display: true,
      font: { size: 20 },
      color: "#0f2c64",
      padding: { bottom: 20 },
    },
    legend: { position: "bottom" },
    tooltip: { enabled: true },
  },
};

// Plugin para mostrar texto en el centro de la gráfica tipo "doughnut"
const centerTextPlugin = {
  id: "doughnutText",
  beforeDraw: (chart) => {
    if (chart.config.type === "doughnut") {
      const { width } = chart;
      const { ctx } = chart;
      const total = chart.data.datasets[0].data.reduce(
        (sum, value) => sum + value,
        0
      );
      const percentage =
        total > 0
          ? Math.round((chart.data.datasets[0].data[0] / total) * 100)
          : 0;

      ctx.save();
      ctx.font = "bold 24px Segoe UI";
      ctx.fillStyle = "#0f2c64";
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      ctx.fillText(
        `${percentage}%`,
        width / 2,
        chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2
      );
      ctx.restore();
    }
  },
};

// Función para crear o actualizar una gráfica
function updateOrCreateChart(chartId, chartData) {
  const canvasId = `chart-${chartId}`;
  let canvas = document.getElementById(canvasId);

  // Crear un nuevo canvas si no existe
  if (!canvas) {
    const panel = document.querySelector(`[data-panel="${chartData.panel}"]`);
    if (!panel) {
      console.error(
        `No se encontró el panel con el identificador ${chartData.panel}`
      );
      return;
    }
    canvas = document.createElement("canvas");
    canvas.id = canvasId;
    panel.appendChild(canvas);
  }

  const ctx = canvas.getContext("2d");

  // Si ya existe una gráfica, actualizarla
  if (Chart.getChart(canvasId)) {
    const chart = Chart.getChart(canvasId);
    chart.data.labels = chartData.labels;
    chart.data.datasets[0].data = chartData.data;
    chart.update();
  } else {
    // Crear una nueva gráfica con configuraciones específicas
    new Chart(ctx, {
      type: chartData.type || "doughnut", // Usa el tipo especificado o 'doughnut' por defecto
      data: {
        labels: chartData.labels, // Etiquetas (e.g., días de la semana)
        datasets: [
          {
            data: chartData.data, // Datos (e.g., número de reportes)
            backgroundColor: chartData.backgroundColor || chartColors, // Colores personalizados
          },
        ],
      },
      options: {
        ...defaultOptions,
        plugins: {
          ...defaultOptions.plugins,
          title: { ...defaultOptions.plugins.title, text: chartData.title },
          legend: {
            display: chartData.type !== "bar", // Oculta la leyenda si es una gráfica de barras
            position: "bottom"
          },
        },
        scales:
          chartData.type === "doughnut"
            ? {}
            : {
                // Deshabilita las escalas para gráficas de dona
                x: {
                  grid: {
                    display: false, // Oculta las líneas de escala en el eje X
                  },
                  ticks: {
                    display: true, // Muestra las etiquetas del eje X
                  },
                  beginAtZero: true,
                },
                y: {
                  grid: {
                    display: false, // Oculta las líneas de escala en el eje X
                  },
                  ticks: {
                    display: true,
                    stepSize: 1, // Intervalo entre los ticks
                  },
                  beginAtZero: true,
                },
              },
      },
      plugins: [centerTextPlugin], // Agregar el plugin aquí
    });
  }
}

// Llama a la función inmediatamente para cargar los datos iniciales
fetchDataAndUpdateCharts();

// Actualizar las gráficas cada 5 segundos
setInterval(fetchDataAndUpdateCharts, 5000);
