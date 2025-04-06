let currentPage = 1;
let totalPages = 0;

/**
 * Inicializa la paginación para una tabla específica.
 * @param {string} tableId - El ID de la tabla.
 * @param {string} viewName - El nombre de la vista (para la solicitud al servidor).
 */
async function initializePagination(tableId, viewName) {
  console.log(`Inicializando paginación para: ${tableId}, vista: ${viewName}`);

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
    toggleButtonState(prevButton, currentPage === 1, () =>
      goToPrevPage(viewName)
    );
    toggleButtonState(nextButton, currentPage === totalPages, () =>
      goToNextPage(viewName)
    );
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

  button.removeEventListener("click", callback);
  if (!isDisabled) button.addEventListener("click", callback);
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
  
    if (data && data.length > 0) { // Verifica si data tiene elementos
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
              <img src="app/views/img/edit.svg" alt="Editar">
            </button>
            <button class="crud-button details-button">
              <img src="app/views/img/visibility.svg" alt="Ver detalles">
            </button>
            <button class="crud-button delete-button" onclick="confirmDelete(${firstItem})">
              <img src="app/views/img/delete.svg" alt="Eliminar">
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
    loadPage(viewName, currentPage);
    updatePagination(viewName);
  }
});
