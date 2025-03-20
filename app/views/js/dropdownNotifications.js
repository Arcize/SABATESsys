// Función para mostrar/ocultar el dropdown de notificaciones al hacer clic
function toggleNotificationsDropdown() {
    // Cerrar el dropdown de usuario si está abierto
    var userDropdown = document.getElementById("dropdown");
    if (userDropdown && userDropdown.classList.contains("show")) {
        userDropdown.classList.remove("show");
        var dropdownImgs = document.getElementsByClassName("dropdownImg");
        for (var i = 0; i < dropdownImgs.length; i++) {
            dropdownImgs[i].classList.remove("rotate");
        }
    }

    // Mostrar/ocultar el dropdown de notificaciones
    document.getElementById("notificationsDropdown").classList.toggle("show");
}

// Cerrar todos los dropdowns si el usuario hace clic fuera de ellos
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var imgs = document.getElementsByClassName("dropdownImg");

        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }

        for (var j = 0; j < imgs.length; j++) {
            var rotatedImg = imgs[j];
            if (rotatedImg.classList.contains('rotate')) {
                rotatedImg.classList.remove('rotate');
            }
        }
    }
}