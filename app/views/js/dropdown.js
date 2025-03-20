// Función para mostrar/ocultar el dropdown de usuario al hacer clic
function toggleDropdown() {
    // Cerrar el dropdown de notificaciones si está abierto
    var notificationsDropdown = document.getElementById("notificationsDropdown");
    if (notificationsDropdown && notificationsDropdown.classList.contains("show")) {
        notificationsDropdown.classList.remove("show");
    }

    // Mostrar/ocultar el dropdown de usuario
    document.getElementById("dropdown").classList.toggle("show");

    // Rotar la flecha del dropdown
    var dropdownImgs = document.getElementsByClassName("dropdownImg");
    for (var i = 0; i < dropdownImgs.length; i++) {
        dropdownImgs[i].classList.toggle("rotate");
    }
}

// Cerrar el dropdown si el usuario hace clic fuera de él
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
