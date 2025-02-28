// Función para mostrar/ocultar el dropdown al hacer clic
function toggleDropdown() {
    document.getElementById("dropdown").classList.toggle("show");

    // Aquí obtienes una colección de elementos, debes iterar para aplicar la clase 'rotate'
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
