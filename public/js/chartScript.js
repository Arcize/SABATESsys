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
    if (chart.config.type === "doughnut" && chart.config.options.showCenterText) {
      const { width } = chart;
      const { ctx } = chart;
      const total = chart.data.datasets[0].data.reduce(
        (sum, value) => sum + value,
        0
      );
      const percentage =
        total > 0
          ? Math.round((chart.data.datasets[0].data[1] / total) * 100)
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

  // Evento de click para redirigir según el segmento
  const onClick = function (event, elements) {
    if (elements.length > 0) {
      const chart = Chart.getChart(canvasId);
      const index = elements[0].index;
      const label = chart.data.labels[index];

      // Redirigir según el gráfico y el label seleccionado
      if (chartId === "empleados") {
        // Ejemplo: filtro por estado de empleados
        window.location.href = `index.php?view=userTable&estado=${encodeURIComponent(label)}`;
      } else if (chartId === "roles") {
        // Ejemplo: filtro por rol
        window.location.href = `index.php?view=userTable&rol=${encodeURIComponent(label)}`;
      } else if (chartId === "reportes_fallas") {
        // Ejemplo: filtro por día de la semana
        window.location.href = `index.php?view=userTable&dia=${encodeURIComponent(label)}`;
      }
      // Agrega más condiciones según tus necesidades
    }
  };

  // Si ya existe una gráfica, actualizarla
  if (Chart.getChart(canvasId)) {
    const chart = Chart.getChart(canvasId);
    chart.data.labels = chartData.labels;
    if (chartData.datasets) {
      chart.data.datasets = chartData.datasets;
    } else {
      chart.data.datasets[0].data = chartData.data;
      chart.data.datasets[0].backgroundColor = chartData.backgroundColor || chartColors;
    }
    chart.options.onClick = onClick; // Actualiza el evento onClick
    chart.update();
  } else {
    // Crear una nueva gráfica con configuraciones específicas
    new Chart(ctx, {
      type: chartData.type || "doughnut",
      data: {
        labels: chartData.labels,
        datasets: chartData.datasets || [
          {
            data: chartData.data,
            backgroundColor: chartData.backgroundColor || chartColors,
          },
        ],
      },
      options: {
        ...defaultOptions,
        showCenterText: chartData.showCenterText,
        plugins: {
          ...defaultOptions.plugins,
          title: { ...defaultOptions.plugins.title, text: chartData.title },
          legend: {
            display: chartData.type !== "bar" || !!chartData.datasets,
            position: "bottom"
          },
        },
        scales:
          chartData.type === "doughnut"
            ? {}
            : {
                x: {
                  grid: { display: false },
                  ticks: { display: true },
                  beginAtZero: true,
                  stacked: !!chartData.stacked,
                },
                y: {
                  grid: { display: false },
                  ticks: { display: true, stepSize: 1 },
                  beginAtZero: true,
                  stacked: !!chartData.stacked,
                },
              },
        onClick: onClick,
        onHover: function(event, elements) {
          event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
        },
      },
      plugins: [centerTextPlugin],
    });
  }
}

// Llama a la función inmediatamente para cargar los datos iniciales
fetchDataAndUpdateCharts();

// Actualizar las gráficas cada 5 segundos
setInterval(fetchDataAndUpdateCharts, 5000);
