document.addEventListener("DOMContentLoaded", () => {
    const dropdownBtn = document.querySelector(".dropdown-btn");
    const dropdownMenu = document.querySelector(".filter-dropdown-menu");

    // Abrir/cerrar el menú desplegable al hacer clic en el botón
    dropdownBtn.addEventListener("click", (event) => {
        event.stopPropagation(); // Evita que el evento se propague
        dropdownMenu.classList.toggle("filter-dropdown-menu-visible");
    });

    // Cerrar el menú desplegable si se hace clic fuera de él
    document.addEventListener("click", () => {
        if (dropdownMenu.classList.contains("filter-dropdown-menu-visible")) {
            dropdownMenu.classList.remove("filter-dropdown-menu-visible");
        }
    });
});