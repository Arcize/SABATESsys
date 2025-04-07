// Referencias a los elementos
const overlay = document.querySelector(".overlay-modal");
const modal = document.querySelector(".modal-box");
const form = document.querySelector(".form");
const inputKey = document.querySelector(".inputKey"); // Obtener el campo de ID del formulario
let OriginalHeader = ""; // Declarar variables fuera de la función para que sean accesibles
let OriginalAction = "";

const formType = form.getAttribute("formType"); // Obtener el tipo de formulario
const idType = inputKey.getAttribute("id"); // Obtener el tipo de ID

// Variable para verificar si el event listener submit ya existe
let submitListenerAdded = false;

// Delegación de eventos para manejar clics en botones "open-modal"
document.addEventListener("click", (event) => {
  const button = event.target.closest(".open-modal"); // Verifica si el clic proviene de un botón con la clase "open-modal"
  if (!button) return;
  const id = button.getAttribute("data-id"); // Obtener el ID si es necesario

  const isFetchRequired = button.getAttribute("data-fetch") === "true"; // Verificar si necesita fetch
  if (isFetchRequired && id) {
    // Realizar la solicitud Fetch
    fetch(`index.php?view=${formType}&action=${formType}_fetch_one&${idType}=${id}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
      })
      .then((data) => {
        // Rellenar los campos del formulario con los datos obtenidos
        Array.from(form.elements).forEach((field) => {
          if (field.name && data[field.name] !== undefined) {
            field.value = data[field.name]; // Asignar valor si existe en los datos
          }
        });

        // Cambiar título del modal para edición
        const modalHeader = document.querySelector(".modal-header h3");
        saveData(); // Guardar datos originales
        modalHeader.textContent = OriginalHeader.replace(/Registrar|Crear/gi, "Editar"); // Cambiar el título a "Editar"
        // Cambiar acción del formulario a actualizar
        form.action = `index.php?view=${formType}&action=${formType}_fetch_update`;

        // Mostrar el modal
        openModalWithAnimations();
      })
      .catch((error) => console.error("Error:", error));
  } else {
    // Si no se necesita fetch, mostrar el modal con contenido estático
    openModalWithAnimations();
    saveData();
  }
});

// Event listener submit para el formulario (agregado solo una vez)
if (!submitListenerAdded) {
  form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Prevenir el envío del formulario por defecto
    const formData = new FormData(form); // Crear un objeto FormData con los datos del formulario
    try {
      const sentBtn = form.querySelector(".sentBtn");
      sentBtn.disabled = true;

      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
      });
      if (response.ok) {
        const data = await response.json(); // Si la respuesta es JSON, conviértela
        console.log("Respuesta del servidor:", data); // Muestra la respuesta en la consola
        if (data.success) {
          // Si la respuesta es exitosa, puedes cerrar el modal y actualizar la tabla
          closeModal();
          // Actualizar la tabla después de cerrar el modal
          const currentPage = parseInt(
            document.querySelector(".page-button-active").getAttribute("data-page")
          ); // Obtener la página activa
          const viewName = form.getAttribute("formType"); // Obtener el nombre de la vista
          await loadPage(viewName, currentPage); // Llama a la función loadPage para recargar los datos de la página actual
          if (data.type === "create") {
            fetchCreateSuccess(); // Llama a la función de éxito de creación
          } else if (data.type === "edit") {
            fetchEditSuccess(); // Llama a la función de éxito de edición
          }
        }
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });
  submitListenerAdded = true; // Marcar que el event listener ya se agregó
}

function saveData() {
  OriginalHeader = modal.querySelector(".modal-header h3").textContent; // Guardar el título original
  OriginalAction = form.action; // Guardar la acción original
}

// Función para abrir el modal con animaciones
function openModalWithAnimations() {
  overlay.classList.add("overlay-active");
  modal.classList.add("modal-active");
  setTimeout(() => {
    overlay.classList.add("overlay-opening");
    modal.classList.add("modal-opening");
  }, 0);
}

// Función para cerrar el modal
if (overlay && modal) {
  overlay.addEventListener("click", closeModal);
  modal.querySelector(".close-modal").addEventListener("click", closeModal);

  // Evitar que el clic dentro del modal cierre el overlay
  modal.addEventListener("click", (event) => {
    event.stopPropagation();
  });
}

// Función para cerrar el modal con animaciones
function closeModal() {
  form.noValidate = true;
  modal.classList.add("modal-closing"); // Añade animación de salida
  overlay.classList.add("overlay-closing"); // Añade animación de salida
  setTimeout(() => {
    modal.classList.remove("modal-active", "modal-closing", "modal-opening");
    overlay.classList.remove(
      "overlay-active",
      "overlay-closing",
      "overlay-opening"
    );
    form.noValidate = false;
    form.reset(); // Vaciar los campos del formulario

    // Restaurar el título del modal y el action del formulario
    const modalHeader = document.querySelector(".modal-header h3");
    modalHeader.textContent = OriginalHeader;
    form.action = OriginalAction;

    const cedulaError = form.querySelector(".cedulaError");
    const sentBtn = form.querySelector(".sentBtn");
    if (cedulaError) {
      cedulaError.textContent = ""; // Limpiar el mensaje de error de cédula
    }
    if (sentBtn) {
      sentBtn.disabled = false; // Habilitar el botón de envío
    }
  }, 300); // Coincide con la duración de la animación (0.3s)
}