// Configuración inicial
let currentPage = 1; // Número de la página inicial
let totalPages = 0; // Inicializar el número total de páginas

// Obtener el número total de páginas de forma dinámica
(async function initializeTotalPages() {
    const totalRecords = await loadPageButtons(); // Cargar el total de registros
    const recordsPerPage = 10; // Registros por página
    totalPages = Math.ceil(totalRecords / recordsPerPage); // Calcular el número total de páginas
    updatePagination(); // Actualizar la paginación después de obtener el total de páginas
})();

// Función para actualizar los botones de navegación
function updateNavigationButtons() {
  const prevButton = document.querySelector(".pagination-button.prev");
  const nextButton = document.querySelector(".pagination-button.next");

  toggleButtonState(prevButton, currentPage === 1, goToPrevPage);
  toggleButtonState(nextButton, currentPage === totalPages, goToNextPage);
}

// Función para habilitar/deshabilitar botones
function toggleButtonState(button, isDisabled, callback) {
  if (isDisabled) {
    button.classList.add("pagination-button-disabled");
    button.removeEventListener("click", callback);
  } else {
    button.classList.remove("pagination-button-disabled");
    button.addEventListener("click", callback);
  }
}

// Función para ir a la página anterior
function goToPrevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadPage(currentPage); // Cargar la página actualizada
        updatePagination();
    }
}

// Función para ir a la página siguiente
function goToNextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        loadPage(currentPage); // Cargar la página actualizada
        updatePagination();
    }
}

// Función para actualizar el botón activo de la página
function updateActivePageButton() {
  document
    .querySelector(".page-button-active")
    ?.classList.remove("page-button-active");
  document
    .querySelector(`.page-button[data-page="${currentPage}"]`)
    ?.classList.add("page-button-active");
}

// Función para actualizar la paginación
function updatePagination() {
  updateNavigationButtons();
  updateActivePageButton();
}

// Escuchar clics de botones de página
document.querySelector(".pages").addEventListener("click", (event) => {
  const button = event.target.closest(".page-button");
  if (button) {
    currentPage = parseInt(button.getAttribute("data-page"));
    updatePagination();
  }
});

async function loadPage(page = 1) {
    try {
        const answer = await fetch(
            `index.php?view=employee&action=employee_fetch_page&page=${page}`
        );
        if (!answer.ok) {
            throw new Error(`Error HTTP: ${answer.status}`);
        }
        const responseData = await answer.json();

        // Renderizar los datos en la tabla
        renderTable(responseData);
    } catch (error) {
        console.error("Error:", error);
    }
}

// Actualizar la función de clic en los botones de página para pasar el atributo data-page
document.querySelector(".pages").addEventListener("click", (event) => {
    const button = event.target.closest(".page-button");
    if (button) {
        const page = parseInt(button.getAttribute("data-page"));
        currentPage = page;
        loadPage(page); // Llamar a la función con el número de página
        updatePagination();
    }
});

async function loadPageButtons() {
    try {
        const answer = await fetch(
        `index.php?view=employee&action=employee_fetch_total_records`
        );
        if (!answer.ok) {
        throw new Error(`Error HTTP: ${answer.status}`);
        }
        const responseData = await answer.json();
        // Renderizar los botones de paginación
        renderPaginationButtons(responseData);
        return responseData; // Retornar el total de registros
    } catch (error) {
        console.error("Error:", error);
    }
}

function renderPaginationButtons(data) {
    const totalRecords = data; // Total de registros
    const recordsPerPage = 10; // Registros por página
    const totalPages = Math.ceil(totalRecords / recordsPerPage); // Calcular el número total de páginas
    
    const paginationContainer = document.querySelector(".pages");
    paginationContainer.innerHTML = ""; // Limpiar el contenido actual de la paginación
    
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement("button");
        pageButton.classList.add("page-button");
        pageButton.classList.add("btn");
        pageButton.setAttribute("data-page", i);
        pageButton.textContent = i;
    
        if (i === currentPage) {
        pageButton.classList.add("page-button-active");
        }
        paginationContainer.appendChild(pageButton);
    }
    
}

function renderTable(data) {
    const tableBody = document.querySelector("#table-body");
    tableBody.innerHTML = ""; // Limpiar el contenido actual de la tabla

    const recordsPerPage = 10; // Número de registros por página

    data.forEach((item, index) => {
        const row = document.createElement("tr");
        row.classList.add("table-row"); // Agregar clase a cada fila

        const startIndex = (currentPage - 1) * 10; // Calcular el índice inicial de la página actual
        row.innerHTML = `
            <td>${startIndex + index + 1}</td>
            <td>${item.nombre}</td>
            <td>${item.apellido}</td>
            <td>${item.cedula}</td>
            <td>${item.nombre_departamento}</td>
            <td>${item.sexo}</td>
            <td class="relative-container">
                <div class="button-container">
                <div>
                    <a>
                    <button class="crud-button edit-button open-modal" data-fetch="true" data-id="${item.id_persona}">
                        <img src="app/views/img/edit.svg" alt="Edit">
                    </button>
                    </a>
                </div>
                <div>
                    <a>
                    <button class="crud-button details-button">
                        <img src="app/views/img/visibility.svg" alt="View">
                    </button>
                    </a>
                </div>
                <div>
                    <a >
                    <button class="crud-button delete-button" onclick="confirmDelete(${item.id_persona})">
                        <img src="app/views/img/delete.svg" alt="Delete">
                    </button>
                    </a>
                </div>
                </div>
            </td>
            `;

        tableBody.appendChild(row);
    });

    // Agregar filas vacías si no hay suficientes registros en la página
    const emptyRows = recordsPerPage - data.length;
    for (let i = 0; i < emptyRows; i++) {
        const emptyRow = document.createElement("tr");
        emptyRow.classList.add("empty-table-row");
        emptyRow.innerHTML = `
                        <td colspan="7" class="empty-row-cell"></td>
                `;
        tableBody.appendChild(emptyRow);
    }
}

// Llamar a la función para cargar la página inicial
loadPage();
loadPageButtons();
