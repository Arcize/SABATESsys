let ctx = document.getElementById("pieChart").getContext("2d");
let pieChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    labels: ["Empleados Registrados", "Empleados sin Registrar"],
    datasets: [
      {
        data: [10, 20], // Datos ficticios de ejemplo
        backgroundColor: [
          "#211C84", // Colores para los segmentos
          "#7A73D1",
        ],
      },
    ],
  },
  options: {
    responsive: true,
    
    maintainAspectRatio: false, // Opcional: para ajustar proporciones libremente
    plugins: {
      legend: {
        position: "right", // Leyenda en la parte superior
      },
    },
  },
});
