let currentPage = 1;
let totalPages = 0;
let currentViewName = ''; // Variable para almacenar el viewName actual

// Variables para almacenar las referencias de las funciones de callback
let prevPageHandler;
let nextPageHandler;

/**
 * Inicializa la paginación para una tabla específica.
 * @param {string} tableId - El ID de la tabla.
 * @param {string} viewName - El nombre de la vista (para la solicitud al servidor).
 */
async function initializePagination(tableId, viewName) {
  console.log(`Inicializando paginación para: ${tableId}, vista: ${viewName}`);
  currentViewName = viewName; // Almacenar el viewName actual

  try {
    const totalRecords = await loadPageButtons(viewName);
    const recordsPerPage = 10;
    totalPages = Math.ceil(totalRecords / recordsPerPage);

    renderPaginationButtons(totalRecords);
    updatePagination(viewName);
    loadPage(viewName, currentPage);
  } catch (error) {
    console.error("Error al inicializar la paginación:", error);
  }
}

/**
 * Actualiza los botones de navegación (anterior y siguiente).
 * @param {string} viewName - El nombre de la vista.
 */
function updateNavigationButtons(viewName) {
  const prevButton = document.querySelector(".pagination-button.prev");
  const nextButton = document.querySelector(".pagination-button.next");

  if (prevButton && nextButton) {
    // Remover listeners existentes
    if (prevPageHandler) {
      prevButton.removeEventListener("click", prevPageHandler);
    }
    if (nextPageHandler) {
      nextButton.removeEventListener("click", nextPageHandler);
    }

    // Crear y asignar nuevos listeners
    prevPageHandler = () => goToPrevPage(viewName);
    nextPageHandler = () => goToNextPage(viewName);

    toggleButtonState(prevButton, currentPage === 1, prevPageHandler);
    toggleButtonState(nextButton, currentPage === totalPages, nextPageHandler);
  }
}

/**
 * Habilita o deshabilita un botón de paginación.
 * @param {HTMLElement} button - El botón a actualizar.
 * @param {boolean} isDisabled - Indica si el botón debe estar deshabilitado.
 * @param {function} callback - La función a ejecutar cuando se hace clic en el botón.
 */
function toggleButtonState(button, isDisabled, callback) {
  if (!button) return;

  button.classList.toggle("pagination-button-disabled", isDisabled);

  if (!isDisabled && callback) {
    button.addEventListener("click", callback);
  }
}

/**
 * Navega a la página anterior.
 * @param {string} viewName - El nombre de la vista.
 */
function goToPrevPage(viewName) {
  if (currentPage > 1) {
    currentPage--;
    loadPage(viewName, currentPage);
    updatePagination(viewName);
  }
  console.log(currentPage);

}

/**
 * Navega a la página siguiente.
 * @param {string} viewName - El nombre de la vista.
 */
function goToNextPage(viewName) {
  if (currentPage < totalPages) {
    currentPage++;
    loadPage(viewName, currentPage);
    updatePagination(viewName);
  }
  console.log(currentPage);
}

/**
 * Actualiza el botón de la página activa.
 */
function updateActivePageButton() {
  const activeButton = document.querySelector(".page-button-active");
  if (activeButton) activeButton.classList.remove("page-button-active");

  const newActiveButton = document.querySelector(
    `.page-button[data-page="${currentPage}"]`
  );
  if (newActiveButton) newActiveButton.classList.add("page-button-active");
}

/**
 * Actualiza la interfaz de paginación.
 * @param {string} viewName - El nombre de la vista.
 */
function updatePagination(viewName) {
  updateNavigationButtons(viewName);
  updateActivePageButton();
}

/**
 * Carga los datos de una página desde el servidor.
 * @param {string} viewName - El nombre de la vista.
 * @param {number} page - El número de página a cargar.
 */
async function loadPage(viewName, page = 1) {
  try {
    const response = await fetch(
      `index.php?view=${viewName}&action=${viewName}_fetch_page&page=${page}`
    );
    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

    const data = await response.json();
    // Verificar si data es un array
    if (Array.isArray(data)) {
      renderTable(data);
    } else {
      console.log("No hay datos para mostrar.");
      renderTable([]); // Pasar un array vacío a renderTable
    }
  } catch (error) {
    console.error("Error en la carga de la página:", error);
  }
}

/**
 * Carga el número total de registros desde el servidor.
 * @param {string} viewName - El nombre de la vista.
 */
async function loadPageButtons(viewName) {
  try {
    const response = await fetch(
      `index.php?view=${viewName}&action=${viewName}_fetch_total_records`
    );
    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

    const totalRecords = await response.json();
    return totalRecords;
  } catch (error) {
    console.error("Error al cargar botones de paginación:", error);
  }
}

/**
 * Renderiza los botones de paginación.
 * @param {number} totalRecords - El número total de registros.
 */
function renderPaginationButtons(totalRecords) {
  const recordsPerPage = 10;
  let totalPages = Math.ceil(totalRecords / recordsPerPage);

  // Asegurar al menos un botón de página
  console.log(totalRecords);
  if (totalRecords.error) {
    totalPages = 1;
  }
  const paginationContainer = document.querySelector(".pages");

  if (!paginationContainer) {
    console.error(`Error: No se encontró el contenedor de paginación`);
    return;
  }

  paginationContainer.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const pageButton = document.createElement("button");
    pageButton.classList.add("page-button", "btn");
    pageButton.setAttribute("data-page", i);
    pageButton.textContent = i;

    if (i === currentPage) pageButton.classList.add("page-button-active");
    paginationContainer.appendChild(pageButton);
  }

  updateNavigationButtons(currentViewName);
}

/**
 * Renderiza los datos de la tabla.
 * @param {Array} data - Los datos a renderizar.
 */
function renderTable(data) {
  const tableBody = document.querySelector("#table-body");
  if (!tableBody) {
    console.error(`Error: No se encontró el cuerpo de la tabla`);
    return;
  }

  tableBody.innerHTML = "";

  const recordsPerPage = 10;
  const camposOcultar = [
    "id_persona",
    "id_departamento",
    "id_sexo",
    "id_usuario",
    "correo",
    "fecha_nac",
  ];

  if (data && data.length > 0) {
    // Verifica si data tiene elementos
    data.forEach((item, index) => {
      const row = document.createElement("tr");
      row.classList.add("table-row");
      const startIndex = (currentPage - 1) * recordsPerPage;

      const indexCell = document.createElement("td");
      indexCell.textContent = startIndex + index + 1;
      row.appendChild(indexCell);

      for (const key in item) {
        if (item.hasOwnProperty(key) && !camposOcultar.includes(key)) {
          const cell = document.createElement("td");
          cell.textContent = item[key];
          row.appendChild(cell);
        }
      }

      const actionsCell = document.createElement("td");
      const firstItem = Object.values(item)[0];
      actionsCell.classList.add("relative-container");
      actionsCell.innerHTML = `
          <div class="button-container">
            <button class="crud-button edit-button open-modal" data-fetch="true" data-id="${firstItem}">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
            </button>
            <button class="crud-button details-button">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-134 0-244.5-72T61-462q-5-9-7.5-18.5T51-500q0-10 2.5-19.5T61-538q64-118 174.5-190T480-800q134 0 244.5 72T899-538q5 9 7.5 18.5T909-500q0 10-2.5 19.5T899-462q-64 118-174.5 190T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/></svg>
            </button>
            <button class="crud-button delete-button" onclick="confirmDelete(${firstItem})">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
            </button>
          </div>
        `;
      row.appendChild(actionsCell);

      tableBody.appendChild(row);
    });
  }

  const emptyRows = recordsPerPage - (data ? data.length : 0); // Maneja el caso de data null o undefined
  let dynamicCellCount = 0;
  if (data && data.length > 0) {
    dynamicCellCount = Object.keys(data[0]).filter(
      (key) => !camposOcultar.includes(key)
    ).length;
  }

  for (let i = 0; i < emptyRows; i++) {
    const emptyRow = document.createElement("tr");
    emptyRow.classList.add("empty-table-row");
    emptyRow.innerHTML = `<td class="empty-row-cell"></td>`.repeat(
      dynamicCellCount + 2
    ); // +2 para index y buttons
    tableBody.appendChild(emptyRow);
  }
}

// Event listener para los botones de página.
document.addEventListener("click", (event) => {
  const button = event.target.closest(".page-button");
  if (button) {
    currentPage = parseInt(button.getAttribute("data-page"));
    loadPage(currentViewName, currentPage);
    updatePagination(currentViewName);
  }
});