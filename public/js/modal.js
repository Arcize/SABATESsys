// Variables para almacenar los modales y overlays activos
const activeModals = {};
let OriginalHeader = "";
let OriginalAction = "";
let currentForm = null; // Para rastrear el formulario dentro del modal activo
let myDropzone = null; // Variable global para la instancia de Dropzone
let uploadedTempFiles = []; // Almacena los nombres de archivo generados por el servidor temporal

// Bandera para evitar doble envío del formulario principal
let formSaving = false; // Indica si el formulario principal está en proceso de envío

// Asegúrate de que Dropzone no se inicialice automáticamente
Dropzone.autoDiscover = false;

// ---
// 1. Función para inicializar Dropzone
// ---
function initDropzone(element, formType) {
  // Si ya existe una instancia de Dropzone, destrúyela para evitar duplicados
  if (myDropzone) {
    myDropzone.destroy();
    myDropzone = null;
  }
  // Siempre limpiar la lista de archivos temporales al inicializar un nuevo Dropzone
  uploadedTempFiles = [];

  myDropzone = new Dropzone(element, {
    url: `index.php?view=${formType}&action=${formType}_upload_temp_files`, // Endpoint para subidas temporales
    paramName: "file",
    maxFilesize: 5, // MB
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictRemoveFile: "Eliminar",
    dictDefaultMessage: "Arrastra tus imágenes aquí o haz clic para subir",
    dictInvalidFileType:
      "No puedes subir archivos de este tipo. Solo se permiten imágenes.",
    dictFileTooBig:
      "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
    autoProcessQueue: true, // Procesa los archivos automáticamente al añadirlos
    uploadMultiple: false, // Sube un archivo a la vez
    maxFiles: 3, // Máximo de 3 archivos
    parallelUploads: 1,
    // timeout: 0, // Ajusta si tienes problemas con subidas grandes en conexiones lentas

    init: function () {
      const dz = this;

      dz.on("addedfile", function (file) {
        console.log("Archivo añadido a Dropzone:", file.name);
      });

      dz.on("removedfile", function (file) {
        console.log("Archivo eliminado de Dropzone (interfaz):", file.name);
        // Verificar si el archivo tiene un serverId (se subió o se precargó)
        if (file.serverId) {
          // Eliminar de la lista de archivos temporales que se enviarán con el formulario principal
          const index = uploadedTempFiles.indexOf(file.serverId);
          if (index > -1) {
            uploadedTempFiles.splice(index, 1);
            console.log(
              "Archivo removido de la lista uploadedTempFiles:",
              file.serverId
            );
          } else {
            console.warn(
              "Archivo no encontrado en uploadedTempFiles al intentar remover:",
              file.serverId
            );
          }

          // Con la estrategia de "borrar y reconstruir" en el backend,
          // NO necesitamos enviar una petición DELETE aquí para las imágenes existentes.
          // La responsabilidad de eliminar recae en el backend al procesar el formulario principal.
          // Si es un archivo temporal nuevo que no se va a guardar, sigue siendo una buena práctica
          // que el cron job (o un limpiador pasivo) se encargue de eliminarlo.
        }
      });

      dz.on("success", function (file, response) {
        console.log(
          "Archivo temporal subido con éxito por Dropzone:",
          file.name,
          response
        );
        if (response.success && response.filename) {
          file.serverId = response.filename; // Guarda el nombre generado por el servidor
          uploadedTempFiles.push(response.filename); // Añadir a la lista de archivos temporales
        } else {
          console.error(
            "Respuesta inesperada del servidor temporal:",
            response
          );
          file.previewElement.classList.add("dz-error");
          dz.emit("error", file, "Error al subir archivo temporal.");
        }
      });

      dz.on("error", function (file, message) {
        console.error(
          "Error al subir el archivo en Dropzone:",
          file.name,
          message
        );
        // Puedes mostrar un mensaje de error visual al usuario aquí
        if (file.previewElement) {
          file.previewElement.classList.add("dz-error-mark"); // Clase para marcar visualmente el error
          file.previewElement.querySelector(".dz-error-message").textContent =
            message; // Mostrar mensaje de error
        }
      });
    },
  });
}

// ---
// 2. Delegación de eventos para manejar clics en botones "open-modal"
// ---
document.addEventListener("click", (event) => {
  const button = event.target.closest(".open-modal");
  if (!button) return;

  const targetModalId = button.getAttribute("data-target-modal");
  const modal = document.getElementById(targetModalId);
  const overlay = modal ? modal.closest(".overlay-modal") : null;

  if (!modal || !overlay) {
    console.error(
      `No se encontró el modal o el overlay para el ID: ${targetModalId}`
    );
    return;
  }

  currentForm = modal.querySelector(".form");
  const inputKey = currentForm ? currentForm.querySelector(".inputKey") : null;
  const formType = currentForm ? currentForm.getAttribute("formType") : null;
  const idType = inputKey ? inputKey.getAttribute("id") : null;
  const id = button.getAttribute("data-id");
  const isFetchRequired = button.getAttribute("data-fetch") === "true";

  // Guarda el estado original del modal (encabezado, acción del formulario)
  saveOriginalState(modal);

  // Inicializar Dropzone si el formulario es de tipo 'activitiesReport' y tiene el área de Dropzone
  const dropzoneElement = currentForm
    ? currentForm.querySelector("#dropzone-area")
    : null;
  if (dropzoneElement && formType === "activitiesReport") {
    initDropzone(dropzoneElement, formType);
  } else {
    // Asegúrate de destruir Dropzone si no se usará en este modal (ej. abres otro modal sin DZ)
    if (myDropzone) {
      myDropzone.destroy();
      myDropzone = null;
    }
  }

  // Si se requiere fetch (modo edición)
  if (isFetchRequired && id && currentForm && formType && idType) {
    fetch(
      `index.php?view=${formType}&action=${formType}_fetch_one&${idType}=${id}`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
      })
      .then((data) => {
        // Rellenar campos del formulario
        Array.from(currentForm.elements).forEach((field) => {
          if (field.name && data[field.name] !== undefined) {
            field.value = data[field.name];
          }
        });

        // Manejo de formularios multipasos
        if (currentForm.classList.contains("multi-step-form")) {
          console.log("Formulario multipasos detectado.");
          if (
            typeof fillRamModules === "function" &&
            Array.isArray(data.ramData)
          ) {
            fillRamModules(data.ramData);
          }
          if (
            typeof fillStorageModules === "function" &&
            Array.isArray(data.storageData)
          ) {
            fillStorageModules(data.storageData);
          }
        }

        // Actualizar encabezado y acción del formulario para edición
        const modalHeader = modal.querySelector(".modal-header h3");
        if (modalHeader) {
          modalHeader.textContent = OriginalHeader.replace(
            /Registrar|Crear/gi,
            "Editar"
          );
        }
        currentForm.action = `index.php?view=${formType}&action=${formType}_fetch_update`;

        // --- Manejo de Dropzone en modo edición (cargar imágenes existentes) ---
        if (dropzoneElement && formType === "activitiesReport") {
          if (data.existingImages && Array.isArray(data.existingImages)) {
            const reportId = data.id_reporte_actividades;
            if (!reportId) {
              console.error(
                "No se encontró el ID del reporte para cargar las imágenes existentes."
              );
              return;
            }

            data.existingImages.forEach((image) => {
              const mockFile = {
                name: image.name, // Esto ahora será solo el nombre del archivo (ej. "imagen.jpg")
                size: image.size,
                type: image.type || "image/jpeg",
                status: Dropzone.SUCCESS,
                accepted: true,
                serverId: image.name, // Sigue siendo solo el nombre del archivo
              };

              // Opción 1: Reconstruir la URL (si el backend solo envió 'name')
              const imageUrl = `./uploads/report_${reportId}/${image.name}`;

              // Opción 2: Usar la ruta completa si tu backend la envió como 'full_path_for_frontend'
              // const imageUrl = image.full_path_for_frontend;
              // console.log("URL de imagen reconstruida/recibida:", imageUrl); // Para depuración

              uploadedTempFiles.push(image.name);

              myDropzone.emit("addedfile", mockFile);
              myDropzone.emit("thumbnail", mockFile, imageUrl); // Dropzone usará esta URL para mostrar la miniatura
              myDropzone.emit("complete", mockFile);
              myDropzone.files.push(mockFile);
              mockFile.previewElement.classList.add("dz-success");
              mockFile.previewElement.classList.add("dz-complete");
            });
          }
        }

        // --------------------------------------------------------

        openModalWithAnimations(overlay, modal);
      })
      .catch((error) =>
        console.error("Error al cargar datos para edición:", error)
      );
  } else {
    // Modo creación o modal sin fetch
    openModalWithAnimations(overlay, modal);
  }
});

// ---
// 3. Event listener para el submit del formulario (EVITA EL DOBLE ENVÍO)
// ---
document.addEventListener("submit", async (event) => {
  if (!event.target.classList.contains("form")) return;
  event.preventDefault(); // Previene el envío estándar del formulario

  const form = event.target;
  const sentBtn = form.querySelector(".sentBtn");
  const dropzoneElement = form.querySelector("#dropzone-area");
  const formType = form.getAttribute("formType");

  // **VERIFICACIÓN CRUCIAL PARA EVITAR EL DOBLE ENVÍO**
  if (form._isSubmitting) {
    console.log("Formulario ya se está enviando, ignorando segundo clic.");
    return; // Detiene la ejecución si ya está en proceso
  }

  // Habilita las banderas y deshabilita el botón INMEDIATAMENTE
  form._isSubmitting = true;
  formSaving = true; // Indica que el formulario principal está en proceso de guardado
  if (sentBtn) sentBtn.disabled = true;

  try {
    // Validar que haya al menos un archivo en Dropzone si aplica
    if (
      dropzoneElement &&
      formType === "activitiesReport" &&
      (!myDropzone || myDropzone.files.length === 0)
    ) {
      throw new Error("Por favor, suba al menos una imagen para el reporte.");
    }

    const formData = new FormData(form);
    // Añadir los nombres de los archivos temporales subidos
    formData.append("uploaded_temp_files", JSON.stringify(uploadedTempFiles));

    const response = await fetch(form.action, {
      method: form.method,
      body: formData,
    });

    // La función handleFormResponse ahora se encarga de habilitar/deshabilitar el botón y resetear banderas.
    await handleFormResponse(response, form, sentBtn);
  } catch (error) {
    console.error("Error en el envío del formulario:", error);
    alert(`Error al guardar el reporte: ${error.message}`);
    // Solo resetear banderas y re-habilitar botón en caso de un error en el fetch/JS (no de servidor)
    if (sentBtn) sentBtn.disabled = false;
    form._isSubmitting = false;
    formSaving = false;
  }
});

// ---
// 4. Función para manejar la respuesta del servidor (Refactorizada)
// ---
async function handleFormResponse(response, form, sentBtn) {
  let data;
  try {
    data = await response.json();
  } catch (e) {
    console.error("Error al parsear la respuesta JSON del servidor:", e);
    // Si no se puede parsear JSON, asume que algo salió mal.
    data = { success: false, message: "Respuesta inválida del servidor." };
  }

  console.log("Respuesta del servidor:", data);

  if (data.success) {
    const modal = form.closest(".modal-box");
    const overlay = modal ? modal.closest(".overlay-modal") : null;

    // Limpiar Dropzone si el formulario se guardó exitosamente
    if (myDropzone) {
      // removeAllFiles(true) limpia la interfaz sin disparar 'removedfile'
      myDropzone.removeAllFiles(true);
      myDropzone.destroy();
      myDropzone = null;
    }
    uploadedTempFiles = []; // Reiniciar la lista de archivos temporales

    form.reset(); // Limpia los campos del formulario
    closeModal(overlay, modal); // Cerrar el modal

    const tableId = document.querySelector(".table")?.id;
    if (tableId) {
      $(`#${tableId}`).DataTable().ajax.reload(null, false); // Recargar la tabla
    }

    // Ejecutar funciones específicas de éxito si están definidas
    if (data.type === "create" && typeof fetchCreateSuccess === "function")
      fetchCreateSuccess();
    if (data.type === "edit" && typeof fetchEditSuccess === "function")
      fetchEditSuccess();

    // Mostrar un mensaje de éxito al usuario (opcional, si tienes una librería de toasts)
    // showToast('success', data.message);
  } else {
    // Mostrar mensaje de error del servidor
    console.error("Operación fallida:", data.message);
    alert(
      `Error: ${data.message || "Hubo un problema al procesar su solicitud."}`
    );
    // No cerrar el modal en caso de error para que el usuario pueda corregir.
  }

  // **SIEMPRE resetear banderas y re-habilitar el botón al final de la respuesta**
  if (sentBtn) sentBtn.disabled = false;
  form._isSubmitting = false;
  formSaving = false;
}

// ---
// 5. Funciones auxiliares (sin cambios significativos en lógica principal)
// ---
function saveOriginalState(modal) {
  const modalHeader = modal.querySelector(".modal-header h3");
  const form = modal.querySelector(".form");
  OriginalHeader = modalHeader ? modalHeader.textContent : "";
  OriginalAction = form ? form.action : "";
  currentForm = form;
}

function openModalWithAnimations(overlay, modal) {
  if (!overlay || !modal) return;
  overlay.classList.add("overlay-active");
  modal.classList.add("modal-active");
  setTimeout(() => {
    overlay.classList.add("overlay-opening");
    modal.classList.add("modal-opening");
  }, 0);
  activeModals[modal.id] = { overlay, modal };
}

// Event listeners para cerrar el modal (botones y clic fuera)
document.addEventListener("click", (event) => {
  const closeButton = event.target.closest(".close-modal");
  if (closeButton) {
    const modal = closeButton.closest(".modal-box");
    const overlay = modal ? modal.closest(".overlay-modal") : null;
    if (overlay && modal) {
      closeModal(overlay, modal);
    }
  }
  // Cierre al hacer clic fuera del modal (en el overlay activo)
  if (
    event.target.classList.contains("overlay-active") &&
    !event.target.classList.contains("modal-box")
  ) {
    const overlay = event.target;
    const modal = overlay.querySelector(".modal-box.modal-active");
    if (modal) {
      closeModal(overlay, modal);
    }
  }
});

// Función para cerrar el modal y restablecer su estado
function closeModal(overlay, modal) {
  if (!overlay || !modal) return;

  const form = modal.querySelector(".form");
  if (form) form.noValidate = true; // Deshabilita la validación HTML5 durante el cierre

  modal.classList.add("modal-closing");
  overlay.classList.add("overlay-closing");

  setTimeout(() => {
    // Eliminar clases de animación
    modal.classList.remove("modal-active", "modal-closing", "modal-opening");
    overlay.classList.remove(
      "overlay-active",
      "overlay-closing",
      "overlay-opening"
    );

    // Restablecer el formulario
    if (form) {
      form.noValidate = false; // Habilita la validación HTML5 de nuevo
      form.reset(); // Limpia los campos del formulario

      // Restablecer encabezado y acción del formulario
      const modalHeader = modal.querySelector(".modal-header h3");
      if (modalHeader) modalHeader.textContent = OriginalHeader;
      form.action = OriginalAction;

      // Limpiar mensajes de error y habilitar botón de envío
      const cedulaError = form.querySelector(".cedulaError");
      const sentBtn = form.querySelector(".sentBtn");
      if (cedulaError) cedulaError.textContent = "";
      // Nota: sentBtn.disabled ya se maneja en handleFormResponse al final del proceso.
      // Si el modal se cierra por clic manual, asegúrate de que el botón se re-habilite.
      if (sentBtn) sentBtn.disabled = false;

      // Resetear formularios multipasos si aplica
      const slidePageElement = form.querySelector(".slidePage");
      if (slidePageElement) {
        if (typeof resetMultiStepForm === "function") {
          resetMultiStepForm();
        } else {
          console.warn("La función resetMultiStepForm no está definida.");
        }
      } else {
        console.log(
          "Formulario multipasos no detectado, no se realiza reseteo."
        );
      }
    }

    // --- Limpiar Dropzone al cerrar el modal (solo si no fue un éxito de guardado) ---
    // Si el formulario se guardó exitosamente, Dropzone ya se destruyó en handleFormResponse.
    // Si el modal se cierra de otra forma (ej. clic en botón 'X' o fuera),
    // aseguramos que Dropzone se destruya y la lista de archivos temporales se reinicie.
    if (myDropzone) {
      // Si hay archivos en Dropzone que no fueron procesados (ej. el usuario cerró el modal)
      // esos archivos temporales quedan en el servidor. Un cron job es la mejor forma de limpiarlos.
      // Aquí solo limpiamos la interfaz y la lista de archivos temporales JS.
      myDropzone.destroy();
      myDropzone = null;
    }
    uploadedTempFiles = []; // Reiniciar la lista para la próxima vez

    // Eliminar el modal de la lista de activos
    delete activeModals[modal.id];
  }, 300); // Duración de la animación de cierre
}

// Detener la propagación de clics dentro del modal (para evitar cerrar el modal al hacer clic en su contenido)
document.addEventListener("click", (event) => {
  // Si el objetivo del clic es un elemento dentro de un modal-box que está activo
  const modalBox = event.target.closest(".modal-box");
  if (modalBox && modalBox.classList.contains("modal-active")) {
    event.stopPropagation();
  }
});
