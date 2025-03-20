const mediaQuery = window.matchMedia('(max-width: 1024px)');

const navbar = document.querySelector(".navbar");
(function () {
  const isCollapsed = localStorage.getItem("navbarCollapsed");
  if (isCollapsed === "true") {
    navbar.classList.add("collapsed");
  } else {
    navbar.classList.remove("collapsed");
  }
})();

// Cambia el estado y almacénalo en localStorage cuando se haga clic
function toggleNavbar() {
  const isCollapsed = navbar.classList.toggle("collapsed");
  localStorage.setItem("navbarCollapsed", isCollapsed);
}
mediaQuery.addEventListener('change', changer);

function changer(e) {
  if (e.matches) {
    if (!navbar.classList.contains("collapsed")) {
      navbar.classList.add("collapsed");
      localStorage.setItem("navbarCollapsed", true);
    }
  } else {
    if (navbar.classList.contains("collapsed")) {
      navbar.classList.remove("collapsed");
      localStorage.setItem("navbarCollapsed", false);
    }
  }
}
// Ejecutar inicialmente para comprobar el estado actual
changer(mediaQuery);
window.toggleNavbar = toggleNavbar;
