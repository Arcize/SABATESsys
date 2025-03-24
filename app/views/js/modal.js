// Referencias a los elementos
const overlay = document.querySelector(".overlay-modal");
const openModalButton = document.querySelector(".open-modal");
const closeModalButton = document.querySelector(".close-modal");
const modal = document.querySelector(".modal-box");

// Función para abrir el modal
if (openModalButton && overlay && modal) {
    openModalButton.addEventListener("click", () => {
        overlay.classList.add("overlay-active");
        modal.classList.add("modal-active");
        setTimeout(() => {
            overlay.classList.add("overlay-opening");
            modal.classList.add("modal-opening");
        }, 0);
    });
}

// Función para cerrar el modal con animación
if (closeModalButton && overlay && modal) {
    closeModalButton.addEventListener("click", () => {
        modal.classList.add("modal-closing"); // Añade animación de salida
        overlay.classList.add("overlay-closing"); // Añade animación de salida
        setTimeout(() => {
            modal.classList.remove("modal-active", "modal-closing", "modal-opening");
            overlay.classList.remove(
                "overlay-active",
                "overlay-closing",
                "overlay-opening"
            );
        }, 300); // Coincide con la duración de la animación (0.3s)
    });
}

// Función para cerrar al hacer click fuera del modal
if (overlay && modal) {
    overlay.addEventListener("click", () => {
        modal.classList.add("modal-closing"); // Añade animación de salida
        overlay.classList.add("overlay-closing"); // Añade animación de salida
        setTimeout(() => {
            modal.classList.remove("modal-active", "modal-closing", "modal-opening");
            overlay.classList.remove(
                "overlay-active",
                "overlay-closing",
                "overlay-opening"
            );
        }, 300); // Coincide con la duración de la animación (0.3s)
    });

    // Evitar que el clic dentro del modal cierre el overlay
    modal.addEventListener("click", (event) => {
        event.stopPropagation(); // Detiene la propagación del evento hacia el overlay
    });
}
