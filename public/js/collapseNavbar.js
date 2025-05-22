// PARTE 1: Ejecución inmediata para aplicar el estado inicial sin destello y sin animación
// Esta IIFE (Immediately Invoked Function Expression) se ejecuta tan pronto como el navegador la lee.
(function() {
    // Estas constantes son necesarias para la lógica inicial.
    // Asegúrate de que coincidan con las usadas más abajo.
    const STORAGE_KEY_INIT = "navbarCollapsedPreference";
    const COLLAPSE_THRESHOLD_INIT = 992;

    // Intentamos seleccionar el navbar. Si este script está bien colocado (ej. al final del body),
    // el navbar ya debería existir en el DOM.
    const navbar = document.querySelector(".navbar");

    if (navbar) {
        const userPrefersCollapsedInit = localStorage.getItem(STORAGE_KEY_INIT) === 'true';
        const currentWidthInit = window.innerWidth;
        let initiallyCollapsed;

        if (currentWidthInit < COLLAPSE_THRESHOLD_INIT) {
            initiallyCollapsed = true;
        } else {
            initiallyCollapsed = userPrefersCollapsedInit;
        }

        // Aplicar la clase 'collapsed' si es necesario, ANTES del primer render completo.
        if (initiallyCollapsed) {
            navbar.classList.add('collapsed');
        }
        // Añadir 'no-transition' para que esta aplicación inicial NO tenga animación.
        // Esta clase se quitará después en DOMContentLoaded.
        navbar.classList.add('no-transition');
    } else {
        // Esto podría pasar si el script se carga antes de que el elemento navbar esté en el DOM.
        // Es crucial la correcta ubicación del script en el HTML.
        console.warn("Script de inicialización: Elemento .navbar no encontrado. El destello podría ocurrir o el estado inicial no aplicarse correctamente.");
    }
})();

// PARTE 2: Lógica principal que se ejecuta después de que el DOM esté completamente cargado.
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector(".navbar");
    const toggleButton = document.getElementById('navbar-toggle-button');

    // --- Configuración (puede reutilizar las de arriba si están en un scope accesible o redefinirlas) ---
    const STORAGE_KEY = "navbarCollapsedPreference";
    const COLLAPSE_THRESHOLD = 992;

    // Función para sincronizar el estado del navbar (clases) y el botón (disabled/enabled)
    // Esta función también es llamada en el resize.
    function syncNavbarStateAndButton() {
        if (!navbar) return;

        const currentWidth = window.innerWidth;
        const userPrefersCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
        let shouldBeCollapsed; // El estado que el navbar DEBERÍA tener ahora.

        if (currentWidth < COLLAPSE_THRESHOLD) {
            shouldBeCollapsed = true;
            if (toggleButton) {
                toggleButton.disabled = true;
            }
        } else {
            shouldBeCollapsed = userPrefersCollapsed;
            if (toggleButton) {
                toggleButton.disabled = false;
            }
        }

        // Aunque la IIFE ya aplicó un estado, esta función asegura la consistencia,
        // especialmente para el estado del botón y si el estado visual necesitara un ajuste
        // (aunque la IIFE debería haberlo manejado bien).
        // Forzamos la clase correcta.
        if (navbar.classList.contains('collapsed') !== shouldBeCollapsed) {
            navbar.classList.toggle('collapsed', shouldBeCollapsed);
        }
        // console.log(`Sync DOMReady: Width: ${currentWidth}, Pref: ${userPrefersCollapsed}, IsCollapsed: ${navbar.classList.contains('collapsed')}`);
    }

    // Manejador para el clic manual en el botón de toggle
    function handleManualToggle() {
        const currentWidth = window.innerWidth;
        if (!navbar || currentWidth < COLLAPSE_THRESHOLD) {
            // Esta comprobación es una salvaguarda, el botón debería estar deshabilitado por syncNavbarStateAndButton
            console.warn("Toggle manual prevenido en pantalla pequeña.");
            return;
        }

        const isNowCollapsed = navbar.classList.toggle('collapsed'); // Esto usará la animación CSS
        localStorage.setItem(STORAGE_KEY, isNowCollapsed);
    }

    // Manejador de redimensionamiento de ventana
    let resizeTimer;
    function handleResize() {
        clearTimeout(resizeTimer);
        // Usamos un pequeño debounce para no ejecutar la lógica en cada píxel de redimensionamiento.
        resizeTimer = setTimeout(syncNavbarStateAndButton, 50);
    }

    // --- Inicialización dentro de DOMContentLoaded ---
    if (navbar) {
        // 1. Sincronizar el estado del botón y cualquier ajuste final al estado del navbar.
        //    La clase 'collapsed' y 'no-transition' ya fueron aplicadas por la IIFE.
        syncNavbarStateAndButton();

        // 2. Quitar la clase 'no-transition' para habilitar animaciones para futuras interacciones.
        //    Usamos requestAnimationFrame para asegurar que esto ocurra después de que el navegador
        //    haya tenido la oportunidad de pintar el estado inicial (sin transición).
        requestAnimationFrame(() => {
            navbar.classList.remove('no-transition');
        });
        // Alternativamente, un setTimeout muy corto también puede funcionar:
        // setTimeout(() => { navbar.classList.remove('no-transition'); }, 0);

        // 3. Escuchar cambios en el tamaño de la ventana
        window.addEventListener('resize', handleResize);

        // 4. Escuchar clics en el botón de toggle (si existe)
        if (toggleButton) {
            toggleButton.addEventListener('click', handleManualToggle);
        } else {
            console.warn('No se encontró el botón con id="navbar-toggle-button". El toggle manual no funcionará.');
        }
    } else {
        // Si la IIFE no encontró el navbar, es probable que aquí tampoco.
        console.error("DOMContentLoaded: Elemento .navbar no encontrado en el DOM.");
    }
});