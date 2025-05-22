document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll(".accordion__header");
    const navbarElement = document.querySelector('.navbar'); // Selector de tu navbar

    // Si no hay acordeones o navbar, no hacer nada.
    if (headers.length === 0 || !navbarElement) {
        if (navbarElement) {
            // Si no hay acordeones pero sí navbar, igual quitar la clase de carga.
            navbarElement.classList.remove('navbar-loading');
        }
        return;
    }

    // --- Sección 1: Restaurar el estado del acordeón desde localStorage SIN animación ---
    // Esta función se ejecuta inmediatamente dentro de DOMContentLoaded.
    // Se asume que '.navbar-loading' está en el HTML del navbar y el CSS correspondiente está activo.
    (function restoreAccordionState() {
        const savedIndexStr = localStorage.getItem("activeAccordionIndex");

        if (savedIndexStr !== null) {
            const activeIndex = parseInt(savedIndexStr, 10);

            // Validar el índice recuperado
            if (activeIndex >= 0 && activeIndex < headers.length) {
                const activeHeader = headers[activeIndex];
                
                // Encontrar el contenido y la flecha asociados al header activo
                // Esta lógica depende de tu estructura HTML.
                // Asumiendo: .navbar__item > .accordion__header, y .accordion (hermano de .navbar__item) > .accordion__content
                const accordionWrapper = activeHeader.closest('.navbar__item')?.nextElementSibling;
                let contentToOpen = null;
                if (accordionWrapper && accordionWrapper.classList.contains('accordion')) {
                    contentToOpen = accordionWrapper.querySelector('.accordion__content');
                }
                const arrowToRotate = activeHeader.querySelector(".imgArrow");

                if (contentToOpen) {
                    // Aplicar estilos para abrir. Gracias a '.navbar-loading', no debería haber animación.
                    contentToOpen.classList.add("accordion__content--active");
                    contentToOpen.style.maxHeight = contentToOpen.scrollHeight + "px";
                }
                if (arrowToRotate) {
                    arrowToRotate.classList.add("dropdown-rotated");
                }
            } else {
                // Índice inválido, limpiar localStorage
                localStorage.removeItem("activeAccordionIndex");
            }
        }
    })();

    // --- Sección 2: Habilitar transiciones ---
    // Eliminar la clase 'navbar-loading' DESPUÉS de aplicar los estilos iniciales.
    // setTimeout con 0ms es para asegurar que esto ocurra después del ciclo de renderizado actual.
    setTimeout(() => {
        if (navbarElement) {
            navbarElement.classList.remove('navbar-loading');
            // console.log('navbar-loading class removed, transitions enabled.');
        }
    }, 0); // Puedes probar con un valor ligeramente mayor (ej. 50) si 0 no es suficiente en algunos navegadores.

    // --- Sección 3: Listeners para la interacción del usuario ---
    headers.forEach((header, headerIndex) => {
        header.addEventListener("click", function () {
            // Encontrar el contenido y la flecha del header clickeado
            const clickedAccordionWrapper = header.closest('.navbar__item')?.nextElementSibling;
            let clickedContent = null;
            if (clickedAccordionWrapper && clickedAccordionWrapper.classList.contains('accordion')) {
                clickedContent = clickedAccordionWrapper.querySelector('.accordion__content');
            }
            const clickedArrow = header.querySelector(".imgArrow");

            if (!clickedContent) return; // No hay contenido para este header

            const isActive = clickedContent.classList.contains("accordion__content--active");

            // Cerrar todos los acordeones (opcional, si solo quieres uno abierto)
            headers.forEach((h, i) => {
                const otherAccordionWrapper = h.closest('.navbar__item')?.nextElementSibling;
                let content = null;
                if (otherAccordionWrapper && otherAccordionWrapper.classList.contains('accordion')) {
                    content = otherAccordionWrapper.querySelector('.accordion__content');
                }
                const arrow = h.querySelector(".imgArrow");

                // Cerrar si es OTRO acordeón, o si es el MISMO que estaba activo (para cerrarlo)
                if (content && (content !== clickedContent || (content === clickedContent && isActive))) {
                    content.style.maxHeight = null;
                    content.classList.remove("accordion__content--active");
                    if (arrow) {
                        arrow.classList.remove("dropdown-rotated");
                    }
                }
            });

            // Abrir el acordeón clickeado SI NO estaba activo
            if (!isActive) {
                clickedContent.classList.add("accordion__content--active");
                clickedContent.style.maxHeight = clickedContent.scrollHeight + "px";
                if (clickedArrow) {
                    clickedArrow.classList.add("dropdown-rotated");
                }
                localStorage.setItem("activeAccordionIndex", headerIndex.toString());
            } else {
                // Si se cerró el acordeón activo (porque isActive era true), eliminar del localStorage
                localStorage.removeItem("activeAccordionIndex");
            }
        });
    });
});