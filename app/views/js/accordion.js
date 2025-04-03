const headers = document.querySelectorAll(".accordion__header");

// Recuperar el índice almacenado en localStorage
const savedIndex = localStorage.getItem("activeAccordionIndex");

if (savedIndex !== null) {
    const allContents = document.querySelectorAll(".accordion__content");
    const allArrows = document.querySelectorAll(".imgArrow");
    const index = parseInt(savedIndex, 10);

    // Abrir el acordeón almacenado sin animación
    if (allContents[index]) {
        allContents[index].style.maxHeight = allContents[index].scrollHeight + "px";
        allContents[index].classList.add("accordion__content--active");

        // Deshabilitar temporalmente la transición de la flecha
        const arrow = allArrows[index];
        arrow.classList.add("dropdown-rotated");
        arrow.style.transition = "none";
        requestAnimationFrame(() => {
            arrow.style.transition = ""; // Restaurar la transición
        });

        // Evitar la animación inicial del contenido
        allContents[index].style.transition = "none";
        requestAnimationFrame(() => {
            allContents[index].style.transition = ""; // Restaurar la transición
        });
    }
}

headers.forEach((header, headerIndex) => {
    header.addEventListener("click", function () {
        const allContents = document.querySelectorAll(".accordion__content");
        const allArrows = document.querySelectorAll(".imgArrow");

        // Cerrar todos los contenidos abiertos y resetear las flechas
        allContents.forEach((content, index) => {
            if (index !== headerIndex) {
                content.style.maxHeight = null;
                content.classList.remove("accordion__content--active");
                allArrows[index].classList.remove("dropdown-rotated");
            }
        });

        // Alternar el contenido y la flecha actual
        toggleAccordion(allContents[headerIndex]);

        // Guardar el índice del acordeón abierto en localStorage
        if (allContents[headerIndex].classList.contains("accordion__content--active")) {
            localStorage.setItem("activeAccordionIndex", headerIndex);
        } else {
            localStorage.removeItem("activeAccordionIndex");
        }

        function toggleAccordion(nextContent) {
            let nextArrow = allArrows[headerIndex];
            if (nextContent) {
                if (nextContent.style.maxHeight) {
                    nextContent.style.maxHeight = null;
                    nextArrow.classList.remove("dropdown-rotated"); // Quitar rotación al cerrar
                } else {
                    nextContent.style.maxHeight = nextContent.scrollHeight + "px";
                    nextArrow.classList.add("dropdown-rotated"); // Añadir rotación al abrir
                }
                nextContent.classList.toggle("accordion__content--active");
            }
        }
    });
});
