// Función para mostrar/ocultar el menú desplegable de usuario
function toggleUserDropdown() {
    // Cerrar el menú de notificaciones si está abierto
    var notificationsMenu = document.getElementById("notifications-menu");
    if (notificationsMenu && notificationsMenu.classList.contains("dropdown-visible")) {
        notificationsMenu.classList.remove("dropdown-visible");
    }

    // Mostrar/ocultar el menú desplegable de usuario
    document.getElementById("user-menu").classList.toggle("dropdown-visible");

    // Rotar la flecha del menú desplegable
    var dropdownArrows = document.getElementsByClassName("dropdown-arrow");
    for (var i = 0; i < dropdownArrows.length; i++) {
        dropdownArrows[i].classList.toggle("dropdown-rotated");
    }
}

// Función para mostrar/ocultar el menú de notificaciones
function toggleNotificationsMenu() {
    // Cerrar el menú de usuario si está abierto
    var userMenu = document.getElementById("user-menu");
    if (userMenu && userMenu.classList.contains("dropdown-visible")) {
        userMenu.classList.remove("dropdown-visible");
        var dropdownArrows = document.getElementsByClassName("dropdown-arrow");
        for (var i = 0; i < dropdownArrows.length; i++) {
            dropdownArrows[i].classList.remove("dropdown-rotated");
        }
    }

    // Mostrar/ocultar el menú de notificaciones
    document.getElementById("notifications-menu").classList.toggle("dropdown-visible");
}

// Cerrar todos los menús desplegables si el usuario hace clic fuera de ellos
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-button')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        var arrows = document.getElementsByClassName("dropdown-arrow");

        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('dropdown-visible')) {
                openDropdown.classList.remove('dropdown-visible');
            }
        }

        for (var j = 0; j < arrows.length; j++) {
            var rotatedArrow = arrows[j];
            if (rotatedArrow.classList.contains('dropdown-rotated')) {
                rotatedArrow.classList.remove('dropdown-rotated');
            }
        }
    }
}