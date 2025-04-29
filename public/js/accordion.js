document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll(".accordion__header");

    if (headers.length === 0) return; // Evita ejecutar el código si no hay acordeones

    // Recuperar el índice almacenado en localStorage
    const savedIndex = localStorage.getItem("activeAccordionIndex");

    if (savedIndex !== null) {
        const allContents = document.querySelectorAll(".accordion__content");
        const allArrows = document.querySelectorAll(".imgArrow");
        const index = parseInt(savedIndex, 10);

        // Verifica que el índice sea válido antes de aplicarlo
        if (index >= 0 && index < allContents.length) {
            allContents[index].style.maxHeight = allContents[index].scrollHeight + "px";
            allContents[index].classList.add("accordion__content--active");

            const arrow = allArrows[index];
            arrow?.classList.add("dropdown-rotated");
            requestAnimationFrame(() => {
                arrow.style.transition = "";
            });

            allContents[index].style.transition = "none";
            requestAnimationFrame(() => {
                allContents[index].style.transition = "";
            });
        } else {
            localStorage.removeItem("activeAccordionIndex"); // Borra el índice si es inválido
        }
    }

    headers.forEach((header, headerIndex) => {
        header.addEventListener("click", function () {
            const allContents = document.querySelectorAll(".accordion__content");
            const allArrows = document.querySelectorAll(".imgArrow");

            if (headerIndex >= allContents.length) return; // Prevenir errores si hay menos elementos de los esperados

            // Cierra todos los contenidos abiertos y resetea las flechas
            allContents.forEach((content, index) => {
                if (index !== headerIndex) {
                    content.style.maxHeight = null;
                    content.classList.remove("accordion__content--active");
                    allArrows[index]?.classList.remove("dropdown-rotated");
                }
            });

            toggleAccordion(allContents[headerIndex]);

            // Guardar el índice del acordeón abierto en localStorage
            if (allContents[headerIndex]?.classList.contains("accordion__content--active")) {
                localStorage.setItem("activeAccordionIndex", headerIndex);
            } else {
                localStorage.removeItem("activeAccordionIndex");
            }
        });

        function toggleAccordion(nextContent) {
            let nextArrow = document.querySelectorAll(".imgArrow")[headerIndex];
            if (nextContent) {
                if (nextContent.style.maxHeight) {
                    nextContent.style.maxHeight = null;
                    nextArrow?.classList.remove("dropdown-rotated"); // Quitar rotación al cerrar
                } else {
                    nextContent.style.maxHeight = nextContent.scrollHeight + "px";
                    nextArrow?.classList.add("dropdown-rotated"); // Añadir rotación al abrir
                }
                nextContent.classList.toggle("accordion__content--active");
            }
        }
    });
});
