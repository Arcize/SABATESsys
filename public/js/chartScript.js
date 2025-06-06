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
      font: { size: 18 },
      color: "#0f2c64",
      padding: { bottom: 12 },
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

  // Evento de click para redirigir según el segmento
  const onClick = function (event, elements) {
    if (elements.length > 0) {
      const chart = Chart.getChart(canvasId);
      const index = elements[0].index;
      const label = chart.data.labels[index];

      // Mapeo de redirección por gráfica
      const chartRedirects = {
        empleados: {
          view: 'employeeTable',
          param: 'estatus',
          normalize: (l) => l === 'Registrados' ? 'Registrado' : l
        },
        roles: {
          view: 'userTable',
          param: 'rol',
          normalize: (l) => l
        },
        reportes_fallas: {
          view: 'faultReportTable',
          param: 'dia',
          normalize: (l) => l
        },
        reportes_fallas_mensual: {
          view: 'faultReportTable',
          param: 'estado',
          normalize: (l, chart, elements, chartData) => {
            const datasetIndex = elements[0].datasetIndex;
            return chart.data.datasets[datasetIndex].label;
          },
          extraParams: (index, chartData) => {
            const ranges = chartData.ranges || [];
            const range = ranges[index] || {};
            if (range.start && range.end) {
              return `&fecha_inicio=${encodeURIComponent(range.start)}&fecha_fin=${encodeURIComponent(range.end)}`;
            }
            return '';
          }
        },
        fallas_por_tipo: {
          view: 'faultReportTable',
          param: 'tipo',
          normalize: (l) => l
        },
        estado_equipos: {
          view: 'pcTable',
          param: 'estado',
          normalize: (l) => l
        },
        prioridades_fallas: {
          view: 'faultReportTable',
          param: 'prioridad',
          normalize: (l) => l
        },
        actividades_por_tipo: {
          view: 'activitiesReportTable',
          param: 'tipo_actividad',
          normalize: (l) => l
        }
      };

      const redirect = chartRedirects[chartId];
      if (redirect) {
        let filtro = redirect.normalize(label, chart, elements, chartData);
        let url = `index.php?view=${redirect.view}&${redirect.param}=${encodeURIComponent(filtro)}`;
        if (redirect.extraParams) {
          url += redirect.extraParams(index, chartData);
        }
        window.location.href = url;
      } else {
        // Por defecto, redirigir a una tabla genérica
        window.location.href = `index.php?view=tabla&filtro=${encodeURIComponent(label)}`;
      }
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
            borderColor: chartData.borderColor,
            fill: chartData.fill
          },
        ],
      },
      options: {
        ...defaultOptions,
        indexAxis: chartData.horizontal ? 'y' : 'x', // Hacer horizontal si corresponde
        showCenterText: chartData.showCenterText,
        plugins: {
          ...defaultOptions.plugins,
          title: { ...defaultOptions.plugins.title, text: chartData.title },
          legend: {
            display: chartData.legendDisplay !== undefined ? chartData.legendDisplay : (chartData.type !== "bar" || !!chartData.datasets),
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
